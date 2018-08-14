<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use dProject\MySQL\MySQLException;
use tercom\dao\ProductSubGroupDAO;
use tercom\entities\ProductSubGroup;
use tercom\entities\lists\ProductCategories;

class ProductSubGroupControl extends GenericControl
{
	/**
	 * @var ProductSubGroupDAO
	 */
	private $productSubGroupDAO;
	/**
	 * @var ProductGroupControl
	 */
	private $productGroupControl;

	public function __construct(MySQL $mysql)
	{
		$this->productSubGroupDAO = new ProductSubGroupDAO($mysql);
		$this->productGroupControl = new ProductGroupControl($mysql);
	}

	private function validate(ProductSubGroup $productSubGroup, bool $validateID)
	{
		if ($validateID) {
			if ($productSubGroup->getID() === 0)
				throw new ControlException('subgrupo não identificado');
		} else {
			if ($productSubGroup->getID() !== 0)
				throw new ControlException('subgrupo já identificado');
		}

		if (empty($productSubGroup->getName()))
			throw new ControlException('nome do subgrupo não definido');

		if ($productSubGroup->getProductGroupID() === 0)
			throw new ControlException('subgrupo com grupo não identificada');
	}

	public function add(ProductSubGroup $productSubGroup): bool
	{
		$this->validate($productSubGroup, false);

		if ($this->getByName($productSubGroup->getName()) !== null)
			throw new ControlException('subgrupo já registrado');

		if (!$this->productGroupControl->has($productSubGroup->getProductGroupID()))
			throw new ControlException('grupo não encontrada');

		try {
			return $this->productSubGroupDAO->insert($productSubGroup);
		} catch (MySQLException $e) {
			switch ($e->getCode())
			{
				case self::ER_DUP_ENTRY:
					throw new ControlException('subgrupo já registrado');
				case self::ER_NO_REFERENCED_ROW_2:
					throw new ControlException('grupo não encontrado');
			}
		}
	}

	public function set(ProductSubGroup $productSubGroup): bool
	{
		$this->validate($productSubGroup, true);
		$currentProduct = $this->getByName($productSubGroup->getName());

		if ($currentProduct !== null && $currentProduct->getID() !== $productSubGroup->getID())
			throw new ControlException('subgrupo já registrado');

		if (!$this->productGroupControl->has($productSubGroup->getProductGroupID()))
			throw new ControlException('grupo não encontrada');

		try {
			return $this->productSubGroupDAO->update($productSubGroup);
		} catch (MySQLException $e) {
			switch ($e->getCode())
			{
				case self::ER_DUP_ENTRY:
					throw new ControlException('subgrupo já registrado');
				case self::ER_NO_REFERENCED_ROW_2:
					throw new ControlException('grupo não encontrada');
			}
		}
	}

	public function remove(ProductSubGroup $productSubGroup): bool
	{
		$this->validate($productSubGroup, true);

		return $this->productSubGroupDAO->delete($productSubGroup);
	}

	public function has(int $idProductSubGroup): bool
	{
		return $this->get($idProductSubGroup) !== null;
	}

	public function get(int $idProductSubGroup): ?ProductSubGroup
	{
		if ($idProductSubGroup < 1)
			throw new ControlException('identificação do subgrupo inválida');

		return $this->productSubGroupDAO->select($idProductSubGroup);
	}

	public function getByName(string $group): ?ProductSubGroup
	{
		return $this->productSubGroupDAO->selectByName($group);
	}

	public function getByGroup(int $idProductGroup): ProductCategories
	{
		return $this->productSubGroupDAO->selectByGroup($idProductGroup);
	}

	public function search(string $group): ProductCategories
	{
		return $this->productSubGroupDAO->searchByName($group);
	}
}

