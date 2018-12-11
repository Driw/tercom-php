<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\Manufacturer;
use tercom\entities\lists\Manufacturers;
use tercom\exceptions\ManufacturerException;
use dProject\Primitive\StringUtil;

class ManufacturerDAO extends GenericDAO
{
	private function validate(Manufacturer $manufacturer, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($manufacturer->getId() === 0)
				throw ManufacturerException::newNotIdentified();
		} else {
			if ($manufacturer->getId() !== 0)
				throw ManufacturerException::newIdentified();
		}

		// NOT NULL
		if (StringUtil::isEmpty($manufacturer->getFantasyName())) throw ManufacturerException::newFantasyNameEmpty();

		// UNIQUE KEY
		if ($this->existFantasyName($manufacturer->getFantasyName(), $manufacturer->getId())) throw ManufacturerException::newFantasyNameUnavaiable();
	}

	public function insert(Manufacturer $manufacturer): bool
	{
		$this->validate($manufacturer, false);

		$sql = "INSERT INTO manufacturers (fantasyName)
				VALUES (?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $manufacturer->getFantasyName());

		if (($result = $query->execute())->isSuccessful())
			$manufacturer->setId($result->getInsertID());

		return $manufacturer->getId() !== 0;
	}

	public function update(Manufacturer $manufacturer): bool
	{
		$this->validate($manufacturer, true);

		$sql = "UPDATE manufacturers
				SET fantasyName = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $manufacturer->getFantasyName());
		$query->setInteger(2, $manufacturer->getId());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function dalete(Manufacturer $manufacturer): bool
	{
		$sql = "DELETE FROM manufacturers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $manufacturer->getId());

		$result = $query->execute();

		return $result->getAffectedRows() > 0;
	}

	private function newSelect(): string
	{
		return "SELECT id, fantasyName
				FROM manufacturers";
	}

	public function select(int $idManufacturer): ?Manufacturer
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idManufacturer);

		$result = $query->execute();

		return $this->parseManufacturer($result);
	}

	public function selectAll(): Manufacturers
	{
		$sql = $this->newSelect();
		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseManufacturers($result);
	}

	public function selectLikeFantasyName(string $fantasyName, int $amount): Manufacturers
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE fantasyName LIKE ?
				ORDER BY fantasyName";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$fantasyName%");

		$result = $query->execute();

		return $this->parseManufacturers($result);
	}

	public function exist(int $idManufacturer): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM manufacturers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idManufacturer);

		return $this->parseQueryExist($query);
	}

	public function existFantasyName(string $fantasyName, int $idManufacturerr): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM manufacturers
				WHERE fantasyName = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $fantasyName);
		$query->setInteger(2, $idManufacturerr);

		return $this->parseQueryExist($query);
	}

	private function parseManufacturer(Result $result): ?Manufacturer
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newManufacturer($entry);
	}

	private function parseManufacturers(Result $result): Manufacturers
	{
		$manufacturers = new Manufacturers();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$manufacturer = $this->newManufacturer($entry);
			$manufacturers->add($manufacturer);
		}

		return $manufacturers;
	}

	private function newManufacturer(array $entry): Manufacturer
	{
		$manufacturer = new Manufacturer();
		$manufacturer->fromArray($entry);

		return $manufacturer;
	}
}

