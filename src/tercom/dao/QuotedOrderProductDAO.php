<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\OrderItemProduct;
use tercom\entities\OrderRequest;
use tercom\entities\QuotedOrderProduct;
use tercom\entities\lists\QuotedOrderProducts;
use tercom\exceptions\QuotedOrderProductException;

/**
 * @author Andrew
 */
class QuotedOrderProductDAO extends GenericDAO
{
	public const ALL_COLUMNS = ['id', 'idOrderItemProduct', 'idQuotedProductPrice', 'observations'];

	public function validate(QuotedOrderProduct $quotedOrderProduct, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($quotedOrderProduct->getId() === 0)
				throw QuotedOrderProductException::newNotIdentified();
		} else {
			if ($quotedOrderProduct->getId() !== 0)
				throw QuotedOrderProductException::newIdentified();
		}

		// FOREIGN KEY
		if (!$this->existOrderItemProduct($quotedOrderProduct->getOrderItemProduct())) throw QuotedOrderProductException::newItemInvalid();
	}

	public function insert(QuotedOrderProduct $quotedOrderProduct): bool
	{
		$this->validate($quotedOrderProduct, false);

		$sql = "INSERT INTO quoted_order_products (idOrderItemProduct, idQuotedProductPrice, observations)
				VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $quotedOrderProduct->getOrderItemProductId());
		$query->setInteger(2, $quotedOrderProduct->getQuotedProductPriceId());
		$query->setString(3, $quotedOrderProduct->getObservations());

		if (($result = $query->execute())->isSuccessful())
			$quotedOrderProduct->setId($result->getInsertID());

		return $quotedOrderProduct->getId() !== 0;
	}

	public function delete(QuotedOrderProduct $quotedOrderProduct): bool
	{
		$sql = "DELETE FROM quoted_order_products
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $quotedOrderProduct->getId());

		return ($query->execute())->isSuccessful();
	}

	public function deleteAll(OrderRequest $orderRequest, OrderItemProduct $orderItemProduct): bool
	{
		$sql = "DELETE quoted_order_products
				FROM quoted_order_products
				INNER JOIN order_item_products ON order_item_products.id = quoted_order_products.idOrderItemProduct
				WHERE quoted_order_products.idOrderItemProduct = ? AND order_item_products.idOrderRequest = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderItemProduct->getId());
		$query->setInteger(2, $orderRequest->getId());

		return ($query->execute())->isSuccessful();
	}

	private function newSelect(): string
	{
		$quotedOrderProductColumns = $this->buildQuery(self::ALL_COLUMNS, 'quoted_order_products');
		$orderItemProductColumns = $this->buildQuery(OrderItemProductDAO::ALL_COLUMNS, 'order_item_products', 'orderItemProduct');
		$quotedProductPriceColumns = $this->buildQuery(QuotedProductPriceDAO::ALL_COLUMNS, 'quoted_product_prices', 'quotedProductPrice');
		$productColumns = $this->buildQuery(ProductDAO::ALL_COLUMNS, 'products', 'quotedProductPrice_product');

		return "SELECT $quotedOrderProductColumns, $orderItemProductColumns, $quotedProductPriceColumns, $productColumns
				FROM quoted_order_products
				INNER JOIN order_item_products ON order_item_products.id = quoted_order_products.idOrderItemProduct
				INNER JOIN quoted_product_prices ON quoted_product_prices.id = quoted_order_products.idQuotedProductPrice
				INNER JOIN products ON products.id = quoted_product_prices.idProduct";
	}

	public function select(int $idQuotedOrderProduct): ?QuotedOrderProduct
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE quoted_order_products.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idQuotedOrderProduct);

		$result = $query->execute();

		return $this->parseQuotedOrderProduct($result);
	}

	public function selectAll(OrderItemProduct $orderItemProduct): QuotedOrderProducts
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE quoted_order_products.idOrderItemProduct = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderItemProduct->getId());

		$result = $query->execute();

		return $this->parseQuotedOrderProducts($result);
	}

	private function existOrderItemProduct(OrderItemProduct $orderItemProduct): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_categories
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderItemProduct->getId());

		return $this->parseQueryExist($query);
	}

	public function existOnOrderRequest(OrderRequest $orderRequest, QuotedOrderProduct $quotedOrderProduct): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM quoted_order_products
				INNER JOIN quoted_product_prices ON quoted_product_prices.id = quoted_order_products.idQuotedProductPrice
				INNER JOIN order_item_products ON order_item_products.id = quoted_order_products.idOrderItemProduct
				WHERE order_item_products.idOrderRequest = ? AND quoted_order_products.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());
		$query->setInteger(2, $quotedOrderProduct->getId());

		return $this->parseQueryExist($query);
	}

	private function parseQuotedOrderProduct(Result $result): ?QuotedOrderProduct
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newQuotedOrderProduct($entry);
	}

	private function parseQuotedOrderProducts(Result $result): QuotedOrderProducts
	{
		$quotedOrderProducts = new QuotedOrderProducts();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$quotedOrderProduct = $this->newQuotedOrderProduct($entry);
			$quotedOrderProducts->add($quotedOrderProduct);
		}

		return $quotedOrderProducts;
	}

	private function newQuotedOrderProduct(array $entry): QuotedOrderProduct
	{
		$this->parseEntry($entry, 'quotedProductPrice', 'orderItemProduct', 'productPrice');
		$this->parseEntry($entry['quotedProductPrice'], 'product');

		$quotedOrderProduct = new QuotedOrderProduct();
		$quotedOrderProduct->fromArray($entry);

		return $quotedOrderProduct;
	}
}

