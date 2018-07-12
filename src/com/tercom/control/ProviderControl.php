<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use tercom\DAO\ProviderDAO;
use tercom\entities\Phone;
use tercom\entities\Provider;

class ProviderControl extends GenericControl
{
	private $phoneControl;
	private $providerDAO;

	public function __construct(MySQL $mysql)
	{
		$this->phoneControl = new PhoneControl($mysql);
		$this->providerDAO = new ProviderDAO($mysql);
	}

	public function add(Provider $provider):bool
	{
		return $this->providerDAO->insert($provider);
	}

	public function set(Provider $provider):bool
	{
		return $this->providerDAO->update($provider);
	}

	public function setPhones(Provider $provider):bool
	{
		$keepCommercial = $this->keepPhone($provider->getCommercial());
		$keepOtherphone = $this->keepPhone($provider->getOtherPhone());

		if ($keepCommercial || $keepOtherphone)
			return $this->providerDAO->updatePhones($provider);

		return false;
	}

	public function removeCommercial(Provider $provider):bool
	{
		if ($provider->getCommercial()->getID() === 0)
			return false;

		if ($this->phoneControl->remove($provider->getCommercial()))
			$provider->setCommercial(new Phone());

		return $this->providerDAO->updatePhones($provider);
	}

	public function removeOtherphone(Provider $provider):bool
	{
		if ($provider->getOtherPhone()->getID() === 0)
			return false;

		if ($this->phoneControl->remove($provider->getOtherPhone()))
			$provider->setOtherPhone(new Phone());

		return $this->providerDAO->updatePhones($provider);
	}

	private function keepPhone(Phone $phone):bool
	{
		if ($phone->getID() === 0)
			return $this->phoneControl->addPhone($phone);

		else if ($phone->getID() !== 0)
			return $this->phoneControl->setPhone($phone);

		return false;
	}

	public function enable(Provider $provider):bool
	{
		$provider->setInactive(false);

		return $this->providerDAO->updateInactive($provider);
	}

	public function disable(Provider $provider):bool
	{
		$provider->setInactive(true);

		return $this->providerDAO->updateInactive($provider);
	}

	public function get(int $providerID)
	{
		return $this->providerDAO->selectByID($providerID);
	}

	public function getByCNPJ(string $cnpj)
	{
		return $this->providerDAO->selectByCNPJ($cnpj);
	}

	public function listByCNPJ(string $cnpj)
	{
		$this->providerDAO->filterByCNPJ($cnpj);
	}

	public function listByFantasyName(string $fantasyName)
	{
		return $this->providerDAO->filterByFantasyName($fantasyName);
	}
}

?>