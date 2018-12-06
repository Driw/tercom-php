<?php

namespace tercom\control;

use tercom\dao\ProductPriceDAO;
use tercom\entities\ProductPrice;
use tercom\entities\lists\ProductPrices;
use dProject\MySQL\MySQL;
use tercom\core\System;

class ProductPriceControl extends GenericControl
{
	/**
	 * @var ProductPriceDAO
	 */
	private $productPriceDAO;
	/**
	 * @var ProductControl
	 */
	private $productControl;
	/**
	 * @var ProviderControl
	 */
	private $providerControl;
	/**
	 * @var ManufacturerControl
	 */
	private $manufacturerControl;
	/**
	 * @var ProductPackageControl
	 */
	private $productPackageControl;
	/**
	 * @var ProductTypeControl
	 */
	private $productTypeControl;

	/**
	 * @param MySQL $mysql
	 */

	public function __construct()
	{
		$mysql = System::getWebConnection();
		$this->productPriceDAO = new ProductPriceDAO();
		$this->productControl = new ProductControl($mysql);
		$this->providerControl = new ProviderControl($mysql);
		$this->manufacturerControl = new ManufacturerControl($mysql);
		$this->productPackageControl = new ProductPackageControl($mysql);
		$this->productTypeControl = new ProductTypeControl($mysql);
	}

	private function validate(ProductPrice $productPrice, bool $validateID)
	{
		if ($validateID) {
			if ($productPrice->getID() === 0)
				throw new ControlException('valor do produto não identificado');
		} else {
			if ($productPrice->getID() !== 0)
				throw new ControlException('valor do produto já identificado');
		}

		if ($productPrice->getAmount() === 0) throw new ControlException('quantidade não informada');
		if ($productPrice->getPrice() === 0.0) throw new ControlException('valor não informado');
		if ($productPrice->getProduct()->getID() === 0) throw new ControlException('produto não informado');
		if ($productPrice->getManufacture()->getID() === 0) throw new ControlException('fabricante não informado');
		if ($productPrice->getProvider()->getID() === 0) throw new ControlException('fornecedor não informado');
		if ($productPrice->getProductPackage()->getID() === 0) throw new ControlException('tipo de pacote não informado');
		if ($productPrice->getProductType()->getID() === 0) throw new ControlException('tipo de produto não informado');

		if (!$this->productControl->has($productPrice->getProduct()->getID())) throw new ControlException('produto não encontrado');
		if (!$this->providerControl->has($productPrice->getProvider()->getID())) throw new ControlException('fornecedor não encontrado');
		if (!$this->manufacturerControl->has($productPrice->getManufacture()->getID())) throw new ControlException('fabricante não encontrado');
		if (!$this->productPackageControl->has($productPrice->getProductPackage()->getID())) throw new ControlException('embalagem de produto não encontrado');
		if (!$this->productTypeControl->has($productPrice->getProductType()->getID())) throw new ControlException('tipo de produto não encontrado');
	}

	public function add(ProductPrice $productPrice): bool
	{
		$this->validate($productPrice, false);

		return $this->productPriceDAO->insert($productPrice);
	}

	public function set(ProductPrice $productPrice): bool
	{
		$this->validate($productPrice, true);

		return $this->productPriceDAO->update($productPrice);
	}

	public function remove(ProductPrice $productPrice): bool
	{
		$this->validate($productPrice, true);

		return $this->productPriceDAO->delete($productPrice);
	}

	public function get(int $idProductPrice): ?ProductPrice
	{
		return $this->productPriceDAO->select($idProductPrice);
	}

	public function getByProduct(int $idProduct): ProductPrices
	{
		return $this->productPriceDAO->selectPrices($idProduct);
	}

	public function searchByProvider(int $idProduct, int $idProvider): ProductPrices
	{
		return $this->productPriceDAO->selectByProvider($idProduct, $idProvider);
	}

	public function searchByName(string $name): ProductPrices
	{
		return $this->productPriceDAO->selectLikeName($name);
	}
}

