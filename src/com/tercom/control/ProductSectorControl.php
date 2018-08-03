<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use dProject\MySQL\MySQLException;
use tercom\dao\ProductSectorDAO;
use tercom\entities\ProductSector;
use tercom\entities\lists\ProductCategories;

class ProductSectorControl extends GenericControl
{
	/**
	 * @var ProductSectorDAO
	 */
	private $productSectorDAO;
	/**
	 * @var ProductSubGroupControl
	 */
	private $productSubGroupControl;

	public function __construct(MySQL $mysql)
	{
		$this->productSectorDAO = new ProductSectorDAO($mysql);
		$this->productSubGroupControl = new ProductSubGroupControl($mysql);
	}

	private function validate(ProductSector $productSector, bool $validateID)
	{
		if ($validateID) {
			if ($productSector->getID() === 0)
				throw new ControlException('setor não identificado');
		} else {
			if ($productSector->getID() !== 0)
				throw new ControlException('setor já identificado');
		}

		if (empty($productSector->getName()))
			throw new ControlException('nome do setor não definido');

		if ($productSector->getProductSubGroupID() === 0)
			throw new ControlException('grupo com setor não identificada');
	}

	public function add(ProductSector $productSector): bool
	{
		$this->validate($productSector, false);

		if ($this->getByName($productSector->getName()) !== null)
			throw new ControlException('setor já registrado');

		if (!$this->productSubGroupControl->has($productSector->getProductSubGroupID()))
			throw new ControlException('subgrupo não encontrada');

		try {
			return $this->productSectorDAO->insert($productSector);
		} catch (MySQLException $e) {
			switch ($e->getCode())
			{
				case self::ER_DUP_ENTRY:
					throw new ControlException('setor já registrado');
				case self::ER_NO_REFERENCED_ROW_2:
					throw new ControlException('subgrupo não encontrada');
			}
		}
	}

	public function set(ProductSector $productSector): bool
	{
		$this->validate($productSector, true);
		$currentProduct = $this->getByName($productSector->getName());

		if ($currentProduct !== null && $currentProduct->getID() !== $productSector->getID())
			throw new ControlException('setor já registrado');

		if (!$this->productSubGroupControl->has($productSector->getProductSubGroupID()))
			throw new ControlException('subgrupo não encontrada');

		try {
			return $this->productSectorDAO->update($productSector);
		} catch (MySQLException $e) {
			switch ($e->getCode())
			{
				case self::ER_DUP_ENTRY:
					throw new ControlException('setor já registrado');
				case self::ER_NO_REFERENCED_ROW_2:
					throw new ControlException('subgrupo não encontrada');
			}
		}
	}

	public function remove(ProductSector $productSector): bool
	{
		$this->validate($productSector, true);

		return $this->productSectorDAO->delete($productSector);
	}

	public function has(int $idProductSector): bool
	{
		return $this->get($idProductSector) !== null;
	}

	public function get(int $idProductSector): ?ProductSector
	{
		if ($idProductSector < 1)
			throw new ControlException('identificação do setor inválida');

		return $this->productSectorDAO->select($idProductSector);
	}

	public function getByName(string $group): ?ProductSector
	{
		return $this->productSectorDAO->selectByName($group);
	}

	public function getBySubGroup(int $idProductSubGroup): ProductCategories
	{
		return $this->productSectorDAO->selectBySubGroup($idProductSubGroup);
	}

	public function search(string $group): ProductCategories
	{
		return $this->productSectorDAO->searchByName($group);
	}
}

