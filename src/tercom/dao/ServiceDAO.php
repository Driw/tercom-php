<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\dao\exceptions\DAOException;
use tercom\entities\Service;
use tercom\entities\lists\Services;

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
	public const ALL_COLUMNS = ['name', 'description', 'tags', 'inactive'];

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
				throw new DAOException('serviço não identificado');
		} else {
			if ($service->getId() !== 0)
				throw new DAOException('serviço já identificado');
		}

		// NOT NULL
		if (StringUtil::isEmpty($service->getName())) throw new DAOException('nome não informado');
		if (StringUtil::isEmpty($service->getDescription())) throw new DAOException('descrição não informada');

		// UNIQUE KEYS
		if ($this->existName($service->getName(), $service->getId())) throw new DAOException('nome já utilizado');
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

		$query = $this->mysql->createQuery($sql);
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

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $service->getName());
		$query->setString(2, $service->getDescription());
		$query->setString(3, $service->getTags()->getString());
		$query->setBoolean(4, $service->isInactive());
		$query->setInteger(5, $service->getID());

		return ($query->execute())->isSuccessful();
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
	 * Selecione os dados de um serviço através do seu código de identificação único.
	 * @param int $idService código de identificação único do serviço.
	 * @return Service|NULL serviço com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idService): Service
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idService);

		$result = $query->execute();

		return $this->parseService($result);
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

		$query = $this->mysql->createQuery($sql);
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

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, "%$name%");

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

