<?php

namespace tercom\control;

use tercom\entities\OrderRequest;
use tercom\dao\OrderRequestDAO;
use tercom\exceptions\OrderRequestException;
use tercom\entities\TercomEmployee;
use tercom\entities\CustomerEmployee;
use tercom\entities\lists\OrderRequests;
use tercom\TercomException;

/**
 * @author Andrew
 */
class OrderRequestControl extends GenericControl
{
	private $orderRequestDAO;

	public function __construct()
	{
		$this->orderRequestDAO = new OrderRequestDAO();
	}

	public function add(OrderRequest $orderRequest): void
	{
		if (!$this->orderRequestDAO->insert($orderRequest))
			throw OrderRequestException::newInserted();
	}

	public function set(OrderRequest $orderRequest): void
	{
		if (!$this->orderRequestDAO->update($orderRequest))
			throw OrderRequestException::newUpdated();
	}

	public function get(int $idOrderRequest, bool $onlyView = false): OrderRequest
	{
		if (($orderRequest = $this->orderRequestDAO->select($idOrderRequest)) === null)
			throw OrderRequestException::newSelected();

		if (!$onlyView)
		{
			switch ($orderRequest->getStatus())
			{
				case OrderRequest::ORS_CANCEL_BY_CUSTOMER: throw OrderRequestException::newCanceledByCustomer();
				case OrderRequest::ORS_CANCEL_BY_TERCOM: throw OrderRequestException::newCanceledByTercom();
			}
		}

		return $orderRequest;
	}

	public function getWithCustomerEmployee(CustomerEmployee $customerEmployee, int $idOrderRequest): OrderRequest
	{
		$orderRequest = $this->get($idOrderRequest);

		if ($this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		$customerEmployee = $this->getCustomerLogged();

		if ($customerEmployee->getId() !== $orderRequest->getCustomerEmployeeId())
			throw OrderRequestException::newCustomerEmployeeError();

		$orderRequest->setCustomerEmployee($customerEmployee);

		return $orderRequest;
	}

	public function getWithTercomEmployee(TercomEmployee $tercomEmployee, int $idOrderRequest): OrderRequest
	{
		$orderRequest = $this->get($idOrderRequest);

		if (!$this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		return $orderRequest;
	}

	public function getAll(int $mode): OrderRequests
	{
		if ($this->isTercomManagement())
			return $this->orderRequestDAO->selectAll($mode);

		return $this->orderRequestDAO->selectAllByCustomer($this->getCustomerLoggedId(), $mode);
	}

	public function getByCustomerEmployee(CustomerEmployee $customerEmployee, int $mode): OrderRequests
	{
		if (!$this->isTercomManagement())
			if ($customerEmployee->getCustomerProfile()->getCustomerId() !== $this->getCustomerLoggedId())
				throw TercomException::newCustomerInvliad();

		return $this->orderRequestDAO->selectByCustomerEmployee($customerEmployee, $mode);
	}

	public function getByTercomEmployee(TercomEmployee $tercomEmployee, int $mode): OrderRequests
	{
		if (!$this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		return $this->orderRequestDAO->selectByTercomEmployee($tercomEmployee, $mode);
	}

	public function cancelByCustomer(CustomerEmployee $customerEmployee, OrderRequest $orderRequest): void
	{
		if ($customerEmployee->getId() !== $orderRequest->getCustomerEmployeeId())
			throw OrderRequestException::newCustomerEmployeeError();

		$orderRequest->setStatus(OrderRequest::ORS_CANCEL_BY_CUSTOMER);
		$orderRequest->setStatusMessage(format('cancelado por "%s"', $customerEmployee->getName()));

		if (!$this->orderRequestDAO->update($orderRequest))
			throw OrderRequestException::newUpdated();
	}

	public function cancelByTercom(TercomEmployee $tercomEmployee, OrderRequest $orderRequest): void
	{
		if ($tercomEmployee->getId() !== $orderRequest->getTercomEmployeeId())
			throw OrderRequestException::newTercomEmployeeError();

		$orderRequest->setStatus(OrderRequest::ORS_CANCEL_BY_TERCOM);
		$orderRequest->setStatusMessage(format('cancelado por "%s"', $tercomEmployee->getName()));

		if (!$this->orderRequestDAO->update($orderRequest))
			throw OrderRequestException::newUpdated();
	}

	public function setCustomerEmployee(CustomerEmployee $currentCustomerEmployee, CustomerEmployee $newCustomerEmployee, OrderRequest $orderRequest): void
	{
		if ($orderRequest->getCustomerEmployeeId() !== $currentCustomerEmployee->getId())
			throw OrderRequestException::newCustomerEmployeeError();

		$orderRequest->setCustomerEmployee($newCustomerEmployee);

		if (!$this->orderRequestDAO->update($orderRequest))
			throw OrderRequestException::newUpdated();
	}

	public function setTercomEmployee(TercomEmployee $currentTercomEmployee, TercomEmployee $newTercomEmployee, OrderRequest $orderRequest): void
	{
		if ($orderRequest->getTercomEmployeeId() !== $currentTercomEmployee->getId())
			throw OrderRequestException::newTercomEmployeeError();

		$orderRequest->setTercomEmployee($newTercomEmployee);

		if (!$this->orderRequestDAO->update($orderRequest))
			throw OrderRequestException::newUpdated();
	}

	public function setQueued(CustomerEmployee $customerEmployee, OrderRequest $orderRequest): void
	{
		if ($customerEmployee->getId() !== $orderRequest->getCustomerEmployeeId())
			throw OrderRequestException::newCustomerEmployeeError();

		$orderRequest->setStatus(OrderRequest::ORS_QUEUED);

		if (!$this->orderRequestDAO->update($orderRequest))
			throw OrderRequestException::newUpdated();
	}

	public function setQuoting(TercomEmployee $tercomEmployee, OrderRequest $orderRequest): void
	{
		if ($orderRequest->getTercomEmployeeId() !== 0)
			throw OrderRequestException::newTercomEmployeeSetted();

		if ($orderRequest->getStatus() !== OrderRequest::ORS_QUEUED)
			throw OrderRequestException::newNotQueued();

		$orderRequest->setStatus(OrderRequest::ORS_QUOTING);

		if (!$this->orderRequestDAO->update($orderRequest))
			throw OrderRequestException::newUpdated();
	}

	public function setQuoted(TercomEmployee $tercomEmployee, OrderRequest $orderRequest): void
	{
		if ($orderRequest->getTercomEmployeeId() !== $tercomEmployee->getId())
			throw OrderRequestException::newTercomEmployeeError();

		$orderRequest->setStatus(OrderRequest::ORS_QUOTED);

		if (!$this->orderRequestDAO->update($orderRequest))
			throw OrderRequestException::newUpdated();
	}

	public function setDone(CustomerEmployee $customerEmployee, OrderRequest $orderRequest): void
	{
		if ($orderRequest->getTercomEmployeeId() !== $customerEmployee->getId())
			throw OrderRequestException::newCustomerEmployeeError();

		$orderRequest->setStatus(OrderRequest::ORS_DONE);

		if (!$this->orderRequestDAO->update($orderRequest))
			throw OrderRequestException::newUpdated();
	}
}

