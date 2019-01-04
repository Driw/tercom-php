<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\LoginTercom;
use tercom\dao\exceptions\DAOException;

/**
 * DAO para Acesso Tercom
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos acessos TERCOM, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar e selecionar, <b>acessos não pode ser excluídos</b>.
 *
 * Um acesso TERCOM possui os mesmos dados e regras de um acesso entretanto é necessário informar o funcionário TERCOM.
 *
 * @see GenericDAO
 * @see LoginTercom
 *
 * @author Andrew
 */
class LoginTercomDAO extends GenericDAO
{
	/**
	 * Procedimento interno para validação dos dados de um acesso TERCOM ao inserir e/ou atualizar.
	 * Acesso TERCOM devem ter seu acesso e funcionário TERCOM identificados.
	 * @param LoginTercom $loginTercom objeto do tipo acesso TERCOM à ser validado.
	 * @throws DAOException caso algum dos dados do acesso TERCOM não estejam de acordo.
	 */
	private function validate(LoginTercom $loginTercom): void
	{
		// TODO trocar DAOException para LoginTercomException

		// NOT NULL
		if ($loginTercom->getId() === 0) throw new DAOException('acesso não identificado');
		if ($loginTercom->getTercomEmployeeId() === 0) throw new DAOException('funcionário não identificado');

		// PRIMARY KEY
		if (!$this->existLogin($loginTercom->getId())) throw new DAOException('acesso desconhecido');
		if (!$this->existTercomEmployee($loginTercom->getTercomEmployeeId())) throw new DAOException('funcionário desconhecido');
	}

	/**
	 * Insere um novo acesso TERCOM no banco de dados com base num acesso já registrado.
	 * @param LoginTercom $loginCustomer objeto do tipo acesso TERCOM à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(LoginTercom $loginTercom): bool
	{
		$this->validate($loginTercom);

		$sql = "INSERT INTO logins_tercom (idLogin, idTercomEmployee)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $loginTercom->getId());
		$query->setInteger(2, $loginTercom->getTercomEmployeeId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Atualiza os dados de um acesso com base num acesso TERCOM existente no banco de dados.
	 * @param LoginTercom $loginTercom objeto do tipo acesso TERCOM à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function updateLogouts(LoginTercom $loginTercom): int
	{
		$this->validate($loginTercom);

		$sql = "UPDATE logins
				INNER JOIN logins_tercom ON logins_tercom.idLogin = logins.id
				SET logins.logout = ?
				WHERE logins_tercom.idLogin <> ? AND logins_tercom.idTercomEmployee = ?";

		$query = $this->createQuery($sql);
		$query->setBoolean(1, true);
		$query->setInteger(2, $loginTercom->getId());
		$query->setInteger(3, $loginTercom->getTercomEmployeeId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		$loginColumns = $this->buildQuery(LoginDAO::ALL_COLUMNS, 'logins');
		$tercomEmployeeColumns = $this->buildQuery(TercomEmployeeDAO::ALL_COLUMNS, 'tercom_employees', 'tercomEmployee');

		return "SELECT $loginColumns, $tercomEmployeeColumns
				FROM logins_tercom
				INNER JOIN logins ON logins.id = logins_tercom.idLogin
				INNER JOIN tercom_employees ON tercom_employees.id = logins_tercom.idTercomEmployee";
	}

	/**
	 * Selecione os dados de um acesso TERCOM através do código de identificação único do acesso e funcionário TERCOM.
	 * @param int $idLogin código de identificação único do acesso.
	 * @param int $idTercomEmployee código de identificação único do funcionário TERCOM.
	 * @return LoginTercom|NULL acesso TERCOM com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idLogin, int $idTercomEmployee): ?LoginTercom
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE logins_tercom.idLogin = ? AND logins_tercom.idTercomEmployee = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idLogin);
		$query->setInteger(2, $idTercomEmployee);

		$result = $query->execute();

		return $this->parseLoginTercom($result);
	}

	/**
	 * Verifica se um determinado código de identificação de acesso existe.
	 * @param int $idLogin código de identificação único do acesso.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existLogin(int $idLogin): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM logins
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idLogin);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação de funcionário TERCOM existe.
	 * @param int $idTercomEmployee código de identificação único do funcionário TERCOM.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existTercomEmployee(int $idTercomEmployee): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_employees
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idTercomEmployee);

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de acesso TERCOM.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return LoginTercom|NULL objeto do tipo acesso TERCOM com dados carregados ou NULL se não houver resultado.
	 */
	private function parseLoginTercom(Result $result): ?LoginTercom
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newLoginTercom($entry);
	}

	/**
	 * Procedimento interno para criar um objeto do tipo acesso TERCOM e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return LoginTercom aquisição de um objeto do tipo acesso TERCOM com dados carregados.
	 */
	private function newLoginTercom(array $entry): LoginTercom
	{
		$this->parseEntry($entry, 'tercomEmployee');

		$loginTercom = new LoginTercom();
		$loginTercom->fromArray($entry);

		return $loginTercom;
	}
}

