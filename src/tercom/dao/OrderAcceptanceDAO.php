<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\Address;
use tercom\entities\Customer;
use tercom\entities\CustomerEmployee;
use tercom\entities\OrderAcceptance;
use tercom\entities\OrderQuote;
use tercom\entities\TercomEmployee;
use tercom\entities\lists\OrderAcceptances;
use tercom\exceptions\OrderAcceptanceException;

/**
 *
 *
 * @see GenericDAO
 * @see OrderAcceptance
 * @see OrderQuote
 * @see Address
 * @see Customer
 * @see CustomerEmployee
 * @see TercomEmployee
 *
 * @author Andrew
 */
class OrderAcceptanceDAO extends GenericDAO
{
	/**
	 * @var array
	 */
	public const ALL_COLUMNS = ['id', 'idOrderQuote', 'idCustomerEmployee', 'idTercomEmployee', 'idAddress', 'status', 'observations', 'register'];

	/**
	 *
	 * @param OrderAcceptance $orderAcceptance
	 * @param bool $validateId
	 * @throws OrderAcceptanceException
	 */
	private function validate(OrderAcceptance $orderAcceptance, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($orderAcceptance->getId() === 0)
				throw OrderAcceptanceException::newNotIdentified();
		} else {
			if ($orderAcceptance->getId() !== 0)
				throw OrderAcceptanceException::newIdentified();
		}

		// UNIQUE KEY
		if (!$validateId)
		{
			if ($this->existOrderQuoteUnique($orderAcceptance->getOrderQuote())) throw OrderAcceptanceException::newQuoteExist();
		}

		// NOT NULL
		if ($orderAcceptance->getOrderQuoteId() === 0) throw OrderAcceptanceException::newOrderEmpty();
		if ($orderAcceptance->getCustomerEmployeeId() === 0) throw OrderAcceptanceException::newCustomerEmpty();
		if ($orderAcceptance->getCustomerEmployeeId() === 0) throw OrderAcceptanceException::newTercomEmpty();
		if ($orderAcceptance->getAddressId() === 0) throw OrderAcceptanceException::newAddressEmpty();

		// FOREIGN KEY
		if (!$this->existOrderQuote($orderAcceptance->getOrderQuote())) throw OrderAcceptanceException::newOrderInvalid();
		if (!$this->existCustomerEmployee($orderAcceptance->getCustomerEmployee())) throw OrderAcceptanceException::newCustomerInvalid();
		if (!$this->existTercomEmployee($orderAcceptance->getTercomEmployee())) throw OrderAcceptanceException::newTercomInvalid();
		if (!$this->existAddress($orderAcceptance->getAddress())) throw OrderAcceptanceException::newAddressInvalid();
	}

	/**
	 *
	 * @param OrderAcceptance $orderAcceptance
	 * @return bool
	 */
	public function insert(OrderAcceptance $orderAcceptance): bool
	{
		$this->validate($orderAcceptance, false);
		$orderAcceptance->setRegisterCurrent();

		$sql = "INSERT INTO order_acceptances (idOrderQuote, idCustomerEmployee, idTercomEmployee, idAddress, status, observations, register)
				VALUES (?, ?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $orderAcceptance->getOrderQuoteId());
		$query->setInteger(2, $orderAcceptance->getCustomerEmployeeId());
		$query->setInteger(3, $orderAcceptance->getTercomEmployeeId());
		$query->setInteger(4, $orderAcceptance->getAddressId());
		$query->setInteger(5, $orderAcceptance->getStatus());
		$query->setString(6, $orderAcceptance->getObservations());
		$query->setDateTime(7, $orderAcceptance->getRegister());

		if (($result = $query->execute())->isSuccessful())
			$orderAcceptance->setId($result->getInsertID());

		return $orderAcceptance->getId() !== 0;
	}

	/**
	 *
	 * @param OrderAcceptance $orderAcceptance
	 * @return bool
	 */
	public function update(OrderAcceptance $orderAcceptance): bool
	{
		$this->validate($orderAcceptance, true);

		$sql = "UPDATE order_acceptances
				SET idAddress = ?, status = ?, observations = ?, register = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $orderAcceptance->getAddressId());
		$query->setInteger(2, $orderAcceptance->getStatus());
		$query->setString(3, $orderAcceptance->getObservations());
		$query->setDateTime(4, $orderAcceptance->getRegister());
		$query->setInteger(5, $orderAcceptance->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param OrderAcceptance $orderAcceptance
	 * @return bool
	 */
	public function updateStatus(OrderAcceptance $orderAcceptance): bool
	{
		$this->validate($orderAcceptance, true);

		$sql = "UPDATE order_acceptances
				SET status = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $orderAcceptance->getStatus());
		$query->setInteger(2, $orderAcceptance->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @return string
	 */
	private function newSelectBase(): string
	{
		$orderAcceptanceColumns = $this->buildQuery(self::ALL_COLUMNS, 'order_acceptances');
		$orderQuoteColumns = $this->buildQuery(OrderQuoteDAO::ALL_COLUMNS, 'order_quotes', 'orderQuote');
		$addressColumns = $this->buildQuery(AddressDAO::ALL_COLUMNS, 'addresses', 'address');
		$customerEmployeeColumns = $this->buildQuery(CustomerEmployeeDAO::ALL_PROFILE_COLUMNS, 'customer_employees', 'customerEmployee');
		$tercomEmployeeColumns = $this->buildQuery(TercomEmployeeDAO::ALL_PROFILE_COLUMNS, 'tercom_employees', 'tercomEmployee');

		return "SELECT $orderAcceptanceColumns, $orderQuoteColumns, $addressColumns, $customerEmployeeColumns, $tercomEmployeeColumns
				FROM order_acceptances
				INNER JOIN order_quotes ON order_quotes.id = order_acceptances.idOrderQuote
				INNER JOIN addresses ON addresses.id = order_acceptances.idAddress
				INNER JOIn order_requests ON order_requests.id = order_quotes.idOrderRequest
				INNER JOIN customer_employees ON customer_employees.id = order_requests.idCustomerEmployee
				INNER JOIN tercom_employees ON tercom_employees.id = order_requests.idTercomEmployee";
	}

	/**
	 *
	 * @return string
	 */
	private function newSelectFull(): string
	{
		$orderAcceptanceColumns = $this->buildQuery(self::ALL_COLUMNS, 'order_acceptances');
		$orderQuoteColumns = $this->buildQuery(OrderQuoteDAO::ALL_COLUMNS, 'order_quotes', 'orderQuote');
		$addressColumns = $this->buildQuery(AddressDAO::ALL_COLUMNS, 'addresses', 'address');
		$customerEmployeeColumns = $this->buildQuery(CustomerEmployeeDAO::ALL_PROFILE_COLUMNS, 'customer_employees', 'customerEmployee');
		$tercomEmployeeColumns = $this->buildQuery(TercomEmployeeDAO::ALL_PROFILE_COLUMNS, 'tercom_employees', 'tercomEmployee');
		$customerProfileColumns = $this->buildQuery(CustomerProfileDAO::ALL_COLUMNS, 'customer_profiles', 'customerEmployee_customerProfile');
		$customerColumns = $this->buildQuery(CustomerDAO::ALL_COLUMNS, 'customers', 'customerEmployee_customerProfile_customer');

		return "SELECT $orderAcceptanceColumns, $orderQuoteColumns, $addressColumns, $customerEmployeeColumns, $tercomEmployeeColumns,
					$customerProfileColumns, $customerColumns
				FROM order_acceptances
				INNER JOIN order_quotes ON order_quotes.id = order_acceptances.idOrderQuote
				INNER JOIN addresses ON addresses.id = order_acceptances.idAddress
				INNER JOIn order_requests ON order_requests.id = order_quotes.idOrderRequest
				INNER JOIN customer_employees ON customer_employees.id = order_requests.idCustomerEmployee
				INNER JOIN tercom_employees ON tercom_employees.id = order_requests.idTercomEmployee
				INNER JOIN customer_profiles ON customer_profiles.id = customer_employees.idCustomerProfile
				INNER JOIN customers ON customers.id = customer_profiles.idCustomer";
	}

	/**
	 *
	 * @param int $idOrderAcceptance
	 * @return OrderAcceptance|NULL
	 */
	public function select(int $idOrderAcceptance): ?OrderAcceptance
	{
		$sqlSelect = $this->newSelectFull();
		$sql = "$sqlSelect
				WHERE order_acceptances.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idOrderAcceptance);

		$result = $query->execute();

		return $this->parseOrderAcceptance($result);
	}

	/**
	 *
	 * @param OrderQuote $orderAcceptance
	 * @return OrderAcceptance|NULL
	 */
	public function selectByOrderQuote(OrderQuote $orderQuote): ?OrderAcceptance
	{
		$sqlSelect = $this->newSelectFull();
		$sql = "$sqlSelect
				WHERE order_acceptances.idOrderQuote = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderQuote->getId());

		$result = $query->execute();

		return $this->parseOrderAcceptance($result);
	}

	/**
	 *
	 * @param Customer $customer
	 * @return OrderAcceptances
	 */
	public function selectByCustomer(Customer $customer): OrderAcceptances
	{
		$sqlSelect = $this->newSelectBase();
		$sql = "$sqlSelect
				INNER JOIN customer_profiles ON customer_profiles.id = customer_employees.idCustomerProfile
				WHERE customer_profiles.idCustomer = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());

		$result = $query->execute();

		return $this->parseOrderAcceptances($result);
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmployee
	 * @return OrderAcceptances
	 */
	public function selectByCustomerEmployee(CustomerEmployee $customerEmployee): OrderAcceptances
	{
		$sqlSelect = $this->newSelectBase();
		$sql = "$sqlSelect
				WHERE order_acceptances.idCustomerEmployee = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerEmployee->getId());

		$result = $query->execute();

		return $this->parseOrderAcceptances($result);
	}

	/**
	 *
	 * @param TercomEmployee $tercomEmployee
	 * @return OrderAcceptances
	 */
	public function selectByTercomEmployee(TercomEmployee $tercomEmployee): OrderAcceptances
	{
		$sqlSelect = $this->newSelectBase();
		$sql = "$sqlSelect
				WHERE order_acceptances.idTercomEmployee = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomEmployee->getId());

		$result = $query->execute();

		return $this->parseOrderAcceptances($result);
	}

	/**
	 *
	 * @return OrderAcceptances
	 */
	public function selectAll(): OrderAcceptances
	{
		$sql = $this->newSelect();
		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseOrderAcceptances($result);
	}

	/**
	 *
	 * @param OrderQuote $orderQuote
	 * @return bool
	 */
	private function existOrderQuoteUnique(OrderQuote $orderQuote): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM order_acceptances
				WHERE idOrderQuote = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderQuote->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 *
	 * @param OrderQuote $orderAcceptance
	 * @return bool
	 */
	private function existOrderQuote(OrderQuote $orderQuote): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM order_quotes
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderQuote->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmployee
	 * @return bool
	 */
	private function existCustomerEmployee(CustomerEmployee $customerEmployee): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_employees
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerEmployee->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 *
	 * @param TercomEmployee $tercomEmployee
	 * @return bool
	 */
	private function existTercomEmployee(TercomEmployee $tercomEmployee): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_employees
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomEmployee->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 *
	 * @param Address $address
	 * @return bool
	 */
	private function existAddress(Address $address): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM addresses
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $address->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 *
	 * @param Result $result
	 * @return OrderAcceptance|NULL
	 */
	private function parseOrderAcceptance(Result $result): ?OrderAcceptance
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newOrderAcceptance($entry);
	}

	/**
	 *
	 * @param Result $result
	 * @return OrderAcceptances|NULL
	 */
	private function parseOrderAcceptances(Result $result): ?OrderAcceptances
	{
		$orderAcceptances = new OrderAcceptances();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$orderAcceptance = $this->newOrderAcceptance($entry);
			$orderAcceptances->add($orderAcceptance);
		}

		return $orderAcceptances;
	}

	/**
	 *
	 * @param array $entry
	 * @return OrderAcceptance
	 */
	private function newOrderAcceptance(array $entry): OrderAcceptance
	{
		$this->parseEntry($entry, 'orderQuote', 'address', 'customerEmployee', 'tercomEmployee');
		if (isset($entry['customerEmployee']))
		$this->parseEntry($entry['customerEmployee'], 'customerProfile');
		if (isset($entry['customerEmployee']['customerProfile']))
		$this->parseEntry($entry['customerEmployee']['customerProfile'], 'customer');

		$orderAcceptance = new OrderAcceptance();
		$orderAcceptance->fromArray($entry);

		return $orderAcceptance;
	}
}

