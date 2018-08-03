<?php

namespace tercom\dao;

use tercom\entities\ProductFamily;
use tercom\entities\lists\ProductCategories;
use dProject\MySQL\Result;

class ProductFamilyDAO extends GenericDAO
{
	public function insert(ProductFamily $productFamily): bool
	{
		$sql = "INSERT INTO product_families (name)
				VALUES (?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $productFamily->getName());

		$result = $query->execute();

		if ($result->isSuccessful())
			$productFamily->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(ProductFamily $productFamily): bool
	{
		$sql = "UPDATE product_families
				SET name = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $productFamily->getName());
		$query->setInteger(2, $productFamily->getID());

		$result = $query->execute();

		return $result->getAffectedRows() === 1;
	}

	public function delete(ProductFamily $productFamily): bool
	{
		$sql = "DELETE FROM product_families
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productFamily->getID());

		$result = $query->execute();

		return $result->getAffectedRows() === 1;
	}

	public function select($idProductFamily): ?ProductFamily
	{
		$sql = "SELECT id, name
				FROM product_families
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductFamily);

		$result = $query->execute();

		return $this->parseProductFamily($result);
	}

	public function selectByName(string $family): ?ProductFamily
	{
		$sql = "SELECT id, name
				FROM product_families
				WHERE name COLLATE UTF8_GENERAL_CI = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $family);

		$result = $query->execute();

		return $this->parseProductFamily($result);
	}

	public function searchByName(string $family): ProductCategories
	{
		$sql = "SELECT id, name
				FROM product_families
				WHERE name COLLATE UTF8_GENERAL_CI LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$family%");

		$result = $query->execute();

		return $this->parseProductFamilies($result);
	}

	private function parseProductFamily(Result $result): ?ProductFamily
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$productFamily = $this->newProductFamily($array);

		return $productFamily;
	}

	private function parseProductFamilies(Result $result): ProductCategories
	{
		$productFamilies = new ProductCategories();

		while ($result->hasNext())
		{
			$array = $result->next();
			$productFamily = $this->newProductFamily($array);
			$productFamilies->add($productFamily);
		}

		return $productFamilies;
	}

	private function newProductFamily(array $array): ProductFamily
	{
		$productFamily = new ProductFamily();
		$productFamily->fromArray($array);

		return $productFamily;
	}
}
