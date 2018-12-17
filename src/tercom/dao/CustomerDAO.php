<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\dao\exceptions\CustomerDAOException;
use tercom\entities\Customer;
use tercom\entities\lists\Customers;

/**
 * DAO para Clientes
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos clientes, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar, selecionar e desativar (<b>não pode excluir</b>).
 *
 * @see GenericDAO
 * @see Customer
 * @see Customers
 *
 * @author Andrew
 *
 */
class CustomerDAO extends GenericDAO
{
	/**
	 *
	 * @param Customer $customer
	 * @param bool $validateID
	 * @throws CustomerDAOException
	 */
	private function validate(Customer $customer, bool $validateID)
	{
		// PRIMARY KEY
		if ($validateID) {
			if ($customer->getId() === 0)
				throw CustomerDAOException::newNoId();
		} else {
			if ($customer->getId() !== 0)
				throw CustomerDAOException::newHasId();
		}

		// NOT NULL
		if (StringUtil::isEmpty($customer->getStateRegistry())) throw CustomerDAOException::newStateRegistryEmpty();
		if (StringUtil::isEmpty($customer->getCnpj())) throw CustomerDAOException::newCnpjEmpty();
		if (StringUtil::isEmpty($customer->getCompanyName())) throw CustomerDAOException::newCompanyNameEmpty();
		if (StringUtil::isEmpty($customer->getFantasyName())) throw CustomerDAOException::newFantasyNameEmpty();
		if (StringUtil::isEmpty($customer->getEmail())) throw CustomerDAOException::newEmailEmpty();

		// UNIQUE KEY
		if ($this->existCnpj($customer->getCnpj())) throw CustomerDAOException::newUnavaiableCnpj();
		if ($this->existCompanyName($customer->getCompanyName())) throw CustomerDAOException::newUnavaiableCompanyName();
	}

	/**
	 * Insere um novo cliente no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param Customer $customer objeto do tipo cliente à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(Customer $customer): bool
	{
		$this->validate($customer, false);
		$customer->getRegister()->setTimestamp(time());

		$sql = "INSERT INTO customers (stateRegistry, cnpj, companyName, fantasyName, email, inactive, register)
				VALUES (?, ?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $customer->getStateRegistry());
		$query->setString(2, $customer->getCnpj());
		$query->setString(3, $customer->getCompanyName());
		$query->setString(4, $customer->getFantasyName());
		$query->setString(5, $customer->getEmail());
		$query->setBoolean(6, $customer->isInactive());
		$query->setDateTime(7, $customer->getRegister());

		if (($result = $query->execute())->isSuccessful())
			$customer->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 * Atualiza os dados de um clinete já existente no banco de dados.
	 * @param Customer $customer objeto do tipo clinete à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(Customer $customer): bool
	{
		$this->validate($customer, true);

		$sql = "UPDATE customers
				SET stateRegistry = ?, cnpj = ?, companyName = ?, fantasyName = ?, email = ?, inactive = ?, register = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $customer->getStateRegistry());
		$query->setString(2, $customer->getCnpj());
		$query->setString(3, $customer->getCompanyName());
		$query->setString(4, $customer->getFantasyName());
		$query->setString(5, $customer->getEmail());
		$query->setBoolean(6, $customer->isInactive());
		$query->setDateTime(7, $customer->getRegister());
		$query->setInteger(8, $customer->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Atualiza o estado de inatividade de um clinete já existente no banco de dados.
	 * @param Customer $customer objeto do tipo clinete à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function updateInactive(Customer $customer): bool
	{
		$this->validate($customer, true);

		$sql = "UPDATE customers
				SET inactive = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setBoolean(1, $customer->isInactive());
		$query->setInteger(2, $customer->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSqlCustomer(): string
	{
		return "SELECT id, stateRegistry, cnpj, companyName, fantasyName, email, inactive, register
				FROM customers";
	}

	/**
	 * Selecione os dados de um cliente através do seu código de identificação único.
	 * @param int $idCustomer código de identificação único do cliente.
	 * @return Customer|NULL cliente com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idCustomer): ?Customer
	{
		$sqlCustomer = $this->newSqlCustomer();
		$sql = "$sqlCustomer
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idCustomer);

		$result = $query->execute();

		return $this->parseCustomer($result);
	}

	/**
	 * Seleciona os dados de um cliente através do seu Cadastro Nacional de Pessoa Jurídica (CNPJ).
	 * @param string $cnpj número do Cadastro Nacional de Pessoa Jurídica do cliente.
	 * @return Customer|NULL cliente com os dados carregados ou NULL se não encontrado.
	 */
	public function selectByCnpj(string $cnpj): ?Customer
	{
		$sqlCustomer = $this->newSqlCustomer();
		$sql = "$sqlCustomer
				WHERE cnpj = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $cnpj);

		$result = $query->execute();

		return $this->parseCustomer($result);
	}

	/**
	 * Seleciona os dados de todos os clientes registrados no banco de dados sem ordenação.
	 * @return Customers aquisição da lista de clientes atualmente registrados.
	 */
	public function selectAll(): Customers
	{
		$sql = $this->newSqlCustomer();

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseCustomers($result);
	}

	/**
	 * Seleciona os dados dos clientes no banco de dados filtrados pela inscrição estadual.
	 * @param string $stateRegistry inscrição estadual parcial ou completa para filtro.
	 * @return Customers aquisição da lista de clientes conforme filtro.
	 */
	public function selectLikeStateRegistry(string $stateRegistry): Customers
	{
		$sqlCustomer = $this->newSqlCustomer();
		$sql = "$sqlCustomer
				WHERE stateRegistry LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$stateRegistry%");

		$result = $query->execute();

		return $this->parseCustomers($result);
	}

	/**
	 * Seleciona os dados dos clientes no banco de dados filtrados pelo CNPJ.
	 * @param string $cnpj número parcial ou completo do CNPJ para filtro.
	 * @return Customers aquisição da lista de clientes conforme filtro.
	 */
	public function selectLikeCnpj(string $cnpj): Customers
	{
		$sqlCustomer = $this->newSqlCustomer();
		$sql = "$sqlCustomer
				WHERE cnpj LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$cnpj%");

		$result = $query->execute();

		return $this->parseCustomers($result);
	}

	/**
	 * Seleciona os dados dos clientes no banco de dados filtrados pelo nome fantasia.
	 * @param string $fantasyName nome fantasia parcial ou completa para filtro.
	 * @return Customers aquisição da lista de clientes conforme filtro.
	 */
	public function selectLikeFantasyName(string $fantasyName): Customers
	{
		$sqlCustomer = $this->newSqlCustomer();
		$sql = "$sqlCustomer
				WHERE fantasyName = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $fantasyName);

		$result = $query->execute();

		return $this->parseCustomers($result);
	}

	/**
	 * Verifica se um determinado número de CNPJ está disponível para um cliente.
	 * @param string $cnpj número do cadastro nacional de pessoa jurídica.
	 * @param int $idCustomer código de identificação do cliente à desconsiderar
	 * ou zero caso seja um novo cliente.
	 * @return bool true se estiver disponível ou false caso contrário.
	 */
	public function existCnpj(string $cnpj, int $idCustomer = 0): int
	{
		$sql = "SELECT COUNT(*) qty
				FROM customers
				WHERE cnpj = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $cnpj);
		$query->setInteger(2, $idCustomer);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinada razão social está disponível para um cliente.
	 * @param string $companyName razão social.
	 * @param int $idCustomer código de identificação do cliente à desconsiderar
	 * ou zero caso seja um novo cliente.
	 * @return bool true se estiver disponível ou false caso contrário.
	 */
	public function existCompanyName(string $companyName, int $idCustomer = 0): int
	{
		$sql = "SELECT COUNT(*) qty
				FROM customers
				WHERE companyName = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $companyName);
		$query->setInteger(2, $idCustomer);

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de cliente.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Customer|NULL objeto do tipo cliente com dados carregados ou NULL se não houver resultado.
	 */
	private function parseCustomer(Result $result, int $idCustomer = 0): ?Customer
	{
		if (!$result->hasNext())
			return null;

		$entry = $result->next();
		$customer = $this->newCustomer($entry);

		return $customer;
	}

	/**
	 * Procedimento inerno para analisar o resultado de uma consulta e criar os objetos de cliente.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Customers aquisição da lista de clientes a partir da consulta.
	 */
	private function parseCustomers(Result $result): Customers
	{
		$customers = new Customers();

		while ($result->hasNext())
		{
			$entry = $result->next();
			$customer = $this->newCustomer($entry);
			$customers->add($customer);
		}

		return $customers;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo cliente e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return Customer aquisição de um objeto do tipo cliente com dados carregados.
	 */
	private function newCustomer(array $entry): Customer
	{
		$customer = new Customer();
		$customer->fromArray($entry);

		return $customer;
	}
}

