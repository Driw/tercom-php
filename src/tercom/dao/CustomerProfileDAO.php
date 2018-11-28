<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\Functions;
use tercom\dao\exceptions\DAOException;
use tercom\entities\Customer;
use tercom\entities\CustomerProfile;
use tercom\entities\lists\CustomerProfiles;

/**
 * @see CustomerProfile
 * @author Andrew
 */
class CustomerProfileDAO extends GenericDAO
{
	/**
	 * @var array
	 */
	public const ALL_COLUMNS = ['id', 'idCustomer', 'name', 'assignmentLevel'];

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @param bool $validateID
	 * @throws DAOException
	 */
	private function validate(CustomerProfile $customerProfile, bool $validateID)
	{
		if ($validateID) {
			if ($customerProfile->getId() === 0)
				throw new DAOException('perfil não identificado');
		} else {
			if ($customerProfile->getId() !== 0)
				throw new DAOException('perfil já identificado');
		}

		if ($customerProfile->getCustomerId() === 0) throw new DAOException('cliente não identificado');
		if (StringUtil::isEmpty($customerProfile->getName())) throw new DAOException('nome não definido');
		if ($customerProfile->getAssignmentLevel() === 0) throw new DAOException('nível de assinatura não definido');
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @return bool
	 */
	public function insert(CustomerProfile $customerProfile): bool
	{
		$this->validate($customerProfile, false);

		$sql = "INSERT INTO customer_profiles (idCustomer, name, assignmentLevel)
				VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getCustomerId());
		$query->setString(2, $customerProfile->getName());
		$query->setInteger(3, $customerProfile->getAssignmentLevel());

		if (($result = $query->execute())->isSuccessful())
			$customerProfile->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @return bool
	 */
	public function update(CustomerProfile $customerProfile): bool
	{
		$this->validate($customerProfile, true);

		$sql = "UPDATE customer_profiles
				SET idCustomer = ?, name = ?, assignmentLevel = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getCustomerId());
		$query->setString(2, $customerProfile->getName());
		$query->setInteger(3, $customerProfile->getAssignmentLevel());
		$query->setInteger(4, $customerProfile->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @return bool
	 */
	public function delete(CustomerProfile $customerProfile): bool
	{
		$this->validate($customerProfile, true);

		$sql = "DELETE FROM customer_profiles
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @return string
	 */
	private function newBaseSelect(): string
	{
		return "SELECT id, idCustomer, name, assignmentLevel
				FROM customer_profiles";
	}

	/**
	 *
	 * @param int $idCustomerProfile
	 * @return CustomerProfile|NULL
	 */
	public function select(int $idCustomerProfile): ?CustomerProfile
	{
		$sqlBase = $this->newBaseSelect();
		$sql = "$sqlBase
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idCustomerProfile);

		$result = $query->execute();

		return $this->parseCustomerProfile($result);
	}

	/**
	 *
	 * @param Customer $customer
	 * @return CustomerProfiles|NULL
	 */
	public function selectByCustomer(Customer $customer): CustomerProfiles
	{
		$sqlBase = $this->newBaseSelect();
		$sql = "$sqlBase
				WHERE idCustomer = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());

		$result = $query->execute();

		return $this->parseCustomerProfiles($result);
	}

	/**
	 *
	 * @param Customer $customer
	 * @param int $assignmentLevel
	 * @return CustomerProfiles|NULL
	 */
	public function selectByCustomerLevel(Customer $customer, int $assignmentLevel): CustomerProfiles
	{
		$sqlBase = $this->newBaseSelect();
		$sql = "$sqlBase
				WHERE idCustomer = ? AND assignmentLevel <= ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setInteger(2, $assignmentLevel);

		$result = $query->execute();

		return $this->parseCustomerProfiles($result);
	}

	/**
	 *
	 * @return CustomerProfiles|NULL
	 */
	public function selectAll(): CustomerProfiles
	{
		$sql = $this->newBaseSelect();

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseCustomerProfiles($result);
	}

	/**
	 *
	 * @param int $idCustomerProfile
	 * @return bool
	 */
	public function exist(int $idCustomerProfile): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_profiles
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idCustomerProfile);

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) === 1;
	}

	/**
	 *
	 * @param Customer $customer
	 * @param string $name
	 * @param int $idCustomerProfile
	 * @return bool
	 */
	public function existName(Customer $customer, string $name, int $idCustomerProfile): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_profiles
				WHERE idCustomer = ? AND name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setString(2, $name);
		$query->setInteger(3, $idCustomerProfile);

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) === 1;
	}

	/**
	 *
	 * @param Result $result
	 * @return CustomerProfile|NULL
	 */
	private function parseCustomerProfile(Result $result): ?CustomerProfile
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newCustomerProfile($entry);
	}

	/**
	 *
	 * @param Result $result
	 * @return CustomerProfiles
	 */
	private function parseCustomerProfiles(Result $result): CustomerProfiles
	{
		$customerProfiles = new CustomerProfiles();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$customerProfile = $this->newCustomerProfile($entry);
			$customerProfiles->add($customerProfile);
		}

		return $customerProfiles;
	}

	/**
	 *
	 * @param array $entry
	 * @return CustomerProfile
	 */
	private function newCustomerProfile(array $entry): CustomerProfile
	{
		Functions::parseArrayJoin($entry);

		$customerProfile = new CustomerProfile();
		$customerProfile->fromArray($entry);

		if (isset($entry['idCustomer']))
			$customerProfile->getCustomer()->setId($entry['idCustomer']);

		return $customerProfile;
	}
}

