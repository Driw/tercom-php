<?php

namespace tercom\control;

use tercom\dao\AddressDAO;
use tercom\entities\Address;
use tercom\entities\Customer;
use tercom\dao\CustomerAddressDAO;
use tercom\entities\lists\Customers;

/**
 * @see AddressDAO
 * @author Andrew
 */
class CustomerAddressControl extends GenericControl implements RelationshipControl
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
	 * @throws ControlException
	 */
	private function validateCustomer(Customer $customer)
	{
		if ($customer->getId() === 0)
			throw new ControlException('cliente não identificado');
	}

	/**
	 *
	 * @param Customer $customer
	 * @param Address $address
	 * @throws ControlException
	 */
	private function validateCustomerAddress(Customer $customer, Address $address)
	{
		$this->validateCustomer($customer);

		if ($address->getId() === 0)
			throw new ControlException('endereço não identificado');

		if (!$this->hasRelationship($customer, $address))
			throw new ControlException('endereço não vinculado ao cliente');
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::addRelationship()
	 * @param Customer $customer
	 * @param Address $address
	 */
	public function addRelationship($customer, $address): bool
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
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::setRelationship()
	 * @param Customer $customer
	 * @param Address $address
	 */
	public function setRelationship($customer, $address): bool
	{
		$this->validateCustomerAddress($customer, $address);

		return $this->addressDAO->update($address);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::removeRelationship()
	 * @param Customer $customer
	 * @param Address $address
	 */
	public function removeRelationship($customer, $address): bool
	{
		$this->validateCustomerAddress($customer, $address);

		if (!$this->addressDAO->delete($address))
			return false;

		$customer->getAddresses()->removeElement($address);
		return true;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::getRelationship()
	 * @param Customer $customer
	 */
	public function getRelationship($customer, int $idAddress)
	{
		$this->validateCustomer($customer);

		if (($address = $this->addressDAO->select($idAddress)) === null)
			throw new ControlException('endereço não encontrado');

		$this->validateCustomerAddress($customer, $address);
		$customer->getAddresses()->replace($address);

		return $address;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::getRelationships()
	 * @param Customer $customer
	 * @return Customers
	 */
	public function getRelationships($customer)
	{
		$this->validateCustomer($customer);
		$addresses = $this->customerAddressDAO->select($customer);
		$customer->setAddresses($addresses);

		return $addresses;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::hasRelationship()
	 * @param Customer $customer
	 * @param Address $address
	 */
	public function hasRelationship($customer, $address): bool
	{
		return $this->customerAddressDAO->exist($customer, $address);
	}
}

