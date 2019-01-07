<?php

namespace tercom\control;

use tercom\dao\AddressDAO;
use tercom\entities\Address;
use tercom\entities\Customer;
use tercom\dao\CustomerAddressDAO;
use tercom\entities\lists\Customers;
use tercom\TercomException;

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
	 * @throws TercomException
	 */
	private function validate(Customer $customer, ?Address $address = null): void
	{
		if ($this->hasCustomerLogged())
			if ($customer->getId() !== $this->getCustomerLoggedId())
				throw TercomException::newPermissionRestrict();

		if ($address !== null)
			if (!$this->customerAddressDAO->exist($customer, $address))
				throw new ControlException('endereço desconhecido');
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::addRelationship()
	 * @param Customer $customer
	 * @param Address $address
	 */
	public function addRelationship($customer, $address): void
	{
		$this->validate($customer);

		$this->addressDAO->beginTransaction();
		{
			if (!$this->addressDAO->insert($address) || !$this->customerAddressDAO->insert($customer, $address))
			{
				$this->addressDAO->rollback();
				throw new ControlException('não foi possível vincular o endereço ao cliente');
			}
		}
		$this->addressDAO->commit();
		$customer->getAddresses()->add($address);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::setRelationship()
	 * @param Customer $customer
	 * @param Address $address
	 */
	public function setRelationship($customer, $address): void
	{
		$this->validate($customer, $address);

		if (!$this->addressDAO->update($address))
			throw new ControlException('não foi possível atualizar o endereço');
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::removeRelationship()
	 * @param Customer $customer
	 * @param Address $address
	 */
	public function removeRelationship($customer, $address): void
	{
		$this->validate($customer, $address);

		if (!$this->addressDAO->delete($address))
			throw new ControlException('não foi excluir o endereço');

		$customer->getAddresses()->removeElement($address);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::getRelationship()
	 * @param Customer $customer
	 */
	public function getRelationship($customer, int $idAddress)
	{
		if (($address = $this->addressDAO->select($idAddress)) === null)
			throw new ControlException('endereço não encontrado');

		$this->validate($customer, $address);
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
		$this->validate($customer);
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

