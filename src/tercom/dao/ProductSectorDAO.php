<?php

namespace tercom\dao;

use tercom\entities\ProductSector;
use tercom\entities\lists\ProductCategories;
use dProject\MySQL\Result;

class ProductSectorDAO extends GenericDAO
{
	public function insert(ProductSector $productSector): bool
	{
		$sql = "INSERT INTO product_sectores (idProductSubGroup, name)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productSector->getProductSubGroupID());
		$query->setString(2, $productSector->getName());

		$result = $query->execute();

		if ($result->isSuccessful())
			$productSector->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(ProductSector $productSector): bool
	{
		$sql = "UPDATE product_sectores
				SET idProductSubGroup = ?, name = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productSector->getProductSubGroupID());
		$query->setString(2, $productSector->getName());
		$query->setInteger(3, $productSector->getID());

		$result = $query->execute();

		return $result->getAffectedRows() === 1;
	}

	public function delete(ProductSector $productSector): bool
	{
		$sql = "DELETE FROM product_sectores
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productSector->getID());

		$result = $query->execute();

		return $result->getAffectedRows() == 1;
	}

	public function select($idProductSector): ?ProductSector
	{
		$sql = "SELECT id, idProductSubGroup, name
				FROM product_sectores
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductSector);

		$result = $query->execute();

		return $this->parseProductSector($result);
	}

	public function selectByName(string $sector): ?ProductSector
	{
		$sql = "SELECT id, idProductSubGroup, name
				FROM product_sectores
				WHERE name COLLATE UTF8_GENERAL_CI = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $sector);

		$result = $query->execute();

		return $this->parseProductSector($result);
	}

	public function selectBySubGroup(int $idProductSubGroup): ProductCategories
	{
		$sql = "SELECT id, idProductSubGroup, name
				FROM product_sectores
				WHERE idProductSubGroup = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductSubGroup);

		$result = $query->execute();

		return $this->parseProductSectores($result);
	}

	public function searchByName(string $sector): ProductCategories
	{
		$sql = "SELECT id, idProductSubGroup, name
				FROM product_sectores
				WHERE name COLLATE UTF8_GENERAL_CI LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$sector%");

		$result = $query->execute();

		return $this->parseProductSectores($result);
	}

	private function parseProductSector(Result $result): ?ProductSector
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$productSector = $this->newProductSector($array);

		return $productSector;
	}

	private function parseProductSectores(Result $result): ProductCategories
	{
		$productFamilies = new ProductCategories();

		while ($result->hasNext())
		{
			$array = $result->next();
			$productSector = $this->newProductSector($array);
			$productFamilies->add($productSector);
		}

		return $productFamilies;
	}

	private function newProductSector(array $array): ProductSector
	{
		$idProductSubGroup = intval($array['idProductSubGroup']); unset($array['idProductSubGroup']);

		$productSector = new ProductSector();
		$productSector->fromArray($array);
		$productSector->setProductSubGroupID($idProductSubGroup);

		return $productSector;
	}
}
