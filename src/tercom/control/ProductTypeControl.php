<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use tercom\dao\ProductTypeDAO;
use tercom\entities\ProductType;
use tercom\entities\lists\ProductTypes;

class ProductTypeControl extends GenericControl
{
	/**
	 * @var ProductTypeDAO
	 */
	private $productTypeDAO;

	/**
	 * @param MySQL $mysql
	 */

	public function __construct(MySQL $mysql)
	{
		$this->productTypeDAO = new ProductTypeDAO($mysql);
	}

	private function validate(ProductType $productType, bool $validateID)
	{
		if ($validateID) {
			if ($productType->getID() === 0)
				throw new ControlException('tipo de produto não identificado');
		} else {
			if ($productType->getID() !== 0)
				throw new ControlException('tipo de produto já identificado');
		}
	}

	public function add(ProductType $productType): bool
	{
		if (!$this->hasAvaiableName($productType->getName(), $productType->getID()))
			throw new ControlException('tipo de produto já definido');

		$this->validate($productType, false);

		return $this->productTypeDAO->insert($productType);
	}

	public function set(ProductType $productType): bool
	{
		if (!$this->hasAvaiableName($productType))
			throw new ControlException('tipo de produto já definido');

		$this->validate($productType, true);

		return $this->productTypeDAO->update($productType);
	}

	public function remove(ProductType $productType): bool
	{
		$this->validate($productType, true);

		return $this->productTypeDAO->dalete($productType);
	}

	public function get(int $idProductType): ?ProductType
	{
		return $this->productTypeDAO->select($idProductType);
	}

	public function getAll(): ProductTypes
	{
		return $this->productTypeDAO->selectAll();
	}

	public function filterByName(string $name): ProductTypes
	{
		return $this->productTypeDAO->selectByName($name);
	}

	public function has(int $idProductType): bool
	{
		return $this->productTypeDAO->existID($idProductType);
	}

	public function hasAvaiableName(string $name, int $idProductType = 0): bool
	{
		return !$this->productTypeDAO->existName($name, $idProductType);
	}
}

