<?php

namespace tercom\control;

use tercom\dao\ProductDAO;
use dProject\MySQL\MySQL;
use tercom\entities\Product;
use tercom\entities\lists\Products;
use tercom\entities\ProductCategorySet;

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
	 * @var ProductFamilyControl
	 */
	private $productFamilyControl;
	/**
	 * @var ProductGroupControl
	 */
	private $productGroupControl;
	/**
	 * @var ProductSubGroupControl
	 */
	private $productSubGroupControl;
	/**
	 * @var ProductSectorControl
	 */
	private $productSectorControl;

	/**
	 * @param MySQL $mysql
	 */

	public function __construct(MySQL $mysql)
	{
		$this->productDAO = new ProductDAO($mysql);
		$this->productUnitControl = new ProductUnitControl($mysql);
		$this->productFamilyControl = new ProductFamilyControl($mysql);
		$this->productGroupControl = new ProductGroupControl($mysql);
		$this->productSubGroupControl = new ProductSubGroupControl($mysql);
		$this->productSectorControl = new ProductSectorControl($mysql);
	}

	private function validate(Product $product, bool $validateID)
	{
		if ($validateID) {
			if ($product->getID() === 0)
				throw new ControlException('produto não identificado');
		} else {
			if ($product->getID() !== 0)
				throw new ControlException('produto já identificado');
		}

		if (empty($product->getName())) throw new ControlException('nome em branco');
		if (empty($product->getDescription())) throw new ControlException('descrição em branco');

		if (!$this->productUnitControl->has($product->getUnit()->getID()))
			throw new ControlException('unidade de produto não encontrada');

		if ($product->getCategory()->getFamily()->getID() !== 0 &&
			!$this->productFamilyControl->has($product->getCategory()->getFamily()->getID()))
			throw new ControlException('família de produto não encontrada');

		if ($product->getCategory()->getGroup()->getID() !== 0 &&
			!$this->productGroupControl->has($product->getCategory()->getGroup()->getID()))
			throw new ControlException('grupo de produto não encontrada');

		if ($product->getCategory()->getSubgroup()->getID() !== 0 &&
			!$this->productSubGroupControl->has($product->getCategory()->getSubgroup()->getID()))
			throw new ControlException('subgrupo de produto não encontrada');

		if ($product->getCategory()->getSector()->getID() !== 0 &&
			!$this->productSectorControl->has($product->getCategory()->getSector()->getID()))
			throw new ControlException('setor de produto não encontrada');
	}

	public function add(Product $product): bool
	{
		$this->validate($product, false);

		if (!$this->hasAvaiableName($product->getName(), $product->getID()))
			throw new ControlException('nome do produto já utilizado');

		$product->setUnit($this->productUnitControl->get($product->getUnit()->getID()));
		$this->loadCategories($product);

		return $this->productDAO->insert($product);
	}

	public function loadCategories(Product $product)
	{
		$category = new ProductCategorySet();

		if (($idProductFamily = $product->getCategory()->getFamily()->getID()) !== 0)
			$category->setFamily($this->productFamilyControl->get($idProductFamily));

		if (($idProductGroup = $product->getCategory()->getGroup()->getID()) !== 0)
			$category->setGroup($this->productGroupControl->get($idProductGroup));

		if (($idProductSubGroup = $product->getCategory()->getSubgroup()->getID()) !== 0)
			$category->setSubgroup($this->productSubGroupControl->get($idProductSubGroup));

		if (($idProductSector = $product->getCategory()->getSector()->getID()) !== 0)
			$category->setSector($this->productSectorControl->get($idProductSector));

		$product->setCategory($category);
	}

	public function set(Product $product): bool
	{
		$this->validate($product, true);

		return $this->productDAO->update($product);
	}

	public function get(int $idProduct): ?Product
	{
		$product = $this->productDAO->select($idProduct);

		return $product;
	}

	public function getAll(): Products
	{
		$product = $this->productDAO->selectAll();

		return $product;
	}

	public function searchByName(string $name): Products
	{
		return $this->productDAO->selectByName($name);
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

	public function hasAvaiableName(string $name, int $idProduct = 0): bool
	{
		return !$this->productDAO->existName($name, $idProduct);
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

