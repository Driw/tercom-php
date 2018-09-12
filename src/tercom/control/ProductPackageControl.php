<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use tercom\dao\ProductPackageDAO;
use tercom\entities\ProductPackage;
use tercom\entities\lists\ProductPackages;

class ProductPackageControl extends GenericControl
{
	/**
	 * @var ProductPackageDAO
	 */
	private $productPackageDAO;

	/**
	 * @param MySQL $mysql
	 */

	public function __construct(MySQL $mysql)
	{
		$this->productPackageDAO = new ProductPackageDAO($mysql);
	}

	private function validate(ProductPackage $productPackage, bool $validateID)
	{
		if ($validateID) {
			if ($productPackage->getID() === 0)
				throw new ControlException('embalagem de produto não identificado');
		} else {
			if ($productPackage->getID() !== 0)
				throw new ControlException('embalagem de produto já identificado');
		}
	}

	public function add(ProductPackage $productPackage): bool
	{
		if (!$this->hasAvaiableName($productPackage->getName(), $productPackage->getID()))
			throw new ControlException('embalagem de produto já definido');

		$this->validate($productPackage, false);

		return $this->productPackageDAO->insert($productPackage);
	}

	public function set(ProductPackage $productPackage): bool
	{
		if (!$this->hasAvaiableName($productPackage))
			throw new ControlException('embalagem de produto já definido');

		$this->validate($productPackage, true);

		return $this->productPackageDAO->update($productPackage);
	}

	public function remove(ProductPackage $productPackage): bool
	{
		$this->validate($productPackage, true);

		return $this->productPackageDAO->dalete($productPackage);
	}

	public function get(int $idProductPackage): ?ProductPackage
	{
		return $this->productPackageDAO->select($idProductPackage);
	}

	public function filterByName(string $name): ProductPackages
	{
		return $this->productPackageDAO->searchByName($name);
	}

	public function has(int $idProductPackage): bool
	{
		return $this->productPackageDAO->existID($idProductPackage);
	}

	public function hasAvaiableName(string $name, int $idProductPackage = 0): bool
	{
		return !$this->productPackageDAO->existName($name, $idProductPackage);
	}
}

