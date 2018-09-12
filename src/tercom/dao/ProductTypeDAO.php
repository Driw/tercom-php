<?php

namespace tercom\dao;

use tercom\entities\ProductType;
use tercom\entities\lists\ProductTypes;
use dProject\MySQL\Result;

class ProductTypeDAO extends GenericDAO
{
	public function insert(ProductType $productType): bool
	{
		$sql = "INSERT INTO product_types (name)
				VALUES (?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $productType->getName());

		$result = $query->execute();

		if ($result->isSuccessful())
			$productType->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(ProductType $productType): bool
	{
		$sql = "UPDATE product_types
				SET name = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $productType->getName());
		$query->setInteger(2, $productType->getID());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function dalete(ProductType $productType): bool
	{
		$sql = "DELETE FROM product_types
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productType->getID());

		$result = $query->execute();

		return $result->getAffectedRows();
	}

	public function select(int $idProductType): ?ProductType
	{
		$sql = "SELECT id, name
				FROM product_types
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductType);

		$result = $query->execute();

		return $this->parseProductType($result);
	}

	public function selectByName(string $name): ProductTypes
	{
		$sql = "SELECT id, name
				FROM product_types
				WHERE name LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$name%");

		$result = $query->execute();

		return $this->parseProductTypes($result);
	}

	public function existID(int $idProductType): bool
	{
		$sql = "SELECT COUNT(*) AS qtd
				FROM product_types
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductType);

		$result = $query->execute();
		$productType = $result->next();

		return intval($productType['qtd']) === 1;
	}

	public function existName(string $name, int $idProductType = 0): bool
	{
		$sql = "SELECT COUNT(*) AS qtd
				FROM product_types
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idProductType);

		$result = $query->execute();
		$productType = $result->next();

		return intval($productType['qtd']) === 1;
	}

	private function parseProductType(Result $result): ?ProductType
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$productType = $this->newProductType($array);

		return $productType;
	}

	private function parseProductTypes(Result $result): ProductTypes
	{
		$productTypes = new ProductTypes();

		while ($result->hasNext())
		{
			$array = $result->next();
			$productType = $this->newProductType($array);
			$productTypes->add($productType);
		}

		return $productTypes;
	}

	private function newProductType(array $array): ProductType
	{
		$productType = new ProductType();
		$productType->fromArray($array);

		return $productType;
	}
}

