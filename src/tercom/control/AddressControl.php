<?php

namespace tercom\control;

use tercom\dao\AddressDAO;
use tercom\entities\Address;
use tercom\entities\Customer;

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
	 *
	 */
	public function __construct()
	{
		$this->addressDAO = new AddressDAO();
	}

	/**
	 *
	 * @param Address $address
	 * @throws ControlException
	 */
	public function add(Address $address): void
	{
		if (!$this->addressDAO->insert($address))
			throw new ControlException('não foi possível adicionar o endereço');
	}

	/**
	 *
	 * @param Address $address
	 * @return bool
	 */
	public function set(Address $address): bool
	{
		return $this->addressDAO->update($address);
	}

	/**
	 *
	 * @param Address $address
	 * @return bool
	 */
	public function remove(Address $address): bool
	{
		return $this->addressDAO->delete($address) > 0;
	}

	/**
	 *
	 * @param int $idAddress
	 * @throws ControlException
	 * @return Address
	 */
	public function get(int $idAddress): Address
	{
		if (($address = $this->addressDAO->select($idAddress)) === null)
			throw new ControlException('endereço não encontrado');

		return $address;
	}

	/**
	 *
	 * @param Customer $customer
	 * @return int
	 */
	public function saveCustomerAddresses(Customer $customer): int
	{
		$this->validateCustomer($customer);

		foreach ($customer->getAddresses() as $address)
		{
			if ($address->getId() === 0) {
				if (!$this->addressDAO->insert($address))
					throw ControlException::new("não foi possível inserir o endereço $address");
			} else {
				if (!$this->addressDAO->update($address))
					throw ControlException::new("não foi possível atualizar o endereço $address");
			}
		}

		return $customer->getAddresses()->size();
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
		$addresses = $this->addressDAO->selectByCustomer($customer);
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

