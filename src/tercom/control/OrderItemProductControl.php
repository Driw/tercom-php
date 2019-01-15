<?php

namespace tercom\control;

use tercom\entities\OrderRequest;
use tercom\entities\OrderItemProduct;
use tercom\entities\Product;
use tercom\dao\OrderItemProductDAO;
use tercom\entities\lists\OrderItemProducts;
use tercom\exceptions\OrderItemProductException;
use tercom\TercomException;
use tercom\exceptions\OrderRequestException;

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

	private function validateOrderRequest(OrderRequest $orderRequest): void
	{
		if ($orderRequest->getCustomerEmployee()->getCustomerProfile()->getCustomerId() !== $this->getCustomerLoggedId())
			throw TercomException::newCustomerInvliad();

		if ($orderRequest->getStatus() !== OrderRequest::ORS_NONE)
			throw OrderRequestException::newNotManagin();
	}

	/**
	 * @param OrderRequest $orderRequest
	 * @param OrderItemProduct $orderItemProduct
	 * @throws OrderItemProductException
	 */
	public function add(OrderRequest $orderRequest, OrderItemProduct $orderItemProduct): void
	{
		$this->validateOrderRequest($orderRequest);

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
		$this->validateOrderRequest($orderRequest);

		if (!$this->orderItemProductDAO->exist($orderRequest, $orderItemProduct->getProduct()))
			throw OrderItemProductException::newBinded();

		if (!$this->orderItemProductDAO->update($orderItemProduct))
			throw OrderItemProductException::newUpdated();
	}

	/**
	 * @param $orderRequest OrderRequest
	 * @param $orderItemProduct OrderItemProduct
	 */
	public function remove(OrderRequest $orderRequest, OrderItemProduct $orderItemProduct): void
	{
		$this->validateOrderRequest($orderRequest);

		if (!$this->orderItemProductDAO->exist($orderRequest, $orderItemProduct->getProduct()))
			throw OrderItemProductException::newBinded();

		if (!$this->orderItemProductDAO->delete($orderItemProduct))
			throw OrderItemProductException::newDeleted();
	}

	/**
	 * @param $orderRequest OrderRequest
	 */
	public function removeAll(OrderRequest $orderRequest): void
	{
		$this->validateOrderRequest($orderRequest);

		if (!$this->orderItemProductDAO->deleteAll($orderRequest))
			throw OrderItemProductException::newDeletedAll();
	}

	/**
	 *
	 * @param int $idOrderItemProduct
	 * @param int|NULL $idOrderRequest
	 * @throws OrderItemProductException
	 * @return OrderItemProducts
	 */
	public function get(int $idOrderItemProduct, ?int $idOrderRequest = null): OrderItemProduct
	{
		if ($idOrderRequest === null) {
			if (($orderItemProduct = $this->orderItemProductDAO->select($idOrderItemProduct)) === null)
				throw OrderItemProductException::newSelected();
		} else {
			if (($orderItemProduct = $this->orderItemProductDAO->selectWithOrderRequest($idOrderRequest, $idOrderItemProduct)) === null)
				throw OrderItemProductException::newSelected();
		}

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

