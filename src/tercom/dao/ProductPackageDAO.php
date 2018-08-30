<?php

namespace tercom\dao;

use tercom\entities\ProductPackage;
use tercom\entities\lists\ProductPackages;
use dProject\MySQL\Result;

class ProductPackageDAO extends GenericDAO
{
	public function insert(ProductPackage $productPackage): bool
	{
		$sql = "INSERT INTO product_packages (name)
				VALUES (?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $productPackage->getName());

		$result = $query->execute();

		if ($result->isSuccessful())
			$productPackage->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(ProductPackage $productPackage): bool
	{
		$sql = "UPDATE product_packages
				SET name = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $productPackage->getName());
		$query->setInteger(2, $productPackage->getID());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function dalete(ProductPackage $productPackage): bool
	{
		$sql = "DELETE FROM product_packages
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productPackage->getID());

		$result = $query->execute();

		return $result->getAffectedRows();
	}

	public function select(int $idProductPackage): ?ProductPackage
	{
		$sql = "SELECT id, name
				FROM product_packages
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductPackage);

		$result = $query->execute();

		return $this->parseProductPackage($result);
	}

	public function searchByName(string $name): ProductPackages
	{
		$sql = "SELECT id, name
				FROM product_packages
				WHERE name LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$name%");

		$result = $query->execute();

		return $this->parseProductPackages($result);
	}

	public function existName(string $name, int $idProductPackage): bool
	{
		$sql = "SELECT COUNT(*) AS qtd
				FROM product_packages
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idProductPackage);

		$result = $query->execute();
		$productPackages = $result->next();

		return intval($productPackages['qtd']) === 1;
	}

	private function parseProductPackage(Result $result): ?ProductPackage
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$productPackage = $this->newProductPackage($array);

		return $productPackage;
	}

	private function parseProductPackages(Result $result): ProductPackages
	{
		$productPackages = new ProductPackages();

		while ($result->hasNext())
		{
			$array = $result->next();
			$productPackage = $this->newProductPackage($array);
			$productPackages->add($productPackage);
		}

		return $productPackages;
	}

	private function newProductPackage(array $array): ProductPackage
	{
		$productPackage = new ProductPackage();
		$productPackage->fromArray($array);

		return $productPackage;
	}
}

