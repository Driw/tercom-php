<?php

namespace tercom\control;

use tercom\dao\OrderItemServiceDAO;
use tercom\entities\OrderItemService;
use tercom\entities\OrderRequest;
use tercom\entities\Service;
use tercom\entities\lists\OrderItemServices;
use tercom\exceptions\OrderItemServiceException;
use tercom\TercomException;

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
	 * @param OrderItemService $orderItemService
	 * @throws OrderItemServiceException
	 */
	public function set(OrderItemService $orderItemService): void
	{
		if (!$this->orderItemServiceDAO->update($orderItemService))
			throw OrderItemServiceException::newUpdated();
	}

	/**
	 * @param $orderItemService OrderItemService
	 */
	public function remove(OrderItemService $orderItemService): void
	{
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
	 * @param bool $validateCustomer
	 * @throws OrderItemServiceException
	 * @return OrderItemServices
	 */
	public function get(int $idOrderItemService, bool $validateCustomer = false): OrderItemService
	{
		if ($validateCustomer) {

			if (!$this->hasCustomerLogged())
				throw TercomException::newCustomerInvliad();

			if (($orderItemService = $this->orderItemServiceDAO->selectWithCustomerEmployee($idOrderItemService, $this->getCustomerLoggedId())) === null)
				throw OrderItemServiceException::newDeleted();

		} else {
			if (($orderItemService = $this->orderItemServiceDAO->select($idOrderItemService)) === null)
				throw OrderItemServiceException::newDeleted();
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

