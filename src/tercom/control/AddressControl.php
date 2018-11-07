<?php

namespace tercom\control;

use tercom\dao\AddressDAO;
use tercom\entities\Address;
use tercom\entities\Customer;

/**
 * @see AddressDAO
 * @author andrews
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
}

