<?php

namespace tercom\dao;

use tercom\entities\CustomerEmployee;
use tercom\entities\CustomerProfile;
use tercom\entities\lists\CustomerEmployees;
use dProject\MySQL\Result;
use tercom\Functions;
use dProject\Primitive\StringUtil;
use tercom\entities\Customer;

/**
 * @see GenericDAO
 * @see CustomerEmployee
 * @author Andrew
 */
class CustomerEmployeeDAO extends GenericDAO
{
	/**
	 * @var array
	 */
	public const ALL_COLUMNS = ['id', 'idCustomerProfile', 'name', 'email', 'password', 'idPhone', 'idCellPhone', 'enabled', 'register'];

	private function validate(CustomerEmployee $customerEmployee, bool $validateID)
	{
		if ($validateID) {
			if ($customerEmployee->getId() === 0)
				throw new DAOException('funcionário de cliente não identificado');
		} else {
			if ($customerEmployee->getId() !== 0)
				throw new DAOException('funcionário de cliente já identificado');
		}

		if ($customerEmployee->getCustomerProfileId() === 0) throw new DAOException('perfil não informado');
		if (StringUtil::isEmpty($customerEmployee->getName())) throw new DAOException('nome não informado');
		if (StringUtil::isEmpty($customerEmployee->getEmail())) throw new DAOException('endereço de e-mail não informado');
		if (StringUtil::isEmpty($customerEmployee->getPassword())) throw new DAOException('senha não informada');
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmplyee
	 * @return bool
	 */
	public function insert(CustomerEmployee $customerEmplyee): bool
	{
		$customerEmplyee->getRegister()->setTimestamp(time());
		$this->validate($customerEmplyee, false);

		$sql = "INSERT INTO customer_employees (idCustomerProfile, name, email, password, idPhone, idCellPhone, enabled)
				VALUES (?, ?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerEmplyee->getCustomerProfileId());
		$query->setString(2, $customerEmplyee->getName());
		$query->setString(3, $customerEmplyee->getEmail());
		$query->setString(4, $customerEmplyee->getPassword());
		$query->setInteger(5, $this->parseNullID($customerEmplyee->getPhone()->getId()));
		$query->setInteger(6, $this->parseNullID($customerEmplyee->getCellphone()->getId()));
		$query->setBoolean(7, $customerEmplyee->isEnable());

		if (($result = $query->execute())->isSuccessful())
			$customerEmplyee->setId($result->getInsertID());

		return $customerEmplyee->getId() !== 0;
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmplyee
	 * @return bool
	 */
	public function update(CustomerEmployee $customerEmplyee): bool
	{
		$this->validate($customerEmplyee, true);

		$sql = "UPDATE customer_employees
				SET idCustomerProfile = ?, name = ?, email = ?, password = ?, idPhone = ?, idCellPhone = ?, enabled = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerEmplyee->getCustomerProfileId());
		$query->setString(2, $customerEmplyee->getName());
		$query->setString(3, $customerEmplyee->getEmail());
		$query->setString(4, $customerEmplyee->getPassword());
		$query->setInteger(5, $this->parseNullID($customerEmplyee->getPhone()->getId()));
		$query->setInteger(6, $this->parseNullID($customerEmplyee->getCellphone()->getId()));
		$query->setBoolean(7, $customerEmplyee->isEnable());
		$query->setInteger(8, $customerEmplyee->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmplyee
	 * @return bool
	 */
	public function updateEnabled(CustomerEmployee $customerEmplyee): bool
	{
		$sql = "UPDATE customer_employees
				SET enabled = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setBoolean(1, $customerEmplyee->isEnable());
		$query->setInteger(2, $customerEmplyee->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @return string
	 */
	private function newSelectProfile(): string
	{
		$customerEmployeeColumns = $this->buildQuery(self::ALL_COLUMNS, 'customer_employees');
		$customerProfileColumns = $this->buildQuery(CustomerProfileDAO::ALL_COLUMNS, 'customer_profiles', 'customerProfile');

		return "SELECT $customerEmployeeColumns, $customerProfileColumns
				FROM customer_employees
				INNER JOIN customer_profiles ON customer_profiles.id = customer_employees.idCustomerProfile";
	}

	/**
	 *
	 * @param int $email
	 * @return CustomerEmployee
	 */
	public function select(int $idCustomerEmployee): ?CustomerEmployee
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				WHERE customer_employees.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idCustomerEmployee);

		$result = $query->execute();

		return $this->parseCustomerEmployee($result);
	}

	/**
	 *
	 * @param string $email
	 * @return CustomerEmployee
	 */
	public function selectByEmail(string $email): ?CustomerEmployee
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				WHERE customer_employees.email = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $email);

		$result = $query->execute();

		return $this->parseCustomerEmployee($result);
	}

	/**
	 *
	 * @return CustomerEmployees
	 */
	public function selectAll(): CustomerEmployees
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				ORDER BY customer_employees.name";

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseCustomerEmployees($result);
	}

	/**
	 *
	 * @param int $assignmentLevel
	 * @return CustomerEmployees
	 */
	public function selectByAssignmentLevel(int $assignmentLevel): CustomerEmployees
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				WHERE customer_profiles.assignmentLevel >= ?
				ORDER BY customer_employees.name";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $assignmentLevel);

		$result = $query->execute();

		return $this->parseCustomerEmployees($result);
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @return CustomerEmployees
	 */
	public function selectByProfile(CustomerProfile $customerProfile): CustomerEmployees
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				WHERE customer_profiles.id = ?
				ORDER BY customer_employees.name";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());

		$result = $query->execute();

		return $this->parseCustomerEmployees($result);
	}

	/**
	 *
	 * @param Customer $customer
	 * @return CustomerEmployees
	 */
	public function selectByCustomer(Customer $customer): CustomerEmployees
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				INNER JOIN customers ON customers.id = customer_profiles.idCustomer
				WHERE customers.id = ?
				ORDER BY customer_employees.name";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());

		$result = $query->execute();

		return $this->parseCustomerEmployees($result);
	}

	/**
	 *
	 * @param string $email
	 * @param int $email
	 * @return bool
	 */
	public function existEmail(string $email, int $idCustomerEmployee = 0): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_employees
				WHERE email = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $email);
		$query->setInteger(2, $idCustomerEmployee);

		$result = $query->execute();
		$entry = $result->next();

		return intval($entry['qty']) > 0;
	}

	/**
	 *
	 * @param Result $result
	 * @return CustomerEmployee|NULL
	 */
	private function parseCustomerEmployee(Result $result): ?CustomerEmployee
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newCustomerEmployee($entry);
	}

	/**
	 *
	 * @param Result $result
	 * @return CustomerEmployees|NULL
	 */
	private function parseCustomerEmployees(Result $result): CustomerEmployees
	{
		$customerEmployees = new CustomerEmployees();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$customerEmployee = $this->newCustomerEmployee($entry);
			$customerEmployees->add($customerEmployee);
		}

		return $customerEmployees;
	}

	/**
	 *
	 * @param array $entry
	 * @return CustomerEmployee
	 */
	private function newCustomerEmployee(array $entry): CustomerEmployee
	{
		$customerProfile = Functions::parseEntrySQL($entry, 'customerProfile');
		$customerProfile['customer']['id'] = $customerProfile['idCustomer']; unset($customerProfile['idCustomer']);

		$customerEmployee = new CustomerEmployee();
		$customerEmployee->fromArray($entry);
		$customerEmployee->getCustomerProfile()->fromArray($customerProfile);

		return $customerEmployee;
	}
}

