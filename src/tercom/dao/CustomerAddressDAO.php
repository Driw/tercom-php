<?php

namespace tercom\dao;

use tercom\entities\Address;
use tercom\entities\Customer;
use tercom\entities\lists\Addresses;
use dProject\MySQL\Result;

/**
 * @see GenericDAO
 * @see Address
 * @author andrews
 */
class CustomerAddressDAO extends GenericDAO
{
	/**
	 *
	 * @param Customer $customer
	 * @param Address $address
	 * @return bool
	 */
	public function insert(Customer $customer, Address $address): bool
	{
		$sql = "INSERT INTO customer_addresses (idCustomer, idAddress)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setInteger(2, $address->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param Customer $customer
	 * @param Address $address
	 * @return bool
	 */
	public function delete(Customer $customer, Address $address): bool
	{
		$sql = "DELETE FROM customer_addresses
				WHERE idCustomer = ? AND idAddress = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setInteger(2, $address->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @return string
	 */
	private function newSqlCustomerAddresses(): string
	{
		$addressColumns = $this->buildQuery(AddressDAO::ALL_COLUMNS, 'addresses');

		return "SELECT $addressColumns
				FROM addresses
				INNER JOIN customer_addresses ON customer_addresses.idAddress = addresses.id";
	}

	/**
	 *
	 * @param Customer $customer
	 * @return Addresses
	 */
	public function select(Customer $customer): Addresses
	{
		$sqlCustomerAddresses = $this->newSqlCustomerAddresses();
		$sql = "$sqlCustomerAddresses
				WHERE customer_addresses.idCustomer = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());

		$result = $query->execute();

		return $this->parseAddresses($result);
	}

	/**
	 *
	 * @param Customer $customer
	 * @param Address $address
	 * @return bool
	 */
	public function exist(Customer $customer, Address $address): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_addresses
				WHERE idCustomer = ? AND idAddress = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setInteger(2, $address->getId());

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) !== 0;
	}

	/**
	 *
	 * @param Result $result
	 * @return Addresses
	 */
	protected function parseAddresses(Result $result): Addresses
	{
		$addresses = new Addresses();

		while ($result->hasNext())
		{
			$entry = $result->next();
			$address = $this->newAddress($entry);
			$addresses->add($address);
		}

		return $addresses;
	}

	/**
	 *
	 * @param array $entry
	 * @return Address
	 */
	protected function newAddress(array $entry): Address
	{
		$address = new Address();
		$address->fromArray($entry);

		return $address;
	}
}

