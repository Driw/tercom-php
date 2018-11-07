<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\dao\exceptions\AddressDAOException;
use tercom\dao\exceptions\DAOException;
use tercom\entities\Address;
use tercom\entities\lists\Addresses;

/**
 * @see GenericDAO
 * @see Address
 * @author Andrew
 */
class AddressDAO extends GenericDAO
{
	/**
	 * @var array
	 */
	public const ALL_COLUMNS = ['id', 'state', 'city', 'cep', 'neighborhood', 'street', 'number', 'complement'];

	/**
	 *
	 * @param Address $address
	 * @param bool $validateID
	 * @throws DAOException
	 */
	private function validateAddress(Address $address, bool $validateID)
	{
		if ($validateID) {
			if ($address->getId() === 0)
				throw AddressDAOException::newNoId();
		} else {
			if ($address->getId() !== 0)
				throw AddressDAOException::newHasId();
		}

		if (StringUtil::isEmpty($address->getCity())) throw AddressDAOException::newCityEmpty();
		if (StringUtil::isEmpty($address->getCep())) throw AddressDAOException::newCepEmpty();
		if (StringUtil::isEmpty($address->getNeighborhood())) throw AddressDAOException::newNeighborhoodEmpty();
		if (StringUtil::isEmpty($address->getStreet())) throw AddressDAOException::newStreetEmpty();
		if ($address->getNumber() === 0) throw AddressDAOException::newNumberEmpty();
	}

	/**
	 *
	 * @param Address $address
	 * @return bool
	 */
	public function insert(Address $address): bool
	{
		$sql = "INSERT INTO addresses (state, city, cep, neighborhood, street, number, complement)
				VALUES (?, ?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setString(1, $address->getState());
		$query->setString(2, $address->getCity());
		$query->setString(3, $address->getCep());
		$query->setString(4, $address->getNeighborhood());
		$query->setString(5, $address->getStreet());
		$query->setString(6, $address->getNumber());
		$query->setString(7, $address->getComplement());

		if (($result = $query->execute())->isSuccessful())
			$address->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 *
	 * @param Address $address
	 * @return bool
	 */
	public function update(Address $address): bool
	{
		$sql = "UPDATE addresses
				SET state = ?, city = ?, cep = ?, neighborhood = ?, street = ?, number = ?, complement = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setString(1, $address->getState());
		$query->setString(2, $address->getCity());
		$query->setString(3, $address->getCep());
		$query->setString(4, $address->getNeighborhood());
		$query->setString(5, $address->getStreet());
		$query->setString(6, $address->getNumber());
		$query->setString(7, $address->getComplement());
		$query->setString(8, $address->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param Address $address
	 * @return bool
	 */
	public function delete(Address $address): bool
	{
		$sql = "DELETE FROM addresses
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $address->getId());

		return ($query->execute())->getAffectedRows() >= 1;
	}

	/**
	 *
	 * @param int $idAddress
	 * @return Address|NULL
	 */
	public function select(int $idAddress): ?Address
	{
		$sql = "SELECT id, state, city, cep, neighborhood, street, number, complement
				FROM addresses
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idAddress);

		$result = $query->execute();

		return $this->parseAddress($result);
	}

	/**
	 *
	 * @param Result $result
	 * @return Address|NULL
	 */
	private function parseAddress(Result $result): ?Address
	{
		if (!$result->hasNext())
			return null;

		$entry = $result->next();
		$address = $this->newAddress($entry);

		return $address;
	}

	/**
	 *
	 * @param Result $result
	 * @return Addresses
	 */
	private function parseAddresses(Result $result): Addresses
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
	private function newAddress(array $entry): Address
	{
		$address = new Address();
		$address->fromArray($entry);

		return $address;
	}
}

