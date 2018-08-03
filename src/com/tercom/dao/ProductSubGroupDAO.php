<?php

namespace tercom\dao;

use tercom\entities\ProductSubGroup;
use tercom\entities\lists\ProductCategories;
use dProject\MySQL\Result;

class ProductSubGroupDAO extends GenericDAO
{
	public function insert(ProductSubGroup $productSubGroup): bool
	{
		$sql = "INSERT INTO product_subgroups (idProductGroup, name)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productSubGroup->getProductGroupID());
		$query->setString(2, $productSubGroup->getName());

		$result = $query->execute();

		if ($result->isSuccessful())
			$productSubGroup->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(ProductSubGroup $productSubGroup): bool
	{
		$sql = "UPDATE product_subgroups
				SET idProductGroup = ?, name = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productSubGroup->getProductGroupID());
		$query->setString(2, $productSubGroup->getName());
		$query->setInteger(3, $productSubGroup->getID());

		$result = $query->execute();

		return $result->getAffectedRows() === 1;
	}

	public function delete(ProductSubGroup $productSubGroup): bool
	{
		$sql = "DELETE FROM product_subgroups
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productSubGroup->getID());

		$result = $query->execute();

		return $result->getAffectedRows() === 1;
	}

	public function select($idProductSubGroup): ?ProductSubGroup
	{
		$sql = "SELECT id, idProductGroup, name
				FROM product_subgroups
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductSubGroup);

		$result = $query->execute();

		return $this->parseProductSubGroup($result);
	}

	public function selectByName(string $subgroup): ?ProductSubGroup
	{
		$sql = "SELECT id, idProductGroup, name
				FROM product_subgroups
				WHERE name COLLATE UTF8_GENERAL_CI = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $subgroup);

		$result = $query->execute();

		return $this->parseProductSubGroup($result);
	}

	public function selectByGroup(int $idProductGroup): ProductCategories
	{
		$sql = "SELECT id, idProductGroup, name
				FROM product_subgroups
				WHERE idProductGroup = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductGroup);

		$result = $query->execute();

		return $this->parseProductSubGroups($result);
	}

	public function searchByName(string $subgroup): ProductCategories
	{
		$sql = "SELECT id, idProductGroup, name
				FROM product_subgroups
				WHERE name COLLATE UTF8_GENERAL_CI LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$subgroup%");

		$result = $query->execute();

		return $this->parseProductSubGroups($result);
	}

	private function parseProductSubGroup(Result $result): ?ProductSubGroup
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$productSubGroup = $this->newProductSubGroup($array);

		return $productSubGroup;
	}

	private function parseProductSubGroups(Result $result): ProductCategories
	{
		$productFamilies = new ProductCategories();

		while ($result->hasNext())
		{
			$array = $result->next();
			$productSubGroup = $this->newProductSubGroup($array);
			$productFamilies->add($productSubGroup);
		}

		return $productFamilies;
	}

	private function newProductSubGroup(array $array): ProductSubGroup
	{
		$idProductGroup = intval($array['idProductGroup']); unset($array['idProductGroup']);

		$productSubGroup = new ProductSubGroup();
		$productSubGroup->fromArray($array);
		$productSubGroup->setProductGroupID($idProductGroup);

		return $productSubGroup;
	}
}
