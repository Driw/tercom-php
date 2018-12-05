<?php

namespace tercom\control;

use tercom\entities\Login;
use tercom\dao\LoginDAO;

/**
 * @author Andrew
 */
abstract class LoginControl extends GenericControl
{
	/**
	 * @var LoginDAO
	 */
	private $loginDAO;

	/**
	 *
	 */
	public function __construct()
	{
		$this->loginDAO = new LoginDAO();
	}

	/**
	 *
	 * @param Login $login
	 * @return bool
	 */
	protected function addLogin(Login $login): bool
	{
		$login->getExpiration()->setTimestamp($this->newExpirationTime());

		return $this->loginDAO->insert($login);
	}

	/**
	 *
	 * @param Login $login
	 * @return bool
	 */
	protected function setLogoff(Login $login): bool
	{
		$login->setLogout(true);
		$login->getExpiration()->setTimestamp(time());

		return $this->loginDAO->update($login);
	}

	/**
	 *
	 * @param int $idRelationship
	 * @param string $ipAddress
	 * @return string
	 */
	protected function newToken(int $idRelationship, string $ipAddress): string
	{
		return $this->loginDAO->generateToken($idRelationship, $ipAddress);
	}

	/**
	 *
	 * @param Login $login
	 * @param string $time
	 * @throws ControlException
	 * @return bool
	 */
	protected function keepLoginAlive(Login $login): bool
	{
		if ($login->isLogout())
			throw new ControlException('login já encerrado');

		if ($login->getExpiration()->getTimestamp() <= time())
		{
			$login->setLogout(true);
			$this->loginDAO->update($login);

			throw new ControlException('login expirado');
		}

		$login->getExpiration()->setTimestamp($this->newExpirationTime());
		return $this->loginDAO->update($login);
	}

	/**
	 *
	 * @return int
	 */
	public function newExpirationTime(): int
	{
		return strtotime('+15 minutes');
	}
}

