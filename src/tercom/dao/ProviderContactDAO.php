<?php

namespace tercom\dao;

use dProject\MySQL\MySQL;
use dProject\MySQL\Result;
use tercom\entities\ProviderContact;
use tercom\entities\Provider;
use tercom\entities\lists\ProviderContacts;

class ProviderContactDAO extends GenericDAO
{
	public function __construct(MySQL $mysql)
	{
		parent::__construct($mysql);
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
		$sql = "INSERT INTO provider_contact (name, position, email, commercial, otherphone) VALUES (?, ?, ?, ?, ?)";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $providerContact->getName());
		$query->setString(2, $providerContact->getPosition());
		$query->setString(3, $providerContact->getEmail());
		$query->setInteger(4, $providerContact->getCommercial()->getID() > 0 ? $providerContact->getCommercial()->getID() : null);
		$query->setInteger(5, $providerContact->getOtherPhone()->getID() > 0 ? $providerContact->getOtherPhone()->getID() : null);

		$result = $query->execute(true);

		if ($result->isSuccessful())
			$providerContact->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(ProviderContact $providerContact):bool
	{
		$sql = "UPDATE provider_contact
				SET name = ?, position = ?, email = ?, commercial = ?, otherphone = ?
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $providerContact->getName());
		$query->setString(2, $providerContact->getPosition());
		$query->setString(3, $providerContact->getEmail());
		$query->setInteger(4, $providerContact->getCommercial()->getID() > 0 ? $providerContact->getCommercial()->getID() : null);
		$query->setInteger(5, $providerContact->getOtherPhone()->getID() > 0 ? $providerContact->getOtherPhone()->getID() : null);
		$query->setInteger(6, $providerContact->getID());

		$result = $query->execute(true);

		return $result->isSuccessful();
	}

	public function updatePhones(ProviderContact $providerContact):bool
	{
		$sql = "UPDATE provider_contact
				SET commercial = ?, otherphone = ?
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $providerContact->getCommercial()->getID() > 0 ? $providerContact->getCommercial()->getID() : null);
		$query->setInteger(2, $providerContact->getOtherPhone()->getID() > 0 ? $providerContact->getOtherPhone()->getID() : null);
		$query->setInteger(3, $providerContact->getID());

		$result = $query->execute(true);

		return $result->isSuccessful();
	}

	public function delete(ProviderContact $providerContact):bool
	{
		$sql = "DELETE FROM provider_contact WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $providerContact->getID());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	/**
	 * Vincula um contato de fornecedor a um fornecedor sendo necessário ambos já identificados.
	 * @param Provider $provider referência do fornecedor a ter o contato vinculado.
	 * @param ProviderContact $providerContact referência do contato à ser vinculado.
	 * @param int $priority nível de prioridade de exibição na lista de contatos,
	 * quanto maior o nível de prioridade mais a frente na lista vai estar.
	 * @return int quantidade de registros afetados conforme:
	 * <code>REPLACE_NONE</code>, <code>REPLACE_INSERTED</code> ou <code>REPLACE_UPDATED</code>.
	 */

	public function linkContact(Provider $provider, ProviderContact $providerContact, int $priority = 0):int
	{
		$sql = "REPLACE INTO provider_contacts (idProvider, idProviderContact, priority)
				VALUES (?, ?, ?)";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $provider->getID());
		$query->setInteger(2, $providerContact->getID());
		$query->setInteger(3, $priority);

		$result = $query->execute();

		return $result->getAffectedRows();
	}

	/**
	 * Desvincula todos os contatos de fornecedores de um fornecedor identificado.
	 * @param Provider $provider referência do fornecedor a ter os contatos desvinculados.
	 * @return int aquisição da quantidade de contatos que foram desvinculados.
	 */

	public function unlinkContacts(Provider $provider):int
	{
		$sql = "DELETE FROM provider_contacts
				WHERE idProvider = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $provider->getID());

		$result = $query->execute();

		return $result->getAffectedRows();
	}

	/**
	 * Obtém os dados de contato de fornecedor de um contato identificado.
	 * @param int $idProvider código de identificação único do fornecedor.
	 * @param int $idProviderContact código de identificação único do contato.
	 * @return ProviderContact aquisição do contato de fornecedor selecionado.
	 */

	public function selectByID(int $idProvider, int $idProviderContact):?ProviderContact
	{
		$sql = "SELECT id, name, position, email, commercial, otherphone
				FROM provider_contact
				INNER JOIN provider_contacts ON provider_contacts.idProviderContact = provider_contact.id
				WHERE provider_contacts.idProvider = ? AND provider_contacts.idProviderContact = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idProvider);
		$query->setInteger(2, $idProviderContact);

		$result = $query->execute();

		return $this->parseProviderContact($result);
	}

	/**
	 * Obtém uma lista com todos os contatos de um fornecedor identificado.
	 * @param int $idProviderContact código de identificação único do fornecedor.
	 * @return ProviderContacts aquisição da lista de contatos do fornecedor.
	 */

	public function selectByProvider(int $idProvider):ProviderContacts
	{
		$sql = "SELECT id, name, position, email, commercial, otherphone
				FROM provider_contacts
				INNER JOIN provider_contact ON provider_contact.id = provider_contacts.idProviderContact
				WHERE provider_contacts.idProvider = ?
				ORDER BY name";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idProvider);

		$result = $query->execute();

		return $this->parseProviderContacts($result);
	}

	private function parseProviderContact(Result $result):?ProviderContact
	{
		if (!($providerContactArray = $this->parseSingleResult($result)))
			return null;

		$providerContact = $this->newProviderContact($providerContactArray);

		return $providerContact;
	}

	private function parseProviderContacts(Result $result):ProviderContacts
	{
		$providerContacts = new ProviderContacts();

		if (($providerContactsArray = $this->parseMultiplyResults($result)) !== null)
		foreach ($providerContactsArray as $providerContactArray)
		{
			$providerContact = $this->newProviderContact($providerContactArray);
			$providerContacts->add($providerContact);
		}

		return $providerContacts;
	}

	private function newProviderContact(array $providerContactArray):ProviderContact
	{
		$commercialID = intval($providerContactArray['commercial']); unset($providerContactArray['commercial']);
		$otherphoneID = intval($providerContactArray['otherphone']); unset($providerContactArray['otherphone']);

		$providerContact = new ProviderContact();
		$providerContact->fromArray($providerContactArray);

		if ($commercialID != 0) $providerContact->getCommercial()->setID($commercialID);
		if ($otherphoneID != 0) $providerContact->getOtherPhone()->setID($otherphoneID);

		return $providerContact;
	}
}

?>