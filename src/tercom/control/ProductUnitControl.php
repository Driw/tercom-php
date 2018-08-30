<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use tercom\dao\ProductUnitDAO;
use tercom\entities\ProductUnit;
use tercom\entities\lists\ProductUnits;

class ProductUnitControl extends GenericControl
{
	/**
	 * @var ProductUnitDAO
	 */
	private $productUnitDAO;

	/**
	 * @param MySQL $mysql
	 */

	public function __construct(MySQL $mysql)
	{
		$this->productUnitDAO = new ProductUnitDAO($mysql);
	}

	private function validate(ProductUnit $productUnit, bool $validateID)
	{
		if ($validateID) {
			if ($productUnit->getID() === 0)
				throw new ControlException('unidade de produto não identificado');
		} else {
			if ($productUnit->getID() !== 0)
				throw new ControlException('unidade de produto já identificado');
		}
	}

	public function add(ProductUnit $productUnit): bool
	{
		if (!$this->hasAvaiableName($productUnit->getName(), $productUnit->getID()))
			throw new ControlException('unidade de produto já definido');

		$this->validate($productUnit, false);

		return $this->productUnitDAO->insert($productUnit);
	}

	public function set(ProductUnit $productUnit): bool
	{
		if (!$this->hasAvaiableName($productUnit))
			throw new ControlException('unidade de produto já definido');

		$this->validate($productUnit, true);

		return $this->productUnitDAO->update($productUnit);
	}

	public function remove(ProductUnit $productUnit): bool
	{
		$this->validate($productUnit, true);

		return $this->productUnitDAO->dalete($productUnit);
	}

	public function get(int $idProductUnit): ?ProductUnit
	{
		return $this->productUnitDAO->select($idProductUnit);
	}

	public function filterByName(string $name): ProductUnits
	{
		return $this->productUnitDAO->selectByName($name);
	}

	public function has(int $idProductUnit): bool
	{
		return $this->productUnitDAO->exist($idProductUnit);
	}

	public function hasAvaiableName(string $name, int $idProductUnit = 0): bool
	{
		return !$this->productUnitDAO->existName($name, $idProductUnit);
	}
}

