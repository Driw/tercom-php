<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\entities\Customer;
use tercom\entities\Service;
use tercom\entities\lists\Services;
use tercom\exceptions\ServiceException;

/**
 * DAO para Serviço
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos serviços, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar e selecionar; <b>serviços não podem ser excluídos</b>.
 *
 * Serviços devem possuir um nome único e descrição, tags são opcionais.
 * A inatividade de um serviço já é definida por padrão como desabilitada.
 *
 * @see GenericDAO
 * @see Service
 * @see Services
 *
 * @author Andrew
 */
class ServiceDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de serviços.
	 */
	public const ALL_COLUMNS = ['id', 'name', 'description', 'tags', 'inactive'];

	/**
	 * Procedimento interno para validação dos dados de um serviço ao inserir e/ou atualizar.
	 * Serviços não podem ter nome e descrição não informadas.
	 * @param Service $service objeto do tipo fornecedor à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws DAOException caso algum dos dados do serviço não estejam de acordo.
	 */
	private function validate(Service $service, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($service->getId() === 0)
				throw ServiceException::newNotIdentified();
		} else {
			if ($service->getId() !== 0)
				throw ServiceException::newIdentified();
		}

		// NOT NULL
		if (StringUtil::isEmpty($service->getName())) throw ServiceException::newEmptyName();
		if (StringUtil::isEmpty($service->getDescription())) throw ServiceException::newEmptyDescription();

		// UNIQUE KEYS
		if ($this->existName($service->getName(), $service->getId())) throw ServiceException::newNameExist();
	}

	/**
	 * Verifica se um código de identificação de serviço personalizado por cliente já exite.
	 * @param Customer $customer objeto do tipo cliente à verificar.
	 * @param Service $service objeto do tipo serviço à verificar.
	 */
	public function validateCustomId(Customer $customer, Service $service): void
	{
		$sql = "SELECT COUNT(*) qty
				FROM service_customer
				WHERE idCustomer = ? AND idCustom = ? AND idService <> ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setString(2, $service->getIdServiceCustomer());
		$query->setInteger(3, $service->getId());

		if ($this->parseQueryExist($query))
			throw ServiceException::newCustomerIdExist();
	}

	/**
	 * Insere um novo serviço no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param Service $service objeto do tipo serviço à adicionar.
	 * @return bool true se conseguir inserir ou false caso contrário.
	 */
	public function insert(Service $service): bool
	{
		$this->validate($service, false);

		$sql = "INSERT INTO services (name, description, tags, inactive)
				VALUES (?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $service->getName());
		$query->setString(2, $service->getDescription());
		$query->setString(3, $service->getTags()->getString());
		$query->setBoolean(4, $service->isInactive());

		if (($result = $query->execute())->isSuccessful())
			$service->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 * Atualiza os dados de um serviço já existente no banco de dados.
	 * @param Service $service objeto do tipo serviço à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(Service $service): bool
	{
		$this->validate($service, true);

		$sql = "UPDATE services
				SET name = ?, description = ?, tags = ?, inactive = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $service->getName());
		$query->setString(2, $service->getDescription());
		$query->setString(3, $service->getTags()->getString());
		$query->setBoolean(4, $service->isInactive());
		$query->setInteger(5, $service->getID());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Substitui o código personalizado de um cliente para um serviço específico.
	 * @param Customer $customer cliente à vincular a identificação do serviço.
	 * @param Service $service objeto do tipo serviço à atualizar.
	 * @return bool true se substituir ou false caso contrário.
	 */
	public function replaceCustomerId(Customer $customer, Service $service): bool
	{
		$this->validate($service, true);
		$this->validateCustomId($customer, $service);

		$sql = "REPLACE service_customer (idService, idCustomer, idCustom) VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $service->getId());
		$query->setInteger(2, $customer->getId());
		$query->setString(3, $service->getIdServiceCustomer());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		return "SELECT id, name, description, tags, inactive
				FROM services";
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelectCustomer(): string
	{
		return "SELECT id, name, description, tags, inactive, service_customer.idCustom idServiceCustomer
				FROM services";
	}

	/**
	 * Selecione os dados de um serviço através do seu código de identificação único.
	 * @param int $idService código de identificação único do serviço.
	 * @return Service|NULL serviço com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idService): ?Service
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idService);

		$result = $query->execute();

		return $this->parseService($result);
	}

	/**
	 * Seleciona os dados de todos os serviços registrados no banco de dados sem ordenação.
	 * Filtra os serviços para que somente os com código personalizado do cliente.
	 * @return Services aquisição da lista de serviços atualmente registrados.
	 */
	public function selectAllWithCustomer(Customer $customer): Services
	{
		$sqlSELECT = $this->newSelectCustomer();
		$sql = "$sqlSELECT
				INNER JOIN service_customer ON service_customer.idService = services.id
				WHERE service_customer.idCustomer = ?
				ORDER BY services.name";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());

		$result = $query->execute();

		return $this->parseServices($result);
	}

	/**
	 * Seleciona os dados de todos os serviços registrados no banco de dados sem ordenação.
	 * @return Services aquisição da lista de serviços atualmente registrados.
	 */
	public function selectAll(): Services
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				ORDER BY name";

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseServices($result);
	}

	/**
	 * Seleciona os dados dos serviços no banco de dados filtrados pelo nome.
	 * @param string $name nome parcial ou completo para filtro.
	 * @return Services aquisição da lista de fornecedores conforme filtro.
	 */
	public function selectByName(string $name): Services
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE name LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$name%");

		$result = $query->execute();

		return $this->parseServices($result);
	}

	/**
	 * Seleciona os dados dos serviços no banco de dados filtrados pelo nome.
	 * @param string $idServiceCustomer cliente serviço ID à filtrar.
	 * @param Customer $customer objeto do tipo cliente à filtrar.
	 * @return Services aquisição da lista de serviços conforme filtro.
	 */
	public function selectLikeIdCustom(string $idServiceCustomer, Customer $customer): Services
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				INNER JOIN service_customer ON service_customer.idCustomer = ? AND service_customer.idService = services.id
				WHERE service_customer.idCustom LIKE ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setString(2, "%$idServiceCustomer%");

		$result = $query->execute();

		return $this->parseServices($result);
	}

	/**
	 * Verifica se um determinado nome de serviço está disponível para um serviço.
	 * @param string $name nome do serviço à verificar.
	 * @param int $idService código de identificação do serviço à desconsiderar
	 * ou zero caso seja um novo serviço.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existName(string $name, int $idService): int
	{
		$sql = "SELECT COUNT(*) qty
				FROM services
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idService);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um cliente serviço ID já está sendo usado por outro serviço.
	 * @param Service $service objeto do tipo serviço à verificar.
	 * @param Customer $customer objeto do tipo cliente à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existIdServiceCustomer(Service $service, Customer $customer): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM service_customer
				WHERE idCustomer = ? AND idCustom = ? AND idService <> ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setString(2, $service->getIdServiceCustomer());
		$query->setInteger(3, $service->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de serviço.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Service|NULL objeto do tipo serviço com dados carregados ou NULL se não houver resultado.
	 */
	private function parseService(Result $result): ?Service
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$service = $this->newService($array);

		return $service;
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar os objetos de serviço.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Services aquisição da lista de serviços a partir da consulta.
	 */
	private function parseServices(Result $result): Services
	{
		$services = new Services();

		while ($result->hasNext())
		{
			$entry = $result->next();
			$services->add($this->newService($entry));
		}

		return $services;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo serviço e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return Service aquisição de um objeto do tipo serviço com dados carregados.
	 */
	private function newService(array $array): Service
	{
		$tags = $array['tags']; unset($array['tags']);

		$service = new Service();
		$service->fromArray($array);
		$service->getTags()->parseString($tags);

		return $service;
	}
}

