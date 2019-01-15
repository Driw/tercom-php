<?php

namespace tercom\control;

use tercom\dao\QuotedOrderServiceDAO;
use tercom\entities\OrderItemService;
use tercom\entities\QuotedOrderService;
use tercom\entities\TercomEmployee;
use tercom\entities\lists\QuotedOrderServices;
use tercom\exceptions\QuotedOrderServiceException;
use tercom\entities\OrderRequest;
use tercom\TercomException;

/**
 * @see QuotedOrderServiceDAO
 *
 * @author Andrew
 */
class QuotedOrderServiceControl extends GenericControl
{
	/**
	 * @var QuotedOrderServiceDAO
	 */
	private $quotedOrderServiceDAO;
	/**
	 * @var QuotedServicePriceControl
	 */
	private $quotedServicePriceControl;

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
		$this->quotedOrderServiceDAO = new QuotedOrderServiceDAO();
		$this->quotedServicePriceControl = new QuotedServicePriceControl();
	}

	/**
	 *
	 * @param QuotedOrderService $quotedOrderService
	 * @throws QuotedOrderServiceException
	 */
	public function add(QuotedOrderService $quotedOrderService): void
	{
		if (!$this->quotedOrderServiceDAO->insert($quotedOrderService))
			throw QuotedOrderServiceException::newInserted();
	}

	/**
	 *
	 * @param QuotedOrderService $quotedOrderService
	 * @throws QuotedOrderServiceException
	 */
	public function remove(QuotedOrderService $quotedOrderService): void
	{
		if (!$this->quotedOrderServiceDAO->delete($quotedOrderService))
			throw QuotedOrderServiceException::newDeletedAll();
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @param OrderItemService $orderItemService
	 */
	public function removeAll(OrderRequest $orderRequest, OrderItemService $orderItemService): void
	{
		if (!$this->quotedOrderServiceDAO->deleteAll($orderRequest, $orderItemService))
			throw QuotedOrderServiceException::newDeletedAll();
	}

	/**
	 *
	 * @param int $idQuotedOrderService
	 * @param OrderRequest $orderRequest
	 * @return QuotedOrderService
	 */
	public function get(int $idQuotedOrderService, OrderRequest $orderRequest): QuotedOrderService
	{
		if (($quotedOrderService = $this->quotedOrderServiceDAO->select($idQuotedOrderService)) === null)
			throw QuotedOrderServiceException::newSelected();

		if (!$this->quotedOrderServiceDAO->existOnOrderRequest($orderRequest, $quotedOrderService))
			throw QuotedOrderServiceException::newOrderRequest();

		return $quotedOrderService;
	}

	/**
	 *
	 * @param OrderItemService $orderItemService
	 * @return QuotedOrderServices
	 */
	public function getAll(OrderItemService $orderItemService): QuotedOrderServices
	{
		return $this->quotedOrderServiceDAO->selectAll($orderItemService);
	}
}

