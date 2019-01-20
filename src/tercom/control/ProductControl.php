<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use tercom\dao\ProductDAO;
use tercom\entities\Product;
use tercom\entities\lists\Products;
use tercom\exceptions\ProductException;

class ProductControl extends GenericControl
{
	/**
	 * @var ProductDAO
	 */
	private $productDAO;
	/**
	 * @var ProductUnitControl
	 */
	private $productUnitControl;
	/**
	 * @var ProductCategoryControl
	 */
	private $productCategoryControl;

	/**
	 * @param MySQL $mysql
	 */
	public function __construct()
	{
		$this->productDAO = new ProductDAO();
		$this->productUnitControl = new ProductUnitControl();
		$this->productCategoryControl = new ProductCategoryControl();
	}

	public function add(Product $product): void
	{
		if (!$this->productDAO->insert($product))
			throw ProductException::newNotInserted();
	}

	public function set(Product $product): void
	{
		if (!$this->productDAO->update($product))
			throw ProductException::newNotUpdated();
	}

	public function get(int $idProduct): Product
	{
		if (($product = $this->productDAO->select($idProduct)) === null)
			throw ProductException::newNotSelected();

		return $product;
	}

	public function getAll(): Products
	{
		return $this->productDAO->selectAll();
	}

	public function searchByName(string $name): Products
	{
		return $this->productDAO->selectLikeName($name);
	}

	public function searchByProductCategory(int $idProductCategory): Products
	{
		return $this->productDAO->selectByProductCategory($idProductCategory);
	}

	public function searchByProductFamily(int $idProductFamily): Products
	{
		return $this->productDAO->selectByProductFamily($idProductFamily);
	}

	public function searchByProductGroup(int $idProductGroup): Products
	{
		return $this->productDAO->selectByProductGroup($idProductGroup);
	}

	public function searchByProductSubGroup(int $idProductSubGroup): Products
	{
		return $this->productDAO->selectByProductSubGroup($idProductSubGroup);
	}

	public function searchByProductSector(int $idProductSector): Products
	{
		return $this->productDAO->selectByProductSector($idProductSector);
	}

	public function has(int $idProduct): bool
	{
		return $this->productDAO->existID($idProduct);
	}

	public function hasName(string $name, int $idProduct = 0): bool
	{
		return $this->productDAO->existName($name, $idProduct);
	}

	public static function getFilters(): array
	{
		return [
			'name' => 'Nome',
			'family' => 'Família',
			'group' => 'Grupo',
			'subgroup' => 'Subgrupo',
			'sector' => 'Setor',
		];
	}
}

