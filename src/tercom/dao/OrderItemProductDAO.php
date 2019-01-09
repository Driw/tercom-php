<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\Manufacturer;
use tercom\entities\OrderItemProduct;
use tercom\entities\OrderRequest;
use tercom\entities\Product;
use tercom\entities\Provider;
use tercom\entities\lists\OrderItemProducts;
use tercom\exceptions\OrderItemProductException;

/**
 *
 *
 * @see GenericDAO
 * @see OrderRequest
 * @see OrderItemProduct
 * @see OrderItemProducts
 *
 * @author Andrew
 */
class OrderItemProductDAO extends GenericDAO
{
	/**
	 * @var array
	 */
	public const ALL_COLUMNS = ['id', 'idOrderRequest', 'idProduct', 'idProvider', 'idManufacturer', 'betterPrice', 'observations'];

	/**
	 *
	 * @param OrderRequest|NULL $orderRequest
	 * @param OrderItemProduct $orderItemProduct
	 * @param bool $validateId
	 * @throws OrderItemProductException
	 */
	private function validate(?OrderRequest $orderRequest, OrderItemProduct $orderItemProduct, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($orderItemProduct->getId() === 0)
				throw OrderItemProductException::newNotIdentified();
		} else {
			if ($orderItemProduct->getId() !== 0)
				throw OrderItemProductException::newIdentified();
		}

		// UNIQUE KEY
		if ($orderRequest !== null && $this->exist($orderRequest, $orderItemProduct->getProduct())) throw OrderItemProductException::newExist();

		// NOT NULL
		if ($orderItemProduct->getProductId() === 0) throw OrderItemProductException::newProductEmpty();

		// FOREIGN KEY
		if (!$this->existProduct($orderItemProduct->getProduct())) throw OrderItemProductException::newProviderInvalid();
		if ($orderItemProduct->getProviderId() !== 0 && !$this->existProvider($orderItemProduct->getProvider())) throw OrderItemProductException::newProviderInvalid();
		if ($orderItemProduct->getManufacturerId() !== 0 && !$this->existManufacturer($orderItemProduct->getManufacturer())) throw OrderItemProductException::newManufacturerInvalid();
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @param OrderItemProduct $orderItemProduct
	 * @throws OrderItemProductException
	 * @return bool
	 */
	public function insert(OrderRequest $orderRequest, OrderItemProduct $orderItemProduct): bool
	{
		$this->validate($orderRequest, $orderItemProduct, false);

		$sql = "INSERT INTO order_item_products (idOrderRequest, idProduct, idProvider, idManufacturer, betterPrice, observations)
				VALUES (?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $orderRequest->getId());
		$query->setInteger(2, $orderItemProduct->getProductId());
		$query->setInteger(3, $this->parseNullID($orderItemProduct->getProviderId()));
		$query->setInteger(4, $this->parseNullID($orderItemProduct->getManufacturerId()));
		$query->setBoolean(5, $orderItemProduct->isBetterPrice());
		$query->setString(6, $orderItemProduct->getObservations());

		if (($result = $query->execute())->isSuccessful())
			$orderItemProduct->setId($result->getInsertID());

		return $orderItemProduct->getId() !== 0;
	}

	/**
	 *
	 * @param OrderItemProduct $orderItemProduct
	 * @throws OrderItemProductException
	 * @return bool
	 */
	public function update(OrderItemProduct $orderItemProduct): bool
	{
		$this->validate(null, $orderItemProduct, true);

		$sql = "UPDATE order_item_products
				SET idProvider = ?, idManufacturer = ?, betterPrice = ?, observations = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $this->parseNullID($orderItemProduct->getProviderId()));
		$query->setInteger(2, $this->parseNullID($orderItemProduct->getManufacturerId()));
		$query->setBoolean(3, $orderItemProduct->isBetterPrice());
		$query->setString(4, $orderItemProduct->getObservations());
		$query->setString(5, $orderItemProduct->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param OrderItemProduct $orderItemProduct
	 * @throws OrderItemProductException
	 * @return bool
	 */
	public function delete(OrderItemProduct $orderItemProduct): bool
	{
		$this->validate(null, $orderItemProduct, true);

		$sql = "DELETE FROM order_item_products
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderItemProduct->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @return bool
	 */
	public function deleteAll(OrderRequest $orderRequest): bool
	{
		$sql = "DELETE FROM order_item_products
				WHERE idOrderRequest = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @return string
	 */
	private function newSelect(): string
	{
		$orderItemProductColumns = $this->buildQuery(self::ALL_COLUMNS, 'order_item_products');
		$productColumns = $this->buildQuery(ProductDAO::ALL_COLUMNS, 'products', 'product');
		$providerColumns = $this->buildQuery(ProviderDAO::ALL_COLUMNS, 'providers', 'provider');
		$manufacturerColumns = $this->buildQuery(ManufacturerDAO::ALL_COLUMNS, 'manufacturers', 'manufacturer');

		return "SELECT $orderItemProductColumns, $productColumns, $providerColumns, $manufacturerColumns
				FROM order_item_products
				INNER JOIN products ON products.id = order_item_products.idProduct
				LEFT JOIN providers ON providers.id = order_item_products.idProvider
				LEFT JOIN manufacturers ON manufacturers.id = order_item_products.idManufacturer";
	}

	/**
	 *
	 * @param int $idOrderItemProduct
	 * @return OrderItemProduct
	 */
	public function select(int $idOrderItemProduct): ?OrderItemProduct
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_item_products.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idOrderItemProduct);

		$result = $query->execute();

		return $this->parseOrderItemProduct($result);
	}

	/**
	 *
	 * @param int $idOrderRequest
	 * @param int $idOrderRequest
	 * @return OrderItemProduct
	 */
	public function selectWithOrderRequest(int $idOrderRequest, int $idOrderRequest): ?OrderItemProduct
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_item_products.id = ? AND order_item_products.idOrderRequest = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idOrderRequest);
		$query->setInteger(2, $idOrderRequest);

		$result = $query->execute();

		return $this->parseOrderItemProduct($result);
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @return OrderItemProducts
	 */
	public function selectAll(OrderRequest $orderRequest): OrderItemProducts
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_item_products.idOrderRequest = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());

		$result = $query->execute();

		return $this->parseOrderItemProducts($result);
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @param Product $product
	 * @return bool
	 */
	public function exist(OrderRequest $orderRequest, Product $product): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM order_item_products
				WHERE idOrderRequest = ? AND idProduct = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());
		$query->setInteger(2, $product->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 *
	 * @param Product $product
	 * @return bool
	 */
	public function existProduct(Product $product): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM products
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $product->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 *
	 * @param Provider $provider
	 * @return bool
	 */
	public function existProvider(Provider $provider): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM providers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $provider->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 *
	 * @param Manufacturer $manufacturer
	 * @return bool
	 */
	public function existManufacturer(Manufacturer $manufacturer): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM manufacturers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $manufacturer->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 *
	 * @param Result $result
	 * @return OrderItemProduct|NULL
	 */
	private function parseOrderItemProduct(Result $result): ?OrderItemProduct
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newOrderItemProduct($entry);
	}

	/**
	 *
	 * @param Result $result
	 * @return OrderItemProducts
	 */
	private function parseOrderItemProducts(Result $result): OrderItemProducts
	{
		$orderItemProducts = new OrderItemProducts();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$orderItemProduct = $this->newOrderItemProduct($entry);
			$orderItemProducts->add($orderItemProduct);
		}

		return $orderItemProducts;
	}

	/**
	 *
	 * @param array $entry
	 * @return OrderItemProduct
	 */
	private function newOrderItemProduct(array $entry): OrderItemProduct
	{
		$this->parseEntry($entry, 'product', 'provider', 'manufacturer');

		$orderItemProduct = new OrderItemProduct();
		$orderItemProduct->fromArray($entry);

		return $orderItemProduct;
	}
}

