<?php

namespace tercom\dao;

use tercom\entities\OrderRequest;
use tercom\entities\lists\OrderRequests;
use tercom\entities\TercomEmployee;
use tercom\entities\CustomerEmployee;
use tercom\exceptions\OrderRequestException;
use dProject\MySQL\Result;

/**
 * @author Andrew
 */
class OrderRequestDAO extends GenericDAO
{
	public const ALL_COLUMNS = ['id', 'idCustomerEmployee', 'idTercomEmployee', 'budget', 'expiration', 'register'];
	public const SELECT_MODE_ALL = 0;
	public const SELECT_MODE_CUSTOMER_CANCEL = 1;
	public const SELECT_MODE_TERCOM_CANCEL = 2;

	private function validate(OrderRequest $orderRequest, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($orderRequest->getId() === 0)
				throw OrderRequestException::newNotIdentified();
		} else {
			if ($orderRequest->getId() !== 0)
				throw OrderRequestException::newIdentified();
		}

		// FOREIGN KEY
		if ($orderRequest->getCustomerEmployeeId() === 0) throw OrderRequestException::newCustomerEmployeeEmpty();
		if (!$this->existCustomerEmployee($orderRequest->getCustomerEmployeeId())) throw OrderRequestException::newCustomerEmployee();
		if ($orderRequest->getTercomEmployeeId() != 0 && !$this->existTercomEmployee($orderRequest->getTercomEmployeeId())) throw OrderRequestException::newCustomerEmployee();
	}

	public function insert(OrderRequest $orderRequest): bool
	{
		$this->validate($orderRequest, false);

		$sql = 'INSERT INTO order_requests (idCustomerEmployee, idTercomEmployee, budget, expiration, register)
				VALUES (?, ?, ?, ?, ?)';

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getCustomerEmployeeId());
		$query->setInteger(2, $this->parseNullID($orderRequest->getTercomEmployeeId()));
		$query->setFloat(3, $orderRequest->getBudget());
		$query->setDateTime(4, $orderRequest->getExpiration());
		$query->setDateTime(5, $orderRequest->getRegister());

		if (($result = $query->execute())->isSuccessful())
			$orderRequest->setId($result->getInsertID());

		return $orderRequest->getId() !== 0;
	}

	public function update(OrderRequest $orderRequest): bool
	{
		$this->validate($orderRequest, true);

		$sql = 'UPDATE order_requests
				SET idCustomerEmployee = ?, idTercomEmployee = ?, budget = ?, expiration = ?
				WHERE id = ?';

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getCustomerEmployeeId());
		$query->setInteger(2, $this->parseNullID($orderRequest->getTercomEmployeeId()));
		$query->setFloat(3, $orderRequest->getBudget());
		$query->setDateTime(4, $orderRequest->getExpiration());
		$query->setInteger(5, $orderRequest->getId());

		return ($query->execute())->isSuccessful();
	}

	private function newSelect(): string
	{
		return "SELECT id, idCustomerEmployee customerEmployee_id, idTercomEmployee tercomEmployee_id, budget, expiration, register
				FROM order_requests";
	}

	private function newAndStatus(int $mode): string
	{
		switch ($mode)
		{
			case self::SELECT_MODE_CUSTOMER_CANCEL: return sprintf('status = %d', OrderRequest::ORS_CANCEL_BY_CUSTOMER);
			case self::SELECT_MODE_TERCOM_CANCEL: return sprintf('status = %d', OrderRequest::ORS_CANCEL_BY_TERCOM);
		}

		return 'status IS NOT NULL';
	}

	public function select(int $idOrderRequest): ?OrderRequest
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idOrderRequest);

		$result = $query->execute();

		return $this->parseOrderRequest($result);
	}

	public function selectAll(int $mode): OrderRequests
	{
		$sqlSelect = $this->newSelect();
		$sqlAndStatus = $this->newAndStatus($mode);
		$sql = "$sqlSelect
				WHERE $sqlAndStatus";

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseOrderRequests($result);
	}

	public function selectByCustomerEmployee(CustomerEmployee $customerEmployee, int $mode): OrderRequests
	{
		$sqlSelect = $this->newSelect();
		$sqlAndStatus = $this->newAndStatus($mode);
		$sql = "$sqlSelect
				WHERE idCustomerEmployee = ? AND $sqlAndStatus";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerEmployee->getId());

		$result = $query->execute();

		return $this->parseOrderRequests($result);
	}

	public function selectByTercomEmployee(TercomEmployee $tercomEmployee, int $mode): OrderRequests
	{
		$sqlSelect = $this->newSelect();
		$sqlAndStatus = $this->newAndStatus($mode);
		$sql = "$sqlSelect
				WHERE idTercomEmployee = ? AND $sqlAndStatus";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomEmployee->getId());

		$result = $query->execute();

		return $this->parseOrderRequests($result);
	}

	public function existCustomerEmployee(int $idCustomerEmployee): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_employees
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idCustomerEmployee);

		return $this->parseQueryExist($query);
	}

	public function existTercomEmployee(int $idTercomEmployee): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_employees
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idTercomEmployee);

		return $this->parseQueryExist($query);
	}

	private function parseOrderRequest(Result $result): ?OrderRequest
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newOrderRequest($entry);
	}

	private function parseOrderRequests(Result $result): OrderRequests
	{
		$orderRequests = new OrderRequests();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$orderRequest = $this->newOrderRequest($entry);
			$orderRequests->add($orderRequest);
		}

		return $orderRequests;
	}

	private function newOrderRequest(array $entry): OrderRequest
	{
		$this->parseEntry($entry, 'customerEmployee', 'tercomEmployee');

		$orderRequest = new OrderRequest();
		$orderRequest->fromArray($entry);

		return $orderRequest;
	}
}

