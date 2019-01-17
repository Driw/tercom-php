<?php

namespace tercom\control;

use tercom\dao\AddressDAO;
use tercom\entities\Address;
use tercom\exceptions\AddressException;

/**
 *
 *
 * @see GenericControl
 * @see AddressDAO
 * @see Address
 *
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
	 * @throws AddressException
	 */
	public function add(Address $address): void
	{
		if (!$this->addressDAO->insert($address))
			throw AddressException::newInserted();
	}

	/**
	 *
	 * @param Address $address
	 * @throws AddressException
	 */
	public function set(Address $address): void
	{
		if (!$this->addressDAO->update($address))
			throw AddressException::newUpdated();
	}

	/**
	 *
	 * @param Address $address
	 * @throws AddressException
	 */
	public function remove(Address $address): void
	{
		if (!$this->addressDAO->delete($address))
			throw AddressException::newDeleted();
	}

	/**
	 *
	 * @param int $idAddress
	 * @throws AddressException
	 * @return Address
	 */
	public function get(int $idAddress): Address
	{
		if (($address = $this->addressDAO->select($idAddress)) === null)
			throw AddressException::newInserted();

		return $address;
	}
}

