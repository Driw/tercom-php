<?php

namespace tercom\control;

use tercom\dao\CustomerProfileDAO;
use tercom\entities\Customer;
use tercom\entities\CustomerProfile;
use tercom\entities\lists\CustomerProfiles;

/**
 * @see CustomerProfileDAO
 * @see Customer
 * @see CustomerProfile
 * @see CustomerProfiles
 * @author Andrew
 */
class CustomerProfileControl extends GenericControl
{
	/**
	 * @var CustomerProfileDAO
	 */
	private $customerProfileDAO;
	/**
	 * @var CustomerControl
	 */
	private $customerControl;

	/**
	 *
	 */
	public function __construct()
	{
		$this->customerProfileDAO = new CustomerProfileDAO();
		$this->customerControl = new CustomerControl();
	}

	/**
	 *
	 * @param Customer $customer
	 * @throws ControlException
	 */
	private function validateCustomer(Customer $customer)
	{
		if ($customer->getId() === 0)
			throw new ControlException('cliente não identificado');
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @return bool
	 */
	public function add(CustomerProfile $customerProfile): bool
	{
		if (!$this->avaiableName($customerProfile->getCustomer(), $customerProfile->getName(), $customerProfile->getId()))
			throw new ControlException('nome de perfil já registrado');

		return $this->customerProfileDAO->insert($customerProfile);
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @return bool
	 */
	public function set(CustomerProfile $customerProfile): bool
	{
		if (!$this->avaiableName($customerProfile->getCustomer(), $customerProfile->getName(), $customerProfile->getId()))
			throw new ControlException('nome de perfil já registrado');

		return $this->customerProfileDAO->update($customerProfile);
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @return bool
	 */
	public function remove(CustomerProfile $customerProfile): bool
	{
		return $this->customerProfileDAO->delete($customerProfile);
	}

	/**
	 *
	 * @param int $idCustomerProfile
	 * @param bool dependences
	 * @return CustomerProfile
	 */
	public function get(int $idCustomerProfile, bool $dependences = false): CustomerProfile
	{
		if (($customerProfile = $this->customerProfileDAO->select($idCustomerProfile)) === null)
			throw new ControlException('perfil de cliente não encontrado');

		if ($dependences)
		{
			$customer = $this->customerControl->get($customerProfile->getCustomerId());
			$customerProfile->setCustomer($customer);
		}

		return $customerProfile;
	}

	/**
	 *
	 * @param Customer $customer
	 * @return CustomerProfiles
	 */
	public function getByCustomer(Customer $customer): CustomerProfiles
	{
		$this->validateCustomer($customer);

		return $this->customerProfileDAO->selectByCustomer($customer);
	}

	/**
	 *
	 * @param Customer $customer
	 * @param int $assignmentLevel
	 * @return CustomerProfiles
	 */
	public function getByCustomerLevel(Customer $customer, int $assignmentLevel): CustomerProfiles
	{
		$this->validateCustomer($customer);

		return $this->customerProfileDAO->selectByCustomerLevel($customer, $assignmentLevel);
	}

	/**
	 *
	 * @return CustomerProfiles
	 */
	public function getAll(): CustomerProfiles
	{
		return $this->customerProfileDAO->selectAll();
	}

	/**
	 *
	 * @param int $idCustomerProfile
	 * @return bool
	 */
	public function has(int $idCustomerProfile): bool
	{
		return $this->customerProfileDAO->exist($idCustomerProfile);
	}

	/**
	 *
	 * @param Customer $customer
	 * @param string $name
	 * @param int $idCustomerProfile
	 * @return bool
	 */
	public function avaiableName(Customer $customer, string $name, int $idCustomerProfile = 0): bool
	{
		return !$this->customerProfileDAO->existName($customer, $name, $idCustomerProfile);
	}
}

