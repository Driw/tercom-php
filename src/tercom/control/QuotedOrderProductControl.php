<?php

namespace tercom\control;

use tercom\dao\QuotedOrderProductDAO;
use tercom\entities\OrderItemProduct;
use tercom\entities\QuotedOrderProduct;
use tercom\entities\TercomEmployee;
use tercom\entities\lists\QuotedOrderProducts;
use tercom\exceptions\QuotedOrderProductException;
use tercom\entities\OrderRequest;
use tercom\TercomException;

/**
 * @see QuotedOrderProductDAO
 *
 * @author Andrew
 */
class QuotedOrderProductControl extends GenericControl
{
	/**
	 * @var QuotedOrderProductDAO
	 */
	private $quotedOrderProductDAO;
	/**
	 * @var QuotedProductPriceControl
	 */
	private $quotedProductPriceControl;

	/**
	 *
	 */
	public function validateManagement(OrderRequest $orderRequest, TercomEmployee $tercomEmployee)
	{
		if (!$this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		if ($orderRequest->getTercomEmployeeId() !== $tercomEmployee->getId())
			throw TercomException::newResponsability();
	}

	/**
	 *
	 */
	public function __construct()
	{
		$this->quotedOrderProductDAO = new QuotedOrderProductDAO();
		$this->quotedProductPriceControl = new QuotedProductPriceControl();
	}

	/**
	 *
	 * @param QuotedOrderProduct $quotedOrderProduct
	 * @throws QuotedOrderProductException
	 */
	public function add(QuotedOrderProduct $quotedOrderProduct): void
	{
		if (!$this->quotedOrderProductDAO->insert($quotedOrderProduct))
			throw QuotedOrderProductException::newInserted();
	}

	/**
	 *
	 * @param QuotedOrderProduct $quotedOrderProduct
	 * @throws QuotedOrderProductException
	 */
	public function remove(QuotedOrderProduct $quotedOrderProduct): void
	{
		if (!$this->quotedOrderProductDAO->delete($quotedOrderProduct))
			throw QuotedOrderProductException::newDeletedAll();
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @param OrderItemProduct $orderItemProduct
	 */
	public function removeAll(OrderRequest $orderRequest, OrderItemProduct $orderItemProduct): void
	{
		if (!$this->quotedOrderProductDAO->deleteAll($orderRequest, $orderItemProduct))
			throw QuotedOrderProductException::newDeletedAll();
	}

	/**
	 *
	 * @param int $idQuotedOrderProduct
	 * @param OrderRequest $orderRequest
	 * @return QuotedOrderProduct
	 */
	public function get(int $idQuotedOrderProduct, OrderRequest $orderRequest): QuotedOrderProduct
	{
		if (($quotedOrderProduct = $this->quotedOrderProductDAO->select($idQuotedOrderProduct)) === null)
			throw QuotedOrderProductException::newSelected();

		if (!$this->quotedOrderProductDAO->existOnOrderRequest($orderRequest, $quotedOrderProduct))
			throw QuotedOrderProductException::newOrderRequest();

		return $quotedOrderProduct;
	}

	/**
	 *
	 * @param OrderItemProduct $orderItemProduct
	 * @return QuotedOrderProducts
	 */
	public function getAll(OrderItemProduct $orderItemProduct): QuotedOrderProducts
	{
		return $this->quotedOrderProductDAO->selectAll($orderItemProduct);
	}
}

