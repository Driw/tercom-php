<?php

namespace tercom\control;

use tercom\entities\OrderRequest;
use tercom\dao\OrderRequestDAO;
use tercom\exceptions\OrderRequestException;
use tercom\entities\TercomEmployee;
use tercom\entities\CustomerEmployee;
use tercom\entities\lists\OrderRequests;

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

	public function get(int $idOrderRequest, ?CustomerEmployee $customerEmployee = null): OrderRequest
	{
		if (($orderRequest = $this->orderRequestDAO->select($idOrderRequest)) === null)
			throw OrderRequestException::newSelected();

		if ($customerEmployee !== null)
		{
			if ($customerEmployee->getId() !== $orderRequest->getCustomerEmployeeId())
				throw OrderRequestException::newCustomerInvalid();

			$orderRequest->setCustomerEmployee($customerEmployee);
		}

		return $orderRequest;
	}

	public function getWithCustomerEmployee(CustomerEmployee $customerEmployee, int $idOrderRequest): OrderRequest
	{
		if (($orderRequest = $this->orderRequestDAO->select($idOrderRequest)) === null)
			throw OrderRequestException::newSelected();

			if (!$this->isTercomManagement())
			{
				$customerEmployee = $this->getCustomerLogged();

				if ($customerEmployee !== null && $customerEmployee->getId() !== $orderRequest->getCustomerEmployeeId())
					throw OrderRequestException::newCustomerInvalid();

					if ($customerEmployee !== null)
						$orderRequest->setCustomerEmployee($customerEmployee);
			}

			return $orderRequest;
	}

	public function getAll(int $mode): OrderRequests
	{
		return $this->orderRequestDAO->selectAll($mode);
	}

	public function getByCustomerEmployee(CustomerEmployee $customerEmployee, int $mode): OrderRequests
	{
		return $this->orderRequestDAO->selectByCustomerEmployee($customerEmployee, $mode);
	}

	public function getByTercomEmployee(TercomEmployee $tercomEmployee, int $mode): OrderRequests
	{
		return $this->orderRequestDAO->selectByTercomEmployee($tercomEmployee, $mode);
	}
}

