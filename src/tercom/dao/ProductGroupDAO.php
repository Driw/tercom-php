<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\ProductGroup;
use tercom\entities\lists\ProductCategories;

class ProductGroupDAO extends GenericDAO
{
	public function insert(ProductGroup $productGroup): bool
	{
		$sql = "INSERT INTO product_groups (idProductFamily, name)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productGroup->getProductFamilyID());
		$query->setString(2, $productGroup->getName());

		$result = $query->execute();

		if ($result->isSuccessful())
			$productGroup->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(ProductGroup $productGroup): bool
	{
		$sql = "UPDATE product_groups
				SET idProductFamily = ?, name = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productGroup->getProductFamilyID());
		$query->setString(2, $productGroup->getName());
		$query->setInteger(3, $productGroup->getID());

		$result = $query->execute();

		return $result->getAffectedRows() === 1;
	}

	public function delete(ProductGroup $productGroup): bool
	{
		$sql = "DELETE FROM product_groups
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productGroup->getID());

		$result = $query->execute();

		return $result->getAffectedRows() === 1;
	}

	public function select($idProductGroup): ?ProductGroup
	{
		$sql = "SELECT id, idProductFamily, name
				FROM product_groups
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductGroup);

		$result = $query->execute();

		return $this->parseProductGroup($result);
	}

	public function selectByName(string $group): ?ProductGroup
	{
		$sql = "SELECT id, idProductFamily, name
				FROM product_groups
				WHERE name COLLATE UTF8_GENERAL_CI = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $group);

		$result = $query->execute();

		return $this->parseProductGroup($result);
	}

	public function selectByFamily(int $idProductFamily): ProductCategories
	{
		$sql = "SELECT id, idProductFamily, name
				FROM product_groups
				WHERE idProductFamily = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductFamily);

		$result = $query->execute();

		return $this->parseProductGroups($result);
	}

	public function searchByName(string $group): ProductCategories
	{
		$sql = "SELECT id, idProductFamily, name
				FROM product_groups
				WHERE name COLLATE UTF8_GENERAL_CI LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$group%");

		$result = $query->execute();

		return $this->parseProductGroups($result);
	}

	private function parseProductGroup(Result $result): ?ProductGroup
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$productGroup = $this->newProductGroup($array);

		return $productGroup;
	}

	private function parseProductGroups(Result $result): ProductCategories
	{
		$productFamilies = new ProductCategories();

		while ($result->hasNext())
		{
			$array = $result->next();
			$productGroup = $this->newProductGroup($array);
			$productFamilies->add($productGroup);
		}

		return $productFamilies;
	}

	private function newProductGroup(array $array): ProductGroup
	{
		$idProductSubGroup = intval($array['idProductFamily']); unset($array['idProductFamily']);

		$productGroup = new ProductGroup();
		$productGroup->fromArray($array);
		$productGroup->setProductFamilyID($idProductSubGroup);

		return $productGroup;
	}
}
