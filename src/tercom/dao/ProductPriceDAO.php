<?php

namespace tercom\dao;

use tercom\entities\ProductPrice;
use tercom\entities\lists\ProductPrices;
use dProject\MySQL\Result;
use tercom\Functions;

class ProductPriceDAO extends GenericDAO
{
	private function newSelect(): string
	{
		$productPriceColumns = ['id', 'idProduct', 'idProvider', 'idManufacturer', 'idProductPackage', 'idProductType', 'name', 'amount', 'price', 'lastUpdate'];
		$productColumns = ['id', 'name', 'description', 'inactive', 'idProductUnit', 'idProductFamily', 'idProductGroup', 'idProductSubGroup', 'idProductSector'];
		$productUnitColumns = ['id', 'name', 'shortName'];
		$productFamilyColumns = ['id', 'name'];
		$productGroupColumns = ['id', 'name'];
		$productSubGroupColumns = ['id', 'name'];
		$productSectorColumns = ['id', 'name'];
		$productProviderColumns = ['id', 'companyName', 'fantasyName', 'spokesman', 'site'];
		$productManufacturerColumns = ['id', 'fantasyName'];
		$productPackageColumns = ['id', 'name'];
		$productTypeColumns = ['id', 'name'];
		$productPriceQuery = $this->buildQuery($productPriceColumns, 'product_prices');
		$productQuery = $this->buildQuery($productColumns, 'products', 'product');
		$productUnitQuery = $this->buildQuery($productUnitColumns, 'product_units', 'productUnit');
		$productFamilyQuery = $this->buildQuery($productFamilyColumns, 'product_families', 'productFamily');
		$productGroupQuery = $this->buildQuery($productGroupColumns, 'product_groups', 'productGroup');
		$productSubGroupQuery = $this->buildQuery($productSubGroupColumns, 'product_subgroups', 'productSubgroup');
		$productSectorQuery = $this->buildQuery($productSectorColumns, 'product_sectores', 'productSector');
		$productProviderQuery = $this->buildQuery($productProviderColumns, 'providers', 'provider');
		$productManufacturerQuery = $this->buildQuery($productManufacturerColumns, 'manufacturers', 'productManufacturer');
		$productPackageQuery = $this->buildQuery($productPackageColumns, 'product_packages', 'productPackage');
		$productTypeQuery = $this->buildQuery($productTypeColumns, 'product_types', 'productType');

		return "SELECT $productPriceQuery, $productProviderQuery, $productManufacturerQuery, $productPackageQuery, $productTypeQuery,
					$productQuery, $productUnitQuery, $productFamilyQuery, $productGroupQuery, $productSubGroupQuery, $productSectorQuery
				FROM product_prices
				LEFT JOIN products ON product_prices.idProduct = products.id
				LEFT JOIN product_units ON products.idProductUnit = product_units.id
				LEFT JOIN product_families ON products.idProductFamily = product_families.id
				LEFT JOIN product_groups ON products.idProductGroup = product_groups.id
				LEFT JOIN product_subgroups ON products.idProductSubGroup = product_subgroups.id
				LEFT JOIN product_sectores ON products.idProductSector = product_sectores.id
				LEFT JOIN product_packages ON product_prices.idProductPackage = product_packages.id
				LEFT JOIN product_types ON product_prices.idProductType = product_types.id
				LEFT JOIN manufacturers ON product_prices.idManufacturer = manufacturers.id
				LEFT JOIN providers ON product_prices.idProvider = providers.id";
	}

	public function insert(ProductPrice $productPrice): bool
	{
		$sql = "INSERT INTO product_prices (idProduct, idProvider, idManufacturer, idProductPackage, idProductType, name, amount, price, lastUpdate)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productPrice->getProduct()->getID());
		$query->setInteger(2, $productPrice->getProvider()->getID());
		$query->setInteger(3, $productPrice->getManufacture()->getID());
		$query->setInteger(4, $productPrice->getProductPackage()->getID());
		$query->setInteger(5, $productPrice->getProductType()->getID());
		$query->setString(6, $productPrice->getName());
		$query->setInteger(7, $productPrice->getAmount());
		$query->setFloat(8, $productPrice->getPrice());
		$query->setDateTime(9, $productPrice->getLastUpdate());

		$result = $query->execute();

		if ($result->isSuccessful())
			$productPrice->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(ProductPrice $productPrice): bool
	{
		$productPrice->getLastUpdate()->setTimestamp(time());

		$sql = "UPDATE product_prices
				SET idProvider = ?, idManufacturer = ?, idProductPackage = ?, idProductType = ?, name = ?, amount = ?, price = ?, lastUpdate = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productPrice->getProvider()->getID());
		$query->setInteger(2, $productPrice->getManufacture()->getID());
		$query->setInteger(3, $productPrice->getProductPackage()->getID());
		$query->setInteger(4, $productPrice->getProductType()->getID());
		$query->setString(5, $productPrice->getName());
		$query->setInteger(6, $productPrice->getAmount());
		$query->setFloat(7, $productPrice->getPrice());
		$query->setDateTime(8, $productPrice->getLastUpdate());
		$query->setInteger(9, $productPrice->getID());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function delete(ProductPrice $productPrice): bool
	{
		$sql = "DELETE FROM product_prices
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productPrice->getID());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function select(int $idProductPrice): ?ProductPrice
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE product_prices.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductPrice);

		$result = $query->execute();

		return $this->parseProductPrice($result);
	}

	public function selectPrices(int $idProduct): ProductPrices
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE product_prices.idProduct = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProduct);

		$result = $query->execute();

		return $this->parseProductPrices($result);
	}

	public function selectByProvider(int $idProduct, int $idProvider): ProductPrices
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE product_prices.idProduct = ? AND (? = 0 OR product_prices.idProvider = ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProduct);
		$query->setInteger(2, $idProvider);
		$query->setInteger(3, $idProvider);

		$result = $query->execute();

		return $this->parseProductPrices($result);
	}

	public function selectLikeName(string $name): ProductPrices
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE products.name LIKE ? OR product_prices.name LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$name%");
		$query->setString(2, "%$name%");

		$result = $query->execute();

		return $this->parseProductPrices($result);
	}

	public function existManufacturer(int $idManufacturer): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_prices
				WHERE idManufacture = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idManufacturer);

		return $this->parseQueryExist($query);
	}

	private function parseProductPrice(Result $result): ?ProductPrice
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$productPrice = $this->newProductPrice($array);

		return $productPrice;
	}

	private function parseProductPrices(Result $result): ProductPrices
	{
		$productPrices = new ProductPrices();

		while ($result->hasNext())
		{
			$array = $result->next();
			$productPrice = $this->newProductPrice($array);
			$productPrices->add($productPrice);
		}

		return $productPrices;
	}

	private function newProductPrice(array $array): ProductPrice
	{
		$productPrice = new ProductPrice();
		$productPrice->fromArray($array);
		$productPrice->getProduct()->fromArray(Functions::parseEntrySQL($array, 'product'));
		$productPrice->getProduct()->getUnit()->fromArray(Functions::parseEntrySQL($array, 'productUnit'));
		$productPrice->getProduct()->getCategory()->getFamily()->fromArray(Functions::parseEntrySQL($array, 'productFamily'));
		$productPrice->getProduct()->getCategory()->getGroup()->fromArray(Functions::parseEntrySQL($array, 'productGroup'));
		$productPrice->getProduct()->getCategory()->getSubgroup()->fromArray(Functions::parseEntrySQL($array, 'productSubgroup'));
		$productPrice->getProduct()->getCategory()->getSector()->fromArray(Functions::parseEntrySQL($array, 'productSector'));
		$productPrice->getProvider()->fromArray(Functions::parseEntrySQL($array, 'provider'));
		$productPrice->getManufacture()->fromArray(Functions::parseEntrySQL($array, 'productManufacturer'));
		$productPrice->getProductPackage()->fromArray(Functions::parseEntrySQL($array, 'productPackage'));
		$productPrice->getProductType()->fromArray(Functions::parseEntrySQL($array, 'productType'));

		return $productPrice;
	}
}
