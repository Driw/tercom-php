<?php

namespace tercom\control;

use tercom\dao\AddressDAO;
use tercom\entities\Address;
use tercom\entities\Customer;
use tercom\dao\CustomerAddressDAO;

/**
 * @see AddressDAO
 * @author Andrew
 */
class AddressControl extends GenericControl
{
	/**
	 * @var AddressDAO
	 */
	private $addressDAO;
	/**
	 * @var CustomerAddressDAO
	 */
	private $customerAddressDAO;

	/**
	 *
	 */
	public function __construct()
	{
		$this->addressDAO = new AddressDAO();
		$this->customerAddressDAO = new CustomerAddressDAO();
	}

	/**
	 *
	 * @param Customer $customer
	 * @param Address $address
	 * @return bool
	 */
	public function addCustomerAddress(Customer $customer, Address $address): bool
	{
		$this->addressDAO->beginTransaction();
		{
			if (!$this->addressDAO->insert($address) || !$this->customerAddressDAO->insert($customer, $address))
				$this->addressDAO->rollback();
		}
		$this->addressDAO->commit();

		$customer->getAddresses()->add($address);
		return true;
	}

	/**
	 *
	 * @param Customer $customer
	 * @param Address $address
	 * @throws ControlException
	 * @return bool
	 */
	public function setCustomerAddress(Customer $customer, Address $address): bool
	{
		if (!$this->customerAddressDAO->has($customer, $address))
			throw new ControlException('endereço não vinculado ao cliente');

		return $this->addressDAO->update($address);
	}

	/**
	 *
	 * @param Customer $customer
	 * @param Address $address
	 * @throws ControlException
	 * @return bool
	 */
	public function removeCustomerAddress(Customer $customer, Address $address): bool
	{
		if (!$this->customerAddressDAO->has($customer, $address))
			throw new ControlException('endereço não vinculado ao cliente');

		if (!$this->addressDAO->delete($address))
			return false;

		$customer->getAddresses()->removeElement($address);
		return true;
	}

	/**
	 *
	 * @param Customer $customer
	 * @param int $idAddress
	 * @throws ControlException
	 */
	public function getCustomerAddresses(Customer $customer, int $idAddress): Address
	{
		$this->validateCustomer($customer);

		if (($address = $this->addressDAO->select($idAddress)) === null)
			throw new ControlException('endereço não encontrado');

		if (!$this->customerAddressDAO->has($customer, $address))
			throw new ControlException('endereço não encontrado no cliente');

		$customer->getAddresses()->replace($address);
		return $address;
	}

	/**
	 *
	 * @param Customer $customer
	 * @throws ControlException
	 * @return int
	 */
	public function loadCustomerAddresses(Customer $customer): int
	{
		$this->validateCustomer($customer);
		$addresses = $this->customerAddressDAO->select($customer);
		$customer->setAddresses($addresses);

		return $addresses->size();
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
}

