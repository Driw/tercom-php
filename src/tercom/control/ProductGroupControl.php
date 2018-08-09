<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use dProject\MySQL\MySQLException;
use tercom\dao\ProductGroupDAO;
use tercom\entities\ProductGroup;
use tercom\entities\lists\ProductCategories;

class ProductGroupControl extends GenericControl
{
	/**
	 * @var ProductGroupDAO
	 */
	private $productGroupDAO;
	/**
	 * @var ProductFamilyControl
	 */
	private $productFamilyControl;

	public function __construct(MySQL $mysql)
	{
		$this->productGroupDAO = new ProductGroupDAO($mysql);
		$this->productFamilyControl = new ProductFamilyControl($mysql);
	}

	private function validate(ProductGroup $productGroup, bool $validateID)
	{
		if ($validateID) {
			if ($productGroup->getID() === 0)
				throw new ControlException('grupo não identificado');
		} else {
			if ($productGroup->getID() !== 0)
				throw new ControlException('grupo já identificado');
		}

		if (empty($productGroup->getName()))
			throw new ControlException('nome do grupo não definido');

		if ($productGroup->getProductFamilyID() === 0)
			throw new ControlException('grupo com família não identificada');
	}

	public function add(ProductGroup $productGroup): bool
	{
		$this->validate($productGroup, false);

		if ($this->getByName($productGroup->getName()) !== null)
			throw new ControlException('grupo já registrado');

		if (!$this->productFamilyControl->has($productGroup->getProductFamilyID()))
			throw new ControlException('família não encontrada');

		try {
			return $this->productGroupDAO->insert($productGroup);
		} catch (MySQLException $e) {
			switch ($e->getCode())
			{
				case self::ER_DUP_ENTRY:
					throw new ControlException('grupo já registrado');
				case self::ER_NO_REFERENCED_ROW_2:
					throw new ControlException('família não encontrada');
			}
		}
	}

	public function set(ProductGroup $productGroup): bool
	{
		$this->validate($productGroup, true);
		$currentProduct = $this->getByName($productGroup->getName());

		if ($currentProduct !== null && $currentProduct->getID() !== $productGroup->getID())
			throw new ControlException('grupo já registrado');

		if (!$this->productFamilyControl->has($productGroup->getProductFamilyID()))
			throw new ControlException('família não encontrada');

		try {
			return $this->productGroupDAO->update($productGroup);
		} catch (MySQLException $e) {
			switch ($e->getCode())
			{
				case self::ER_DUP_ENTRY:
					throw new ControlException('grupo já registrado');
				case self::ER_NO_REFERENCED_ROW_2:
					throw new ControlException('família não encontrada');
			}
		}
	}

	public function remove(ProductGroup $productGroup): bool
	{
		$this->validate($productGroup, true);

		return $this->productGroupDAO->delete($productGroup);
	}

	public function has(int $idProductGroup): bool
	{
		return $this->get($idProductGroup) !== null;
	}

	public function get(int $idProductGroup): ?ProductGroup
	{
		if ($idProductGroup < 1)
			throw new ControlException('identificação do grupo inválida');

		return $this->productGroupDAO->select($idProductGroup);
	}

	public function getByName(string $group): ?ProductGroup
	{
		return $this->productGroupDAO->selectByName($group);
	}

	public function getByFamily(int $idProductFamily): ProductCategories
	{
		return $this->productGroupDAO->selectByFamily($idProductFamily);
	}

	public function search(string $group): ProductCategories
	{
		return $this->productGroupDAO->searchByName($group);
	}
}

