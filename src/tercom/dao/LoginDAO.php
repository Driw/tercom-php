<?php

namespace tercom\dao;

use tercom\dao\exceptions\DAOException;
use tercom\entities\Login;
use dProject\Primitive\StringUtil;

/**
 * @see GenericDAO
 * @author Andrew
 */
class LoginDAO extends GenericDAO
{
	/**
	 * @var array
	 */
	public const ALL_COLUMNS = ['id', 'token', 'logout', 'ipAddress', 'browser', 'expiration', 'register'];

	/**
	 *
	 * @param Login $login
	 * @param bool $validateID
	 * @throws DAOException
	 */
	private function validate(Login $login, bool $validateID)
	{
		if ($validateID) {
			if ($login->getId() === 0)
				throw new DAOException('login não identificado');
		} else {
			if ($login->getId() !== 0)
				throw new DAOException('login já identificado');
		}

		if (StringUtil::isEmpty($login->getToken())) throw new DAOException('token não informado');
		if (StringUtil::isEmpty($login->getIpAddress())) throw new DAOException('endereço de IP não informado');
		if (StringUtil::isEmpty($login->getBrowser())) throw new DAOException('navegador não informado');
		if ($login->getExpiration()->getTimestamp() === 0) throw new DAOException('horário de expiração não informado');
	}

	/**
	 *
	 * @param Login $login
	 * @return bool
	 */
	public function insert(Login $login): bool
	{
		$login->getRegister()->setTimestamp(time());
		$this->validate($login, false);

		$sql = "INSERT INTO logins (token, logout, ipAddress, browser, expiration, register)
				VALUES (?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $login->getToken());
		$query->setBoolean(2, $login->isLogout());
		$query->setString(3, $login->getIpAddress());
		$query->setString(4, $login->getBrowser());
		$query->setDateTime(5, $login->getExpiration());
		$query->setDateTime(6, $login->getRegister());

		if (($result = $query->execute())->isSuccessful())
			$login->setId($result->getInsertID());

		return $login->getId() !== 0;
	}

	/**
	 *
	 * @param Login $login
	 * @return bool
	 */
	public function update(Login $login): bool
	{
		$this->validate($login, true);

		$sql = "UPDATE logins
				SET logout = ?, ipAddress = ?, browser = ?, expiration = ?
				WHERE id = ? AND token = ?";

		$query = $this->createQuery($sql);
		$query->setBoolean(1, $login->isLogout());
		$query->setString(2, $login->getIpAddress());
		$query->setString(3, $login->getBrowser());
		$query->setDateTime(4, $login->getExpiration());
		$query->setInteger(5, $login->getId());
		$query->setString(6, $login->getToken());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param int $idRelationship
	 * @param string $ipAddress
	 * @return string
	 */
	public function generateToken(int $idRelationship, string $ipAddress): string
	{
		$token = null;

		while ($token === null)
		{
			$token = md5(uniqid("$idRelationship#$ipAddress#".time()));

			if ($this->existToken($token))
				$token = null;
		}

		return $token;
	}

	/**
	 *
	 * @param string $token
	 * @return bool
	 */
	public function existToken(string $token, int $idLogin = 0): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM logins
				WHERE token = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $token);
		$query->setInteger(2, $idLogin);

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) > 0;
	}
}

