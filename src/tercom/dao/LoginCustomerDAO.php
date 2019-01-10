<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\dao\exceptions\DAOException;
use tercom\entities\LoginCustomer;

/**
 * DAO para Acesso Tercom
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos acessos de cliente, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar e selecionar, <b>acessos não pode ser excluídos</b>.
 *
 * Um acesso de cliente possui os mesmos dados e regras de um acesso entretanto é necessário informar o funcionário de cliente.
 *
 * @see GenericDAO
 * @see LoginTercom
 *
 * @author Andrew
 */
class LoginCustomerDAO extends GenericDAO
{
	/**
	 * Procedimento interno para validação dos dados de um acesso de cliente ao inserir e/ou atualizar.
	 * Acesso TERCOM devem ter seu acesso e funcionário de cliente identificados.
	 * @param LoginCustomer $loginCustomer objeto do tipo acesso de cliente à ser validado.
	 * @throws DAOException caso algum dos dados do acesso de cliente não estejam de acordo.
	 */
	private function validate(LoginCustomer $loginCustomer): void
	{
		// TODO trocar DAOException para LoginCustomerException

		// NOT NULL
		if ($loginCustomer->getId() === 0) throw new DAOException('acesso não identificado');
		if ($loginCustomer->getCustomerEmployeeId() === 0) throw new DAOException('funcionário não identificado');

		// PRIMARY KEY
		if (!$this->existLogin($loginCustomer->getId())) throw new DAOException('acesso desconhecido');
		if (!$this->existCustomerEmployee($loginCustomer->getCustomerEmployeeId())) throw new DAOException('funcionário desconhecido');
	}

	/**
	 * Insere um novo acesso de cliente no banco de dados com base num acesso já registrado.
	 * @param LoginCustomer $loginCustomer objeto do tipo acesso de cliente à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(LoginCustomer $loginCustomer): bool
	{
		$this->validate($loginCustomer);

		$sql = "INSERT INTO logins_customer (idLogin, idCustomerEmployee)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $loginCustomer->getId());
		$query->setInteger(2, $loginCustomer->getCustomerEmployeeId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Atualiza os dados de um acesso com base num acesso de cliente existente no banco de dados.
	 * @param LoginCustomer $loginCustomer objeto do tipo acesso de cliente à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function updateLogouts(LoginCustomer $loginCustomer): int
	{
		$this->validate($loginCustomer);

		$sql = "UPDATE logins
				INNER JOIN logins_customer ON logins_customer.idLogin = logins.id
				SET logins.logout = ?
				WHERE logins_customer.idLogin <> ? AND logins_customer.idCustomerEmployee = ?";

		$query = $this->createQuery($sql);
		$query->setBoolean(1, true);
		$query->setInteger(2, $loginCustomer->getId());
		$query->setInteger(3, $loginCustomer->getCustomerEmployeeId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		$loginColumns = $this->buildQuery(LoginDAO::ALL_COLUMNS, 'logins');
		$customerEmployeeColumns = $this->buildQuery(CustomerEmployeeDAO::ALL_COLUMNS, 'customer_employees', 'customerEmployee');
		$customerProfileColumns = $this->buildQuery(CustomerProfileDAO::ALL_COLUMNS, 'customer_profiles', 'customerEmployee_customerProfile');
		$customerColumns = $this->buildQuery(CustomerDAO::ALL_COLUMNS, 'customers', 'customerEmployee_customerProfile_customer');

		return "SELECT $loginColumns, $customerEmployeeColumns, $customerProfileColumns, $customerColumns
				FROM logins_customer
				INNER JOIN logins ON logins.id = logins_customer.idLogin
				INNER JOIN customer_employees ON customer_employees.id = logins_customer.idCustomerEmployee
				INNER JOIN customer_profiles ON customer_employees.idCustomerProfile = customer_profiles.id
				INNER JOIN customers ON customers.id = customer_profiles.idCustomer";
	}

	/**
	 * Selecione os dados de um acesso de cliente através do código de identificação único do acesso e funcionário de cliente.
	 * @param int $idLogin código de identificação único do acesso.
	 * @param int $idCustomerEmployee código de identificação único do funcionário de cliente.
	 * @return LoginCustomer|NULL acesso de cliente com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idLogin, int $idCustomerEmployee): ?LoginCustomer
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE logins_customer.idLogin = ? AND logins_customer.idCustomerEmployee = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idLogin);
		$query->setInteger(2, $idCustomerEmployee);

		$result = $query->execute();

		return $this->parseLoginCustomer($result);
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
	 * Verifica se um determinado código de identificação de funcionário de cliente existe.
	 * @param int $idCustomerEmployee código de identificação único do funcionário de cliente.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existCustomerEmployee(int $idCustomerEmployee): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_employees
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idCustomerEmployee);

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de acesso de cliente.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return LoginCustomer|NULL objeto do tipo acesso de cliente com dados carregados ou NULL se não houver resultado.
	 */
	private function parseLoginCustomer(Result $result): ?LoginCustomer
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newLoginCustomer($entry);
	}

	/**
	 * Procedimento interno para criar um objeto do tipo acesso de cliente e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return LoginCustomer aquisição de um objeto do tipo acesso de cliente com dados carregados.
	 */
	private function newLoginCustomer(array $entry): LoginCustomer
	{
		$this->parseEntry($entry, 'customerEmployee');
		$this->parseEntry($entry['customerEmployee'], 'customerProfile');
		$this->parseEntry($entry['customerEmployee']['customerProfile'], 'customer');

		$loginCustomer = new LoginCustomer();
		$loginCustomer->fromArray($entry);

		return $loginCustomer;
	}
}

