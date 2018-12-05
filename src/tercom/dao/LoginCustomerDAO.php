<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\CustomerEmployee;
use tercom\entities\LoginCustomer;

/**
 * @see GenericDAO
 * @author Andrew
 */
class LoginCustomerDAO extends GenericDAO
{
	/**
	 *
	 * @param LoginCustomer $loginCustomer
	 * @return bool
	 */
	public function insert(LoginCustomer $loginCustomer): bool
	{
		$sql = "INSERT INTO logins_customer (idLogin, idCustomerEmployee)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $loginCustomer->getId());
		$query->setInteger(2, $loginCustomer->getCustomerEmployeeId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param LoginCustomer $loginCustomer
	 * @return int
	 */
	public function updateLogouts(LoginCustomer $loginCustomer): int
	{
		$sql = "UPDATE logins
				INNER JOIN logins_customer ON logins_customer.idLogin = logins.id
				SET logins.logout = ?
				WHERE logins_customer.idLogin <> ? AND logins_customer.idCustomerEmployee = ?";

		$query = $this->createQuery($sql);
		$query->setBoolean(1, true);
		$query->setInteger(2, $loginCustomer->getId());
		$query->setInteger(3, $loginCustomer->getCustomerEmployeeId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @return string
	 */
	private function newSelect(): string
	{
		$loginColumns = $this->buildQuery(LoginDAO::ALL_COLUMNS, 'logins');
		$customerEmployeeColumns = $this->buildQuery(CustomerEmployeeDAO::ALL_COLUMNS, 'customer_employees', 'customerEmployee');

		return "SELECT $loginColumns, $customerEmployeeColumns
				FROM logins_customer
				INNER JOIN logins ON logins.id = logins_customer.idLogin
				INNER JOIN customer_employees ON customer_employees.id = logins_customer.idCustomerEmployee";
	}

	/**
	 *
	 * @param int $idLogin
	 * @param int $idCustomerEmployee
	 * @return LoginCustomer|NULL
	 */
	public function select(int $idLogin, int $idCustomerEmployee): ?LoginCustomer
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE logins_customer.idLogin = ? AND logins_customer.idCustomerEmployee = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idLogin);
		$query->setInteger(2, $idCustomerEmployee);

		$result = $query->execute();

		return $this->parseLoginCustomer($result);
	}

	/**
	 *
	 * @param Result $result
	 * @return LoginCustomer|NULL
	 */
	private function parseLoginCustomer(Result $result): ?LoginCustomer
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newLoginCustomer($entry);
	}

	/**
	 *
	 * @param array $entry
	 * @return LoginCustomer
	 */
	private function newLoginCustomer(array $entry): LoginCustomer
	{
		$customerEmployeeEntry = $this->parseArrayJoin($entry, 'customerEmployee');

		$customerEmployee = new CustomerEmployee();
		$customerEmployee->fromArray($customerEmployeeEntry);

		$loginCustomer = new LoginCustomer();
		$loginCustomer->fromArray($entry);
		$loginCustomer->setCustomerEmployee($customerEmployee);

		return $loginCustomer;
	}
}

