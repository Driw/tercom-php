<?php

namespace tercom\control;

use tercom\entities\OrderRequest;
use tercom\entities\OrderItemService;
use tercom\entities\Service;
use tercom\dao\OrderItemServiceDAO;
use tercom\entities\lists\OrderItemServices;
use tercom\exceptions\OrderItemServiceException;

/**
 *
 *
 * @see GenericControl
 * @see OrderItemService
 * @see OrderItemServices
 * @see OrderItemServiceDAO
 *
 * @author Andrew
 */
class OrderItemServiceControl extends GenericControl
{
	/**
	 * @var OrderItemServiceDAO
	 */
	private $orderItemServiceDAO;

	/**
	 *
	 */
	public function __construct()
	{
		$this->orderItemServiceDAO = new OrderItemServiceDAO();
	}

	/**
	 * @param OrderRequest $orderRequest
	 * @param OrderItemService $orderItemService
	 * @throws OrderItemServiceException
	 */
	public function add(OrderRequest $orderRequest, OrderItemService $orderItemService): void
	{
		if (!$this->orderItemServiceDAO->insert($orderRequest, $orderItemService))
			throw OrderItemServiceException::newInserted();
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @param OrderItemService $orderItemService
	 * @throws OrderItemServiceException
	 */
	public function set(OrderRequest $orderRequest, OrderItemService $orderItemService): void
	{
		if (!$this->orderItemServiceDAO->exist($orderRequest, $orderItemService->getService()))
			throw OrderItemServiceException::newBinded();

			if (!$this->orderItemServiceDAO->update($orderItemService))
				throw OrderItemServiceException::newUpdated();
	}

	/**
	 * @param $orderRequest OrderRequest
	 * @param $orderItemService OrderItemService
	 */
	public function remove(OrderRequest $orderRequest, OrderItemService $orderItemService): void
	{
		if (!$this->orderItemServiceDAO->exist($orderRequest, $orderItemService->getService()))
			throw OrderItemServiceException::newBinded();

		if (!$this->orderItemServiceDAO->delete($orderItemService))
			throw OrderItemServiceException::newDeleted();
	}

	/**
	 * @param $orderRequest OrderRequest
	 */
	public function removeAll(OrderRequest $orderRequest): void
	{
		if (!$this->orderItemServiceDAO->deleteAll($orderRequest))
			throw OrderItemServiceException::newDeletedAll();
	}

	/**
	 *
	 * @param int $idOrderItemService
	 * @param int|NULL $idOrderRequest
	 * @throws OrderItemServiceException
	 * @return OrderItemServices
	 */
	public function get(int $idOrderItemService, ?int $idOrderRequest = null): OrderItemService
	{
		if ($idOrderRequest === null) {
			if (($orderItemService = $this->orderItemServiceDAO->select($idOrderItemService)) === null)
				throw OrderItemServiceException::newSelected();
		} else {
			if (($orderItemService = $this->orderItemServiceDAO->selectWithOrderRequest($idOrderRequest, $idOrderItemService)) === null)
				throw OrderItemServiceException::newSelected();
		}

		return $orderItemService;
	}

	/**
	 * @param OrderRequest $orderRequest
	 * @return OrderItemServices
	 */
	public function getAll(OrderRequest $orderRequest): OrderItemServices
	{
		return $this->orderItemServiceDAO->selectAll($orderRequest);
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @param Service $service
	 * @return bool
	 */
	public function avaiableService(OrderRequest $orderRequest, Service $service): bool
	{
		return !$this->orderItemServiceDAO->exist($orderRequest, $service);
	}
}

