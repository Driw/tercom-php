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
	public const ALL_COLUMNS = ['id', 'idCustomerEmployee', 'idTercomEmployee', 'budget', 'status', 'expiration', 'register'];
	public const SELECT_MODE_ALL = 0;
	public const SELECT_MODE_CUSTOMER_CANCEL = 1;
	public const SELECT_MODE_TERCOM_CANCEL = 2;
	public const SELECT_MODE_CANCELED = 3;
	public const SELECT_MODE_QUEUED = 4;

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

		$sql = 'INSERT INTO order_requests (idCustomerEmployee, idTercomEmployee, status, budget, expiration, register)
				VALUES (?, ?, ?, ?, ?, ?)';

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getCustomerEmployeeId());
		$query->setInteger(2, $this->parseNullID($orderRequest->getTercomEmployeeId()));
		$query->setFloat(3, $orderRequest->getBudget());
		$query->setInteger(4, $orderRequest->getStatus());
		$query->setDateTime(5, $orderRequest->getExpiration());
		$query->setDateTime(6, $orderRequest->getRegister());

		if (($result = $query->execute())->isSuccessful())
			$orderRequest->setId($result->getInsertID());

		return $orderRequest->getId() !== 0;
	}

	public function update(OrderRequest $orderRequest): bool
	{
		$this->validate($orderRequest, true);

		$sql = 'UPDATE order_requests
				SET idCustomerEmployee = ?, idTercomEmployee = ?, budget = ?, status = ?, expiration = ?
				WHERE id = ?';

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getCustomerEmployeeId());
		$query->setInteger(2, $this->parseNullID($orderRequest->getTercomEmployeeId()));
		$query->setFloat(3, $orderRequest->getBudget());
		$query->setInteger(4, $orderRequest->getStatus());
		$query->setDateTime(5, $orderRequest->getExpiration());
		$query->setInteger(6, $orderRequest->getId());

		return ($query->execute())->isSuccessful();
	}

	private function newSelect(): string
	{
		$orderRequestColumns = $this->buildQuery(self::ALL_COLUMNS, 'order_requests');
		$customerEmployeeColumns = $this->buildQuery(CustomerEmployeeDAO::ALL_COLUMNS, 'customer_employees', 'customerEmployee');
		$tercomEmployeeColumns = $this->buildQuery(TercomEmployeeDAO::ALL_COLUMNS, 'tercom_employees', 'tercomEmployee');

		return "SELECT $orderRequestColumns, $customerEmployeeColumns, $tercomEmployeeColumns
				FROM order_requests
				INNER JOIN customer_employees ON customer_employees.id = order_requests.idCustomerEmployee
				LEFT JOIN tercom_employees ON tercom_employees.id = order_requests.idTercomEmployee";
	}

	private function newAndStatus(int $mode): string
	{
		switch ($mode)
		{
			case self::SELECT_MODE_CUSTOMER_CANCEL: return sprintf('status = %d', OrderRequest::ORS_CANCEL_BY_CUSTOMER);
			case self::SELECT_MODE_TERCOM_CANCEL: return sprintf('status = %d', OrderRequest::ORS_CANCEL_BY_TERCOM);
			case self::SELECT_MODE_CANCELED: return sprintf('status IN(%d, %d)', OrderRequest::ORS_CANCEL_BY_CUSTOMER, OrderRequest::ORS_CANCEL_BY_TERCOM);
			case self::SELECT_MODE_QUEUED: return sprintf('status = %d', OrderRequest::ORS_QUEUED);
		}

		return 'status IS NOT NULL';
	}

	public function select(int $idOrderRequest): ?OrderRequest
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_requests.id = ?";

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

	public function selectAllByCustomer(int $idCustomer, int $mode): OrderRequests
	{
		$sqlSelect = $this->newSelect();
		$sqlAndStatus = $this->newAndStatus($mode);
		$sql = "$sqlSelect
				WHERE customer_employees.id = ? AND $sqlAndStatus";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idCustomer);

		$result = $query->execute();

		return $this->parseOrderRequests($result);
	}

	public function selectByCustomerEmployee(CustomerEmployee $customerEmployee, int $mode): OrderRequests
	{
		$sqlSelect = $this->newSelect();
		$sqlAndStatus = $this->newAndStatus($mode);
		$sql = "$sqlSelect
				WHERE order_requests.idCustomerEmployee = ? AND $sqlAndStatus";

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
				WHERE order_requests.idTercomEmployee = ? AND $sqlAndStatus";

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

