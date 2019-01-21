<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\entities\Manufacturer;
use tercom\entities\OrderAcceptance;
use tercom\entities\OrderAcceptanceProduct;
use tercom\entities\Product;
use tercom\entities\ProductPackage;
use tercom\entities\ProductType;
use tercom\entities\Provider;
use tercom\entities\lists\OrderAcceptanceProducts;
use tercom\exceptions\OrderAcceptanceProductException;

/**
 * @author Andrew
 */
class OrderAcceptanceProductDAO extends GenericDAO
{
	public const ALL_COLUMNS = [
		'id', 'idQuotedProductPrice', 'idProduct', 'idProvider', 'idManufacturer', 'idProductPackage', 'idProductType',
		'name', 'amount', 'amountRequest', 'price', 'subprice', 'observations', 'lastUpdate'
	];

	private function validate(?OrderAcceptance $orderAcceptance, OrderAcceptanceProduct $orderAcceptanceProduct, bool $validaId): void
	{
		// PRIMARY KEY
		if ($validaId) {
			if ($orderAcceptanceProduct->getId() === 0)
				throw OrderAcceptanceProductException::newNotIdentified();
		} else {
			if ($orderAcceptanceProduct->getId() !== 0)
				throw OrderAcceptanceProductException::newIdentified();
		}

		// UNIQUE KEY
		if ($orderAcceptance !== null)
			if ($this->existQuotedPrice($orderAcceptance, $orderAcceptanceProduct)) throw OrderAcceptanceProductException::newQuotedPriceUsed();

		// NOT NULL
		if ($orderAcceptance !== null && $orderAcceptance->getId() === 0) throw OrderAcceptanceProductException::newAcceptanceEmpty();
		if (StringUtil::isEmpty($orderAcceptanceProduct->getName())) throw OrderAcceptanceProductException::newNameEmpty();
		if ($orderAcceptanceProduct->getAmount() === 0) throw OrderAcceptanceProductException::newAmountEmpty();
		if ($orderAcceptanceProduct->getPrice() === 0.0) throw OrderAcceptanceProductException::newPriceEmpty();
		if ($orderAcceptanceProduct->getAmountRequest() === 0) throw OrderAcceptanceProductException::newAmountRequestEmpty();
		if ($orderAcceptanceProduct->getSubprice() === 0.0) throw OrderAcceptanceProductException::newSubpriceEmpty();
		if ($orderAcceptanceProduct->getProductId() === 0) throw OrderAcceptanceProductException::newProductEmpty();
		if ($orderAcceptanceProduct->getProviderId() === 0) throw OrderAcceptanceProductException::newProviderEmpty();
		if ($orderAcceptanceProduct->getProductPackageId() === 0) throw OrderAcceptanceProductException::newProductPackageEmpty();

		// FOREIGN KEY
		if ($orderAcceptance !== null)
			if (!$this->existOrderAcceptance($orderAcceptance)) throw OrderAcceptanceProductException::newAcceptanceInvalid();
		if (!$this->existProduct($orderAcceptanceProduct->getProduct())) throw OrderAcceptanceProductException::newProductInvalid();
		if (!$this->existProvider($orderAcceptanceProduct->getProvider())) throw OrderAcceptanceProductException::newProviderInvalid();
		if (!$this->existProductPackage($orderAcceptanceProduct->getProductPackage())) throw OrderAcceptanceProductException::newProductPackageInvalid();
		if ($orderAcceptanceProduct->getManufacturerId() !== 0)
			if (!$this->existManufacturer($orderAcceptanceProduct->getManufacturer())) throw OrderAcceptanceProductException::newManufacturerInvalid();
		if ($orderAcceptanceProduct->getProductTypeId() !== 0)
			if (!$this->existProductType($orderAcceptanceProduct->getProductType())) throw OrderAcceptanceProductException::newProductTypeInvalid();
	}

	public function insert(OrderAcceptance $orderAcceptance, OrderAcceptanceProduct $orderAcceptanceProduct): bool
	{
		$this->validate($orderAcceptance, $orderAcceptanceProduct, false);

		$sql = "INSERT INTO order_acceptance_products (
					idOrderAcceptance, idQuotedProductPrice, idProduct, idProvider, idManufacturer, idProductPackage, idProductType,
					name, amount, amountRequest, price, subprice, observations, lastUpdate
				) VALUES (
					?, ?, ?, ?, ?, ?, ?,
					?, ?, ?, ?, ?, ?, ?
				)";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $orderAcceptance->getId());
		$query->setInteger(2, $orderAcceptanceProduct->getIdQuotedProductPrice());
		$query->setInteger(3, $orderAcceptanceProduct->getProductId());
		$query->setInteger(4, $orderAcceptanceProduct->getProviderId());
		$query->setInteger(5, $this->parseNullID($orderAcceptanceProduct->getManufacturerId()));
		$query->setInteger(6, $orderAcceptanceProduct->getProductPackageId());
		$query->setInteger(7, $this->parseNullID($orderAcceptanceProduct->getProductTypeId()));
		$query->setString(8, $orderAcceptanceProduct->getName());
		$query->setInteger(9, $orderAcceptanceProduct->getAmount());
		$query->setInteger(10, $orderAcceptanceProduct->getAmountRequest());
		$query->setFloat(11, $orderAcceptanceProduct->getPrice());
		$query->setFloat(12, $orderAcceptanceProduct->getSubprice());
		$query->setString(13, $orderAcceptanceProduct->getObservations());
		$query->setDateTime(14, $orderAcceptanceProduct->getLastUpdate());

		if (($result = $query->execute())->isSuccessful())
			$orderAcceptanceProduct->setId($result->getInsertID());

		return $orderAcceptanceProduct->getId() !== 0;
	}

	public function update(OrderAcceptanceProduct $orderAcceptanceProduct): bool
	{
		$this->validate(null, $orderAcceptanceProduct, true);

		$sql = "UPDATE order_acceptance_products
				SET amountRequest = ?, subprice = ?, observations = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $orderAcceptanceProduct->getAmountRequest());
		$query->setFloat(2, $orderAcceptanceProduct->getSubprice());
		$query->setString(3, $orderAcceptanceProduct->getObservations());
		$query->setInteger(4, $orderAcceptanceProduct->getId());

		return ($query->execute())->isSuccessful();
	}

	public function delete(OrderAcceptanceProduct $orderAcceptanceProduct): bool
	{
		$sql = "DELETE FROM order_acceptance_products
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderAcceptanceProduct->getId());

		return ($query->execute())->isSuccessful();
	}

	public function deleteAll(OrderAcceptance $orderAcceptance): bool
	{
		$sql = "DELETE FROM order_acceptance_products
				WHERE idOrderAcceptance = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderAcceptance->getId());

		return ($query->execute())->isSuccessful();
	}

	private function newSelect(): string
	{
		$orderAcceptanceProduct = $this->buildQuery(self::ALL_COLUMNS, 'order_acceptance_products');
		$productColumns = $this->buildQuery(ProductDAO::ALL_COLUMNS, 'products', 'product');
		$productUnitColumns = $this->buildQuery(ProductUnitDAO::ALL_COLUMNS, 'product_units', 'product_productUnit');
		$productCategoryColumns = $this->buildQuery(ProductCategoryDAO::ALL_COLUMNS, 'product_categories', 'product_productCategory');
		$productProviderColumns = $this->buildQuery(ProviderDAO::ALL_COLUMNS, 'providers', 'provider');
		$productManufacturerColumns = $this->buildQuery(ManufacturerDAO::ALL_COLUMNS, 'manufacturers', 'manufacturer');
		$productPackageColumns = $this->buildQuery(ProductPackageDAO::ALL_COLUMNS, 'product_packages', 'productPackage');
		$productTypeColumns = $this->buildQuery(ProductTypeDAO::ALL_COLUMNS, 'product_types', 'productType');

		return "SELECT
					$orderAcceptanceProduct, $productColumns, $productUnitColumns, $productCategoryColumns,
					$productProviderColumns, $productManufacturerColumns, $productPackageColumns, $productTypeColumns
				FROM order_acceptance_products
				INNER JOIN products ON products.id = order_acceptance_products.idProduct
				INNER JOIN product_units ON product_units.id = products.idProductUnit
				LEFT JOIN product_categories ON product_categories.id = products.idProductCategory
				INNER JOIN providers ON providers.id = order_acceptance_products.idProvider
				LEFT JOIN manufacturers ON manufacturers.id = order_acceptance_products.idManufacturer
				INNER JOIN product_packages ON product_packages.id = order_acceptance_products.idProductPackage
				LEFT JOIN product_types ON product_types.id = order_acceptance_products.idProductType";
	}

	public function select(int $idOrderAcceptanceProduct, int $idOrderAcceptance = 0): ?OrderAcceptanceProduct
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_acceptance_products.id = ? AND ? IN (0, order_acceptance_products.idOrderAcceptance)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idOrderAcceptanceProduct);
		$query->setInteger(2, $idOrderAcceptance);

		$result = $query->execute();

		return $this->parseOrderAcceptanceProduct($result);
	}

	public function selectByOrderAcceptance(OrderAcceptance $orderAcceptance): OrderAcceptanceProducts
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_acceptance_products.idOrderAcceptance = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderAcceptance->getId());

		$result = $query->execute();

		return $this->parseOrderAcceptanceProducts($result);
	}
	private function existQuotedPrice(OrderAcceptance $orderAcceptance, OrderAcceptanceProduct $orderAcceptanceProduct): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM order_acceptance_products
				WHERE idOrderAcceptance = ? AND idQuotedProductPrice = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderAcceptance->getId());
		$query->setInteger(2, $orderAcceptanceProduct->getIdQuotedProductPrice());

		return $this->parseQueryExist($query);
	}

	private function existOrderAcceptance(OrderAcceptance $orderAcceptance): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM order_acceptances
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderAcceptance->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação de preço de produto existe.
	 * @param Product $product objeto do tipo produto à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	private function existProduct(Product $product): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM products
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $product->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação de fornecedor existe.
	 * @param Provider $provider objeto do tipo fornecedor à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	private function existProvider(Provider $provider): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM providers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $provider->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação de fabricante existe.
	 * @param Manufacturer $manufacturer objeto do tipo fabricante à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	private function existManufacturer(Manufacturer $manufacturer): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM manufacturers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $manufacturer->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação da embalade de produto existe.
	 * @param ProductPackage $productPackage objeto do tipo pacote de produto à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	private function existProductPackage(ProductPackage $productPackage): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_packages
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productPackage->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação do tipo de produto existe.
	 * @param ProductType $productType objeto do tipo tipo de produto à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	private function existProductType(ProductType $productType): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_types
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productType->getId());

		return $this->parseQueryExist($query);
	}

	private function parseOrderAcceptanceProduct(Result $result): ?OrderAcceptanceProduct
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newOrderAcceptanceProduct($entry);
	}

	private function parseOrderAcceptanceProducts(Result $result): OrderAcceptanceProducts
	{
		$orderAcceptanceProducts = new OrderAcceptanceProducts();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$orderAcceptanceProduct = $this->newOrderAcceptanceProduct($entry);
			$orderAcceptanceProducts->add($orderAcceptanceProduct);
		}

		return $orderAcceptanceProducts;
	}

	private function newOrderAcceptanceProduct(array $entry): OrderAcceptanceProduct
	{
		$this->parseEntry($entry, 'product', 'provider', 'manufacturer', 'productPackage', 'productType');
		$this->parseEntry($entry['product'], 'productUnit', 'productCategory');

		$orderAcceptanceProduct = new OrderAcceptanceProduct();
		$orderAcceptanceProduct->fromArray($entry);

		return $orderAcceptanceProduct;
	}
}

