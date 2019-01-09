<?php

namespace tercom\control;

use tercom\entities\OrderRequest;
use tercom\entities\OrderItemProduct;
use tercom\entities\Product;
use tercom\dao\OrderItemProductDAO;
use tercom\entities\lists\OrderItemProducts;
use tercom\exceptions\OrderItemProductException;

/**
 *
 *
 * @see GenericControl
 * @see OrderItemProduct
 * @see OrderItemProducts
 * @see OrderItemProductDAO
 *
 * @author Andrew
 */
class OrderItemProductControl extends GenericControl
{
	/**
	 * @var OrderItemProductDAO
	 */
	private $orderItemProductDAO;

	/**
	 *
	 */
	public function __construct()
	{
		$this->orderItemProductDAO = new OrderItemProductDAO();
	}

	/**
	 * @param OrderRequest $orderRequest
	 * @param OrderItemProduct $orderItemProduct
	 * @throws OrderItemProductException
	 */
	public function add(OrderRequest $orderRequest, OrderItemProduct $orderItemProduct): void
	{
		if (!$this->orderItemProductDAO->insert($orderRequest, $orderItemProduct))
			throw OrderItemProductException::newInserted();
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @param OrderItemProduct $orderItemProduct
	 * @throws OrderItemProductException
	 */
	public function set(OrderRequest $orderRequest, OrderItemProduct $orderItemProduct): void
	{
		if (!$this->orderItemProductDAO->update($orderRequest, $orderItemProduct))
			throw OrderItemProductException::newUpdated();
	}

	/**
	 * @param $orderRequest OrderRequest
	 * @param $orderItemProduct OrderItemProduct
	 */
	public function remove(OrderRequest $orderRequest, OrderItemProduct $orderItemProduct): void
	{
		if (!$this->orderItemProductDAO->delete($orderRequest, $orderItemProduct))
			throw OrderItemProductException::newDeleted();
	}

	/**
	 * @param $orderRequest OrderRequest
	 */
	public function removeAll(OrderRequest $orderRequest): void
	{
		if (!$this->orderItemProductDAO->deleteAll($orderRequest))
			throw OrderItemProductException::newDeletedAll();
	}

	/**
	 *
	 * @param int $idOrderItemProduct
	 * @throws OrderItemProductException
	 * @return OrderItemProducts
	 */
	public function get(int $idOrderItemProduct): OrderItemProduct
	{
		if (($orderItemProduct = $this->orderItemProductDAO->select($idOrderItemProduct)) === null)
			throw OrderItemProductException::newSelected();

		return $orderItemProduct;
	}

	/**
	 * @param OrderRequest $orderRequest
	 * @return OrderItemProducts
	 */
	public function getAll(OrderRequest $orderRequest): OrderItemProducts
	{
		return $this->orderItemProductDAO->selectAll($orderRequest);
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @param Product $product
	 * @return bool
	 */
	public function avaiableProduct(OrderRequest $orderRequest, Product $product): bool
	{
		return !$this->orderItemProductDAO->exist($orderRequest, $product);
	}
}

