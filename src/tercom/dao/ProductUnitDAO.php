<?php

namespace tercom\dao;

use tercom\entities\ProductUnit;
use tercom\entities\lists\ProductUnits;
use dProject\MySQL\Result;

class ProductUnitDAO extends GenericDAO
{
	public function insert(ProductUnit $productUnit): bool
	{
		$sql = "INSERT INTO product_units (name, shortName)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $productUnit->getName());
		$query->setString(2, $productUnit->getShortName());

		$result = $query->execute();

		if ($result->isSuccessful())
			$productUnit->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(ProductUnit $productUnit): bool
	{
		$sql = "UPDATE product_units
				SET name = ?, shortName = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $productUnit->getName());
		$query->setString(2, $productUnit->getShortName());
		$query->setInteger(3, $productUnit->getID());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function dalete(ProductUnit $productUnit): bool
	{
		$sql = "DELETE FROM product_units
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productUnit->getID());

		$result = $query->execute();

		return $result->getAffectedRows();
	}

	public function select(int $idProductUnit): ?ProductUnit
	{
		$sql = "SELECT id, name, shortName
				FROM product_units
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductUnit);

		$result = $query->execute();

		return $this->parseProductUnit($result);
	}

	public function selectAll(): ProductUnits
	{
		$sql = "SELECT id, name, shortName
				FROM product_units";

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseProductUnits($result);
	}

	public function selectByName(string $name): ProductUnits
	{
		$sql = "SELECT id, name, shortName
				FROM product_units
				WHERE name LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$name%");

		$result = $query->execute();

		return $this->parseProductUnits($result);
	}

	public function exist(int $idProductUnit): bool
	{
		$sql = "SELECT COUNT(*) AS qtd
				FROM product_units
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductUnit);

		$result = $query->execute();
		$productUnit = $result->next();

		return intval($productUnit['qtd']) === 1;
	}

	public function existName(string $name, int $idProductUnit): bool
	{
		$sql = "SELECT COUNT(*) AS qtd
				FROM product_units
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idProductUnit);

		$result = $query->execute();
		$productUnit = $result->next();

		return intval($productUnit['qtd']) === 1;
	}

	public function existShortName(string $shorName, int $idProductUnit): bool
	{
		$sql = "SELECT COUNT(*) AS qtd
				FROM product_units
				WHERE shortName = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $shorName);
		$query->setInteger(2, $idProductUnit);

		$result = $query->execute();
		$productUnit = $result->next();

		return intval($productUnit['qtd']) === 1;
	}

	private function parseProductUnit(Result $result): ?ProductUnit
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$productUnit = $this->newProductUnit($array);

		return $productUnit;
	}

	private function parseProductUnits(Result $result): ProductUnits
	{
		$productUnits = new ProductUnits();

		while ($result->hasNext())
		{
			$array = $result->next();
			$productUnit = $this->newProductUnit($array);
			$productUnits->add($productUnit);
		}

		return $productUnits;
	}

	private function newProductUnit(array $array): ProductUnit
	{
		$productUnit = new ProductUnit();
		$productUnit->fromArray($array);

		return $productUnit;
	}
}

