<?php

namespace tercom\DAO;

use dProject\MySQL\MySQL;
use dProject\MySQL\Result;
use tercom\entities\Phone;
use tercom\entities\ProviderContact;
use tercom\Functions;

class ProviderContactDAO extends GenericDAO
{
	/**
	 * @var PhoneDAO
	 */
	private $phoneDAO;

	public function __construct(MySQL $mysql)
	{
		parent::__construct($mysql);

		$this->phoneDAO = new PhoneDAO($mysql);
	}

	private function validate(ProviderContact $providerContact, bool $validateID)
	{
		if ($validateID) {
			if ($providerContact->getID() === 0)
				throw new DAOException('contato não identificado');
		} else {
			if ($providerContact->getID() !== 0)
				throw new DAOException('contato já identificado');
		}

		if (empty($providerContact->getName()))
			throw new DAOException('nome não definido');
	}

	public function insert(ProviderContact $providerContact):bool
	{
		if ($providerContact->getCellPhone() != null) $this->phoneDAO->insert($providerContact->getCellPhone());
		if ($providerContact->getOtherPhone() != null) $this->phoneDAO->insert($providerContact->getOtherPhone());

		$sql = "INSERT INTO provider_contact (name, post, email, cellphone, otherphone) VALUES (?, ?, ?, ?, ?)";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $providerContact->getName());
		$query->setString(2, $providerContact->getPost());
		$query->setString(3, $providerContact->getEmail());
		$query->setInteger(4, $providerContact->getCellPhone()->getID() == 0 ? null : $providerContact->getCellPhone()->getID());
		$query->setInteger(5, $providerContact->getOtherPhone()->getID() == 0 ? null : $providerContact->getOtherPhone()->getID());

		$result = $query->execute(true);

		if ($result->isSuccessful())
			$providerContact->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(ProviderContact $providerContact):bool
	{
		$this->updatePhone($providerContact->getCellPhone());
		$this->updatePhone($providerContact->getOtherPhone());

		$sql = "UPDATE provider_contact
				SET name = ?, post = ?, email = ?, cellphone = ?, otherphone = ?
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $providerContact->getName());
		$query->setString(2, $providerContact->getPost());
		$query->setString(3, $providerContact->getEmail());
		$query->setInteger(4, $providerContact->getCellPhone()->getID() == 0 ? null : $providerContact->getCellPhone()->getID());
		$query->setInteger(5, $providerContact->getOtherPhone()->getID() == 0 ? null : $providerContact->getOtherPhone()->getID());
		$query->setInteger(6, $providerContact->getID());

		$result = $query->execute(true);

		if ($result->isSuccessful())
			$providerContact->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	private function updatePhone(Phone $phone)
	{
		if ($phone == null)
			return;

		if ($phone->getID() === 0)
			$this->phoneDAO->insert($phone);
		else
			$this->phoneDAO->update($phone);
	}

	public function delete(ProviderContact $providerContact):bool
	{
		$sql = "DELETE FROM provider_contact WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $providerContact->getID());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function selectByID(int $providerContactID):ProviderContact
	{
		$sql = "SELECT id, name, post, email, cellphone, otherphone FROM provider_contact WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $providerContactID);

		$result = $query->execute();

		return $this->parseProviderContact($result);
	}

	public function filterByName(string $name):array
	{
		$sql = "SELECT id, name, post, email, cellphone, otherphone FROM provider_contact WHERE name LIKE ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, "%$name%");

		$result = $query->execute();

		return $this->parseProviderContacts($result);
	}

	public function filterByProvider(int $providerID):array
	{
		$sql = "SELECT id, nome, cargo, email, telefone, celular
				FROM provider_contact
				INNER JOIN provider_contacts ON provider_contacts.providerID = $providerID
				ORDER BY nome";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $providerID);

		$result = $query->execute();

		return $this->parseProviderContacts($result);
	}

	private function parseProviderContact(Result $result)
	{
		if (!($providerContactArray = $this->parseSingleResult($result)))
			return null;

		$providerContact = new ProviderContact();
		$providerContact->setID($providerContactID);
		Functions::parseArrayObject($providerContact, $providerContactArray);
		$this->loadPhones($providerContact, $providerContactArray);

		return $providerContact;
	}

	private function parseProviderContacts(Result $result)
	{
		$providerContacts = [];

		if (!($providerContactsArray = $this->parseMultiplyResults($result)))
			return null;

		foreach ($providerContactsArray as $providerContactArray)
		{
			$providerContact = new ProviderContact();
			$providerContact->setID($providerContactID);
			Functions::parseArrayObject($providerContact, $providerContactArray);
			array_push($providerContacts, $providerContact);
			$this->loadPhones($providerContact, $providerContactArray);
		}

		return $providerContacts;
	}

	private function loadPhones(ProviderContact $providerContact, array $providerContactArray):void
	{
		if (($cellphoneID = $providerContactArray['cellphone']) !== null)
		{
			$cellphone = $this->phoneDAO->selectById($cellphoneID);
			$providerContact->setPhone($cellphone);
		}

		if (($otherphoneID = $providerContactArray['otherphone']) !== null)
		{
			$otherphone = $this->phoneDAO->selectById($otherphoneID);
			$providerContact->setCelular($otherphone);
		}
	}
}

?>