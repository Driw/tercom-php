<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\Service;
use tercom\entities\lists\Services;

/**
 * @see GenericDAO
 * @see Service
 * @author Andrew
 */
class ServiceDAO extends GenericDAO
{
	/**
	 * @param Service $service
	 */
	public function insert(Service $service): bool
	{
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
	 * @param Service $service
	 */
	public function update(Service $service): bool
	{
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
	 * @param Service $service
	 */
	public function delete(Service $service): bool
	{
		$sql = "DELETE FROM services
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $service->getID());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * @return string
	 */
	private function newSelect(): string
	{
		return "SELECT id, name, description, tags, inactive
				FROM services";
	}

	/**
	 * @param Service $idService
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
	 * @return Services
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
	 * @param string $name
	 * @return Services
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
	 * @param string $name
	 * @param int $idService
	 * @return int
	 */
	public function countByName(string $name, int $idService): int
	{
		$sql = "SELECT COUNT(*) qty
				FROM services
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idService);

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']);
	}

	/**
	 * @param Result $result
	 * @return Service|NULL
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
	 * @param Result $result
	 * @return Services
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
	 * @param array $array
	 * @return Service
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

