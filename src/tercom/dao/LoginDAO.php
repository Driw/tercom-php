<?php

namespace tercom\dao;

use dProject\Primitive\StringUtil;
use tercom\dao\exceptions\DAOException;
use tercom\entities\Login;

/**
 * DAO para Acesso
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos acessos, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar e gerar tokens únicos, <b>acessos não pode ser excluídos</b>.
 *
 * Um acesso deve possuir um token gerado pelo sistema, endereço de IP, navegador
 * (no caso dos mobiles é o tipo de sistema operacional e versão) e um horário de expiração.
 *
 * @see GenericDAO
 * @see GenericLogin
 *
 * @author Andrew
 */
class LoginDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de acessos.
	 */
	public const ALL_COLUMNS = ['id', 'token', 'logout', 'ipAddress', 'browser', 'expiration', 'register'];

	/**
	 * Procedimento interno para validação dos dados de um acesso ao inserir e/ou atualizar.
	 * Acessos não podem ter token, endereço de IP, navegador e horário de expiração não informados.
	 * @param Login $login objeto do tipo acesso à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws DAOException caso algum dos dados do acesso não estejam de acordo.
	 */
	private function validate(Login $login, bool $validateId)
	{
		// FIXME trocar DAOException para LoginException

		// PRIMARY KEY
		if ($validateId) {
			if ($login->getId() === 0)
				throw new DAOException('login não identificado');
		} else {
			if ($login->getId() !== 0)
				throw new DAOException('login já identificado');
		}

		// NOT NULL
		if (StringUtil::isEmpty($login->getToken())) throw new DAOException('token não informado');
		if (StringUtil::isEmpty($login->getIpAddress())) throw new DAOException('endereço de IP não informado');
		if (StringUtil::isEmpty($login->getBrowser())) throw new DAOException('navegador não informado');
		if ($login->getExpiration()->getTimestamp() === 0) throw new DAOException('horário de expiração não informado');
	}

	/**
	 * Insere um novo acesso no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param Login $login objeto do tipo acesso à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
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
	 * Atualiza os dados de um acesso já existente no banco de dados.
	 * @param Login $login objeto do tipo acesso à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
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
	 * Gera um novo token de acesso único com base nos parâmetros informados.
	 * Tenta gerar repetidamente um token até que este seja único.
	 * Tokens gerados não estão reservados, portanto deve ser usado o mais rápido possível após ser gerado.
	 * @param int $idRelationship código de identificação único da relação com o acesso,
	 * isso vai depender do tipo de acesso que está sendo registrado.
	 * @param string $ipAddress endereço de IP usado para realizar o acesso.
	 * @return string aquisição da string contendo o valor único do token.
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
	 * Verifica se um determinado token de acesso está disponível para um acesso.
	 * @param string $token token de acesso à verificar.
	 * @param int $idLogin código de identificação do acesso à desconsiderar
	 * ou zero caso seja um novo acesso.
	 * @return bool true se estiver disponível ou false caso contrário.
	 */
	public function existToken(string $token, int $idLogin = 0): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM logins
				WHERE token = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $token);
		$query->setInteger(2, $idLogin);

		return $this->parseQueryExist($query);
	}
}

