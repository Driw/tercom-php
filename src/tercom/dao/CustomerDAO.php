<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\dao\exceptions\CustomerDAOException;
use tercom\entities\Customer;
use tercom\entities\lists\Customers;

/**
 * @see GenericDAO
 * @see Customer
 * @author Andrew
 *
 */
class CustomerDAO extends GenericDAO
{
	/**
	 *
	 * @param Customer $customer
	 * @param bool $validateID
	 * @throws CustomerDAOException
	 */
	private function validate(Customer $customer, bool $validateID)
	{
		if ($validateID) {
			if ($customer->getId() === 0)
				throw CustomerDAOException::newNoId();
		} else {
			if ($customer->getId() !== 0)
				throw CustomerDAOException::newHasId();
		}

		if (StringUtil::isEmpty($customer->getStateRegistry())) throw CustomerDAOException::newStateRegistryEmpty();
		if (StringUtil::isEmpty($customer->getCnpj())) throw CustomerDAOException::newCnpjEmpty();
		if (StringUtil::isEmpty($customer->getCompanyName())) throw CustomerDAOException::newCompanyNameEmpty();
		if (StringUtil::isEmpty($customer->getFantasyName())) throw CustomerDAOException::newFantasyNameEmpty();
		if (StringUtil::isEmpty($customer->getEmail())) throw CustomerDAOException::newEmailEmpty();
	}

	/**
	 *
	 * @param Customer $customer
	 * @return bool
	 */
	public function insert(Customer $customer): bool
	{
		$this->validate($customer, false);
		$customer->getRegister()->setTimestamp(time());

		$sql = "INSERT INTO customers (stateRegistry, cnpj, companyName, fantasyName, email, inactive, register)
				VALUES (?, ?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $customer->getStateRegistry());
		$query->setString(2, $customer->getCnpj());
		$query->setString(3, $customer->getCompanyName());
		$query->setString(4, $customer->getFantasyName());
		$query->setString(5, $customer->getEmail());
		$query->setBoolean(6, $customer->isInactive());
		$query->setDateTime(7, $customer->getRegister());

		if (($result = $query->execute())->isSuccessful())
			$customer->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 *
	 * @param Customer $customer
	 * @return bool
	 */
	public function update(Customer $customer): bool
	{
		$this->validate($customer, true);

		$sql = "UPDATE customers
				SET stateRegistry = ?, cnpj = ?, companyName = ?, fantasyName = ?, email = ?, inactive = ?, register = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $customer->getStateRegistry());
		$query->setString(2, $customer->getCnpj());
		$query->setString(3, $customer->getCompanyName());
		$query->setString(4, $customer->getFantasyName());
		$query->setString(5, $customer->getEmail());
		$query->setBoolean(6, $customer->isInactive());
		$query->setDateTime(7, $customer->getRegister());
		$query->setInteger(8, $customer->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param Customer $customer
	 * @return bool
	 */
	public function delete(Customer $customer): bool
	{
		$sql = "DELETE FROM customers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $customer->getId());

		return ($query->execute())->getAffectedRows() >= 1;
	}

	/**
	 *
	 * @return string
	 */
	private function newSqlCustomer(): string
	{
		return "SELECT id, stateRegistry, cnpj, companyName, fantasyName, email, inactive, register
				FROM customers";
	}

	/**
	 *
	 * @param int $idCustomer
	 * @return Customer|NULL
	 */
	public function select(int $idCustomer): ?Customer
	{
		$sqlCustomer = $this->newSqlCustomer();
		$sql = "$sqlCustomer
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idCustomer);

		$result = $query->execute();

		return $this->parseCustomer($result);
	}

	/**
	 *
	 * @param string $cnpj
	 * @return Customer|NULL
	 */
	public function selectByCnpj(string $cnpj): ?Customer
	{
		$sqlCustomer = $this->newSqlCustomer();
		$sql = "$sqlCustomer
				WHERE cnpj = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $cnpj);

		$result = $query->execute();

		return $this->parseCustomer($result);
	}

	/**
	 *
	 * @return Customers
	 */
	public function selectAll(): Customers
	{
		$sql = $this->newSqlCustomer();

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseCustomers($result);
	}

	/**
	 *
	 * @param string $stateRegistry
	 * @return Customers
	 */
	public function selectByStateRegistryLike(string $stateRegistry): Customers
	{
		$sqlCustomer = $this->newSqlCustomer();
		$sql = "$sqlCustomer
				WHERE stateRegistry LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$stateRegistry%");

		$result = $query->execute();

		return $this->parseCustomers($result);
	}

	/**
	 *
	 * @param string $cnpj
	 * @return Customers
	 */
	public function selectByCnpjLike(string $cnpj): Customers
	{
		$sqlCustomer = $this->newSqlCustomer();
		$sql = "$sqlCustomer
				WHERE cnpj LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$cnpj%");

		$result = $query->execute();

		return $this->parseCustomers($result);
	}

	/**
	 *
	 * @param string $fantasyName
	 * @return Customers
	 */
	public function selectByFantasyNameLike(string $fantasyName): Customers
	{
		$sqlCustomer = $this->newSqlCustomer();
		$sql = "$sqlCustomer
				WHERE fantasyName = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $fantasyName);

		$result = $query->execute();

		return $this->parseCustomers($result);
	}

	/**
	 *
	 * @param string $fantasyName
	 * @param int $idCustomer
	 * @return int
	 */
	public function selectCountCnpj(string $cnpj, int $idCustomer = 0): int
	{
		$sql = "SELECT COUNT(*) qty
				FROM customers
				WHERE cnpj = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $cnpj);
		$query->setInteger(2, $idCustomer);

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']);
	}

	/**
	 *
	 * @param string $companyName
	 * @param int $idCustomer
	 * @return int
	 */
	public function selectCountCompanyName(string $companyName, int $idCustomer = 0): int
	{
		$sql = "SELECT COUNT(*) qty
				FROM customers
				WHERE companyName = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $companyName);
		$query->setInteger(2, $idCustomer);

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']);
	}

	/**
	 *
	 * @param Result $result
	 * @return Customer|NULL
	 */
	private function parseCustomer(Result $result, int $idCustomer = 0): ?Customer
	{
		if (!$result->hasNext())
			return null;

		$entry = $result->next();
		$customer = $this->newCustomer($entry);

		return $customer;
	}

	/**
	 *
	 * @param Result $result
	 * @return Customers
	 */
	private function parseCustomers(Result $result): Customers
	{
		$customers = new Customers();

		while ($result->hasNext())
		{
			$entry = $result->next();
			$customer = $this->newCustomer($entry);
			$customers->add($customer);
		}

		return $customers;
	}

	/**
	 *
	 * @param array $entry
	 * @return Customer
	 */
	private function newCustomer(array $entry): Customer
	{
		$customer = new Customer();
		$customer->fromArray($entry);

		return $customer;
	}
}

