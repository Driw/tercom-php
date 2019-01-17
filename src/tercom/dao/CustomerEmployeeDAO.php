<?php

namespace tercom\dao;

use tercom\entities\CustomerEmployee;
use tercom\entities\CustomerProfile;
use tercom\entities\lists\CustomerEmployees;
use dProject\MySQL\Result;
use tercom\Functions;
use dProject\Primitive\StringUtil;
use tercom\entities\Customer;
use tercom\dao\exceptions\DAOException;

/**
 * DAO para Funcionário de Cliente
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos funcionários de clientes, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar e selecionar; <b>funcionários de clientes não podem ser excluídos</b>.
 *
 * Funcionários de cliente são vinculados a um único cliente e devem possuir obrigatoriamente um perfil de cliente.
 * É obrigatório ainda possuir um endereço de e-mail e senha de acesso e nome, telefone e celular são opcionais.
 * Funcionários não podem ser excluídos portanto é exibido uma opção de ativar/desativar.
 *
 * @see GenericDAO
 * @see CustomerEmployee
 * @see CustomerEmployees
 *
 * @author Andrew
 */
class CustomerEmployeeDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de funcionários de clientes.
	 */
	public const ALL_COLUMNS = ['id', 'idCustomerProfile', 'name', 'email', 'password', 'idPhone', 'idCellPhone', 'enabled', 'register'];
	/**
	 * @var array nome das colunas de perfil da tabela de funcionários de clientes.
	 */
	public const ALL_PROFILE_COLUMNS = ['id', 'idCustomerProfile', 'name', 'email', 'idPhone', 'idCellPhone'];

	/**
	 * Procedimento interno para validação dos dados de um funcionário de cliente ao inserir e/ou atualizar.
	 * Funcionários de cliente não podem possuir perfil de cliente, nome, endereço de e-mail e senha em branco.
	 * @param CustomerEmployee $customerEmployee objeto do tipo funcionário de cliente à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws DAOException caso algum dos dados do funcionário de cliente não estejam de acordo.
	 */
	private function validate(CustomerEmployee $customerEmployee, bool $validateID)
	{
		// FIXME Transformar DAOException em CustomerEmployeeException

		// PRIMARY KEY
		if ($validateID) {
			if ($customerEmployee->getId() === 0)
				throw new DAOException('funcionário de cliente não identificado');
		} else {
			if ($customerEmployee->getId() !== 0)
				throw new DAOException('funcionário de cliente já identificado');
		}

		// NOT NULL
		if (StringUtil::isEmpty($customerEmployee->getName())) throw new DAOException('nome não informado');
		if (StringUtil::isEmpty($customerEmployee->getEmail())) throw new DAOException('endereço de e-mail não informado');
		if (StringUtil::isEmpty($customerEmployee->getPassword())) throw new DAOException('senha não informada');

		// UNIQUE KEY
		if ($this->existEmail($customerEmployee->getEmail(), $customerEmployee->getId())) throw new DAOException('endereço de e-mail indisponível');

		// FOREIGN KEY
		if ($customerEmployee->getCustomerProfileId() === 0) throw new DAOException('perfil não informado');
	}

	/**
	 * Insere um novo funcionário de cliente no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param CustomerEmployee $customerEmplyee objeto do tipo funcionário de cliente à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(CustomerEmployee $customerEmplyee): bool
	{
		$customerEmplyee->getRegister()->setTimestamp(time());
		$this->validate($customerEmplyee, false);

		$sql = "INSERT INTO customer_employees (idCustomerProfile, name, email, password, idPhone, idCellPhone, enabled)
				VALUES (?, ?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerEmplyee->getCustomerProfileId());
		$query->setString(2, $customerEmplyee->getName());
		$query->setString(3, $customerEmplyee->getEmail());
		$query->setString(4, $customerEmplyee->getPassword());
		$query->setInteger(5, $this->parseNullID($customerEmplyee->getPhone()->getId()));
		$query->setInteger(6, $this->parseNullID($customerEmplyee->getCellphone()->getId()));
		$query->setBoolean(7, $customerEmplyee->isEnable());

		if (($result = $query->execute())->isSuccessful())
			$customerEmplyee->setId($result->getInsertID());

		return $customerEmplyee->getId() !== 0;
	}

	/**
	 * Atualiza os dados de um funcionário de cliente já existente no banco de dados.
	 * @param CustomerEmployee $customerEmplyee objeto do tipo funcionário de cliente à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(CustomerEmployee $customerEmplyee): bool
	{
		$this->validate($customerEmplyee, true);

		$sql = "UPDATE customer_employees
				SET idCustomerProfile = ?, name = ?, email = ?, password = ?, idPhone = ?, idCellPhone = ?, enabled = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerEmplyee->getCustomerProfileId());
		$query->setString(2, $customerEmplyee->getName());
		$query->setString(3, $customerEmplyee->getEmail());
		$query->setString(4, $customerEmplyee->getPassword());
		$query->setInteger(5, $this->parseNullID($customerEmplyee->getPhone()->getId()));
		$query->setInteger(6, $this->parseNullID($customerEmplyee->getCellphone()->getId()));
		$query->setBoolean(7, $customerEmplyee->isEnable());
		$query->setInteger(8, $customerEmplyee->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Atualiza o estado de inatividade de um funcionário de cliente no banco de dados.
	 * @param CustomerEmployee $customerEmplyee objeto do tipo funcionário de cliente à atualizar.
	 * @return bool true se atualizado ou false caso contrário.
	 */
	public function updateEnabled(CustomerEmployee $customerEmplyee): bool
	{
		$sql = "UPDATE customer_employees
				SET enabled = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setBoolean(1, $customerEmplyee->isEnable());
		$query->setInteger(2, $customerEmplyee->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelectProfile(): string
	{
		$customerEmployeeColumns = $this->buildQuery(self::ALL_COLUMNS, 'customer_employees');
		$customerProfileColumns = $this->buildQuery(CustomerProfileDAO::ALL_COLUMNS, 'customer_profiles', 'customerProfile');

		return "SELECT $customerEmployeeColumns, $customerProfileColumns
				FROM customer_employees
				INNER JOIN customer_profiles ON customer_profiles.id = customer_employees.idCustomerProfile";
	}

	/**
	 * Selecione os dados de um funcionário de cliente através do seu código de identificação único.
	 * @param int $idCustomerEmployee código de identificação único do funcionário de cliente.
	 * @return CustomerEmployee|NULL funcionário de cliente com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idCustomerEmployee): ?CustomerEmployee
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				WHERE customer_employees.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idCustomerEmployee);

		$result = $query->execute();

		return $this->parseCustomerEmployee($result);
	}


	/**
	 * Seleciona os dados de um funcionário de cliente através do seu endereço de e-mail cadastrado.
	 * @param string $email endereço de e-mail do funcionário de cliente à selecionar.
	 * @return CustomerEmployee|NULL funcionário de cliente com os dados carregados ou NULL se não encontrado.
	 */
	public function selectByEmail(string $email): ?CustomerEmployee
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				WHERE customer_employees.email = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $email);

		$result = $query->execute();

		return $this->parseCustomerEmployee($result);
	}

	/**
	 * Seleciona os dados de todos os funcionários de clientes registrados no banco de dados sem ordenação.
	 * @return CustomerEmployees aquisição da lista de funcionários de cliente atualmente registrados.
	 */
	public function selectAll(): CustomerEmployees
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				ORDER BY customer_employees.name";

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseCustomerEmployees($result);
	}

	/**
	 * Selecione os dados de todos os funcionários de clientes registrados filtrados por perfil de cliente.
	 * @param CustomerProfile $customerProfile objeto do tipo perfil de cliente à filtrar.
	 * @return CustomerEmployees aquisição da lista de funcionários de cliente filtrados.
	 */
	public function selectByProfile(CustomerProfile $customerProfile): CustomerEmployees
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				WHERE customer_profiles.id = ?
				ORDER BY customer_employees.name";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());

		$result = $query->execute();

		return $this->parseCustomerEmployees($result);
	}

	/**
	 * Selecione os dados de todos os funcionários de clientes registrados filtrados por cliente.
	 * @param Customer $customer objeto do tipo cliente à filtrar.
	 * @return CustomerEmployees aquisição da lista de funcionários de cliente filtrados.
	 */
	public function selectByCustomer(Customer $customer): CustomerEmployees
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				INNER JOIN customers ON customers.id = customer_profiles.idCustomer
				WHERE customers.id = ?
				ORDER BY customer_employees.name";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());

		$result = $query->execute();

		return $this->parseCustomerEmployees($result);
	}

	/**
	 * Verifica se um determinado endereço de e-mail já foi utilizado entre os funcionários de clientes.
	 * @param string $email endereço de e-mail à verificar a existência.
	 * @param int $idCustomerEmployee código de identificação do funcionário de cliente à desconsiderar
	 * ou zero caso seja um novo funcionário de cliente.
	 * @return bool true se já existir ou false caso contrário.
	 */
	public function existEmail(string $email, int $idCustomerEmployee = 0): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_employees
				WHERE email = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $email);
		$query->setInteger(2, $idCustomerEmployee);

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de funcionário de clientes.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return CustomerEmployee|NULL objeto do tipo funcionário de cliente com dados carregados ou NULL se não houver resultado.
	 */
	private function parseCustomerEmployee(Result $result): ?CustomerEmployee
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newCustomerEmployee($entry);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar os objetos de funcionário de cliente.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return CustomerEmployees aquisição da lista de funcionário de cliente a partir da consulta.
	 */
	private function parseCustomerEmployees(Result $result): CustomerEmployees
	{
		$customerEmployees = new CustomerEmployees();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$customerEmployee = $this->newCustomerEmployee($entry);
			$customerEmployees->add($customerEmployee);
		}

		return $customerEmployees;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo funcionário de cliente e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return CustomerEmployee aquisição de um objeto do tipo funcionário de cliente com dados carregados.
	 */
	private function newCustomerEmployee(array $entry): CustomerEmployee
	{
		$customerProfile = Functions::parseEntrySQL($entry, 'customerProfile');
		$customerProfile['customer']['id'] = $customerProfile['idCustomer']; unset($customerProfile['idCustomer']);

		$customerEmployee = new CustomerEmployee();
		$customerEmployee->fromArray($entry);
		$customerEmployee->getCustomerProfile()->fromArray($customerProfile);

		return $customerEmployee;
	}
}

