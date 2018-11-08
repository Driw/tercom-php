<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use tercom\dao\ProviderContactDAO;
use tercom\entities\Phone;
use tercom\entities\Provider;
use tercom\entities\ProviderContact;
use tercom\entities\lists\ProviderContacts;

class ProviderContactControl extends GenericControl
{
	private $phoneControl;
	private $providerContactDAO;

	public function __construct(MySQL $mysql)
	{
		$this->phoneControl = new PhoneControl($mysql);
		$this->providerContactDAO = new ProviderContactDAO($mysql);
	}

	private function validate(ProviderContact $providerContact, $validateID)
	{
		if ($validateID) {
			if ($providerContact->getID() < 1)
				throw new ControlException('contato de fornecedor não identificado');
		} else {
			if ($providerContact->getID() != 0)
				throw new ControlException('contato de fornecedor já identificado');
		}

		if (empty($providerContact->getName()))
			throw new ControlException('nome do contato de fornecedor em branco');
	}

	public function addProviderContact(Provider $provider, ProviderContact $providerContact):bool
	{
		$this->validate($providerContact, false);

		if ($this->providerContactDAO->insert($providerContact))
		{
			$this->providerContactDAO->linkContact($provider, $providerContact);
			$provider->getContacs()->add($providerContact);
			return true;
		}

		return false;
	}

	public function setProviderContact(ProviderContact $providerContact):bool
	{
		$this->validate($providerContact, true);
		$this->setPhones($providerContact);

		return $this->providerContactDAO->update($providerContact);
	}

	public function setPhones(ProviderContact $providerContact):bool
	{
		$phones = $providerContact->getPhones();

		if ($this->phoneControl->keepPhones($phones))
			return $this->providerContactDAO->updatePhones($providerContact);

		return false;
	}

	public function removeProviderContact(ProviderContact $providerContact):bool
	{
		$phones = $providerContact->getPhones();
		$this->phoneControl->removePhones($phones);

		return $this->providerContactDAO->delete($providerContact);
	}

	public function removeCommercial(ProviderContact $providerContact):bool
	{
		if ($providerContact->getCommercial()->getId() !== 0)
			if ($this->phoneControl->removePhone($providerContact->getCommercial()))
			{
				$providerContact->setCommercial(new Phone());
				return true;
			}

		return false;
	}

	public function removeOtherphone(ProviderContact $providerContact):bool
	{
		if ($providerContact->getOtherPhone()->getId() !== 0)
			if ($this->phoneControl->removePhone($providerContact->getOtherPhone()))
			{
				$providerContact->setOtherPhone(new Phone());
				return true;
			}

		return false;
	}

	public function getProvideContact(int $providerID, int $providerContactID):?ProviderContact
	{
		return $this->providerContactDAO->selectByID($providerID, $providerContactID);
	}

	public function getProvideContacts(int $providerID):ProviderContacts
	{
		return $this->providerContactDAO->selectByProvider($providerID);
	}

	public function loadProviderContacts(Provider $provider)
	{
		$provider->getContacs()->clear();

		foreach ($this->getProvideContacts($provider->getID()) as $providerContact)
		{
			$this->phoneControl->loadPhones($providerContact->getPhones());
			$provider->getContacs()->add($providerContact);
		}
	}
}

?>