<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\Functions;
use tercom\dao\exceptions\DAOException;
use tercom\entities\Customer;
use tercom\entities\CustomerProfile;
use tercom\entities\lists\CustomerProfiles;

/**
 * DAO para Perfil de Cliente
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos perfis de cliente, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar e selecionar e excluir (se não houver referências).
 *
 * Perfil do cliente deve estar vinculado a um cliente, possuir um nome e um nível de assinatura.
 * Nível de assinatura delimita o valor máximo de permissões que podem ser atribuídas ao perfil.
 *
 * @see GenericDAO
 * @see CustomerProfile
 * @see CustomerProfiles
 *
 * @author Andrew
 */
class CustomerProfileDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de perfil de cliente.
	 */
	public const ALL_COLUMNS = ['id', 'idCustomer', 'name', 'assignmentLevel'];

	/**
	 * Procedimento interno para validação dos dados de um perfil de cliente ao inserir e/ou atualizar.
	 * Perfil de cliente deve possuir obrigatoriamnete um cliente vinculado, nome e nível de assinatura.
	 * @param CustomerProfile $customerProfile objeto do tipo perfil de cliente à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws DAOException caso algum dos dados do perfil de cliente não estejam de acordo.
	 */
	private function validate(CustomerProfile $customerProfile, bool $validateId)
	{
		// FIXME trocar DAOException para CustomerProfileException

		// PRIMARY KEY
		if ($validateId) {
			if ($customerProfile->getId() === 0)
				throw new DAOException('perfil não identificado');
		} else {
			if ($customerProfile->getId() !== 0)
				throw new DAOException('perfil já identificado');
		}

		// UNIQUE KEY
		if ($this->existName($customerProfile->getCustomer(), $customerProfile->getName(), $customerProfile->getId())) throw new DAOException('nome indisponível');

		// NOT NULL
		if (StringUtil::isEmpty($customerProfile->getName())) throw new DAOException('nome não definido');
		if ($customerProfile->getAssignmentLevel() === 0) throw new DAOException('nível de assinatura não definido');
		if ($customerProfile->getCustomerId() === 0) throw new DAOException('cliente não identificado');

		// FOREIGN KEY
		if (!$this->existCustomer($customerProfile->getCustomerId())) throw new DAOException('cliente não encontrado');
	}

	/**
	 * Insere um novo perfil de cliente no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param CustomerProfile $customerProfile objeto do tipo perfil de cliente à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(CustomerProfile $customerProfile): bool
	{
		$this->validate($customerProfile, false);

		$sql = "INSERT INTO customer_profiles (idCustomer, name, assignmentLevel)
				VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getCustomerId());
		$query->setString(2, $customerProfile->getName());
		$query->setInteger(3, $customerProfile->getAssignmentLevel());

		if (($result = $query->execute())->isSuccessful())
			$customerProfile->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 * Atualiza os dados de um perfil de cliente já existente no banco de dados.
	 * @param CustomerProfile $customerProfile objeto do tipo perfil de cliente à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(CustomerProfile $customerProfile): bool
	{
		$this->validate($customerProfile, true);

		$sql = "UPDATE customer_profiles
				SET idCustomer = ?, name = ?, assignmentLevel = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getCustomerId());
		$query->setString(2, $customerProfile->getName());
		$query->setInteger(3, $customerProfile->getAssignmentLevel());
		$query->setInteger(4, $customerProfile->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui os dados de um perfil de cliente já existente no banco de dados.
	 * @param CustomerProfile $customerProfile objeto do tipo perfil de cliente à excluir.
	 * @return bool true se for atualizado ou false caso contrário.
	 * @throws DAOException se definido em um ou mais funcionários de cliente.
	 */
	public function delete(CustomerProfile $customerProfile): bool
	{
		$this->validate($customerProfile, true);

		if ($this->existOnCustomerEmployees($customerProfile))
			throw new DAOException('perfil definido em um ou mais funcionários');

		$sql = "DELETE FROM customer_profiles
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newBaseSelect(): string
	{
		return "SELECT id, idCustomer, name, assignmentLevel
				FROM customer_profiles";
	}

	/**
	 * Selecione os dados de um perfil de cliente através do seu código de identificação único.
	 * @param int $idCustomerProfile código de identificação único do perfil de cliente.
	 * @return CustomerProfile|NULL perfil de cliente com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idCustomerProfile): ?CustomerProfile
	{
		$sqlBase = $this->newBaseSelect();
		$sql = "$sqlBase
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idCustomerProfile);

		$result = $query->execute();

		return $this->parseCustomerProfile($result);
	}

	/**
	 * Seleciona os dados dos perfis de cliente através de um cliente já existente.
	 * @param Customer $customer objeto do tipo cliente à ser filtrado.
	 * @return CustomerProfiles lista com os dados dos perfis filtrados.
	 */
	public function selectByCustomer(Customer $customer): CustomerProfiles
	{
		$sqlBase = $this->newBaseSelect();
		$sql = "$sqlBase
				WHERE idCustomer = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());

		$result = $query->execute();

		return $this->parseCustomerProfiles($result);
	}

	/**
	 * Seleciona os dados dos perfis de cliente através de um cliente já existente e nível de assinatura.
	 * @param Customer $customer objeto do tipo cliente à ser filtrado.
	 * @param int $assignmentLevel nível de assinatura máximo à ser filtrado.
	 * @return CustomerProfiles lista com os dados dos perfis filtrados.
	 */
	public function selectByCustomerLevel(Customer $customer, int $assignmentLevel): CustomerProfiles
	{
		$sqlBase = $this->newBaseSelect();
		$sql = "$sqlBase
				WHERE idCustomer = ? AND assignmentLevel <= ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setInteger(2, $assignmentLevel);

		$result = $query->execute();

		return $this->parseCustomerProfiles($result);
	}

	/**
	 * Seleciona os dados de todos os perfis de cliente registrados no banco de dados sem ordenação.
	 * @return CustomerProfiles aquisição da lista de perfis de cliente atualmente registrados.
	 */
	public function selectAll(): CustomerProfiles
	{
		$sql = $this->newBaseSelect();

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseCustomerProfiles($result);
	}

	/**
	 * Verifica se um determinado código de identificação de perfil de cliente existe.
	 * @param int $idCustomerProfile código de identificação único do perfil de cliente.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function exist(int $idCustomerProfile): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_profiles
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idCustomerProfile);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado nome de perfil já foi utilizado por um cliente.
	 * @param Customer $customer objeto do tipo cliente a ser considerado na consulta.
	 * @param string $name nome de perfil do qual deseja verificar a existência.
	 * @param int $idCustomerProfile código de identificação do perfil de cliente à desconsiderar
	 * ou zero caso seja um novo perfil de cliente.
	 * @return bool true se estiver disponível ou false caso contrário.
	 */
	public function existName(Customer $customer, string $name, int $idCustomerProfile): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_profiles
				WHERE idCustomer = ? AND name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setString(2, $name);
		$query->setInteger(3, $idCustomerProfile);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação de cliente existe.
	 * @param int $idCustomer código de identificação único do cliente.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existCustomer(int $idCustomer): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idCustomer);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado perfil de cliente está em uso por um funcionário de cliente.
	 * @param CustomerProfile $customerProfile objeto do tipo perfil de cliente à verificar.
	 * @return bool true se estiver vinculado ou false caso contrário.
	 */
	public function existOnCustomerEmployees(CustomerProfile $customerProfile): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_employees
				WHERE idCustomerProfile = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de perfil de cliente.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return CustomerProfile|NULL objeto do tipo perfil de cliente com dados carregados ou NULL se não houver resultado.
	 */
	private function parseCustomerProfile(Result $result): ?CustomerProfile
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newCustomerProfile($entry);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar os objetos de perfil de cliente.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return CustomerProfiles aquisição da lista de perfis de cliente a partir da consulta.
	 */
	private function parseCustomerProfiles(Result $result): CustomerProfiles
	{
		$customerProfiles = new CustomerProfiles();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$customerProfile = $this->newCustomerProfile($entry);
			$customerProfiles->add($customerProfile);
		}

		return $customerProfiles;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo perfil de cliente e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return CustomerProfile aquisição de um objeto do tipo perfil de cliente com dados carregados.
	 */
	private function newCustomerProfile(array $entry): CustomerProfile
	{
		Functions::parseArrayJoin($entry);

		$customerProfile = new CustomerProfile();
		$customerProfile->fromArray($entry);

		if (isset($entry['idCustomer']))
			$customerProfile->getCustomer()->setId($entry['idCustomer']);

		return $customerProfile;
	}
}

