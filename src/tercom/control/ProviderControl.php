<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use tercom\dao\ProviderDAO;
use tercom\entities\Phone;
use tercom\entities\Provider;
use tercom\entities\lists\Providers;
use tercom\Functions;

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
		if ($this->phoneControl->keepPhones($provider->getPhones()))
			return $this->providerDAO->updatePhones($provider);

		return false;
	}

	public function removeCommercial(Provider $provider):bool
	{
		if ($provider->getCommercial()->getID() !== 0)
			if ($this->phoneControl->removePhone($provider->getCommercial()))
			{
				$provider->setCommercial(new Phone());
				return true;
			}

		return false;
	}

	public function removeOtherphone(Provider $provider):bool
	{
		if ($provider->getOtherPhone()->getID() !== 0)
			if ($this->phoneControl->removePhone($provider->getOtherPhone()))
			{
				$provider->setOtherPhone(new Phone());
				return true;
			}

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

	public function get(int $providerID): ?Provider
	{
		return $this->providerDAO->selectByID($providerID);
	}

	public function getByCNPJ(string $cnpj): ?Provider
	{
		return $this->providerDAO->selectByCNPJ($cnpj);
	}

	public function listByCNPJ(string $cnpj): Providers
	{
		return $this->providerDAO->searchByCNPJ($cnpj);
	}

	public function listByFantasyName(string $fantasyName): Providers
	{
		return $this->providerDAO->searchByFantasyName($fantasyName);
	}

	public function listByPage(int $page): Providers
	{
		return $this->providerDAO->searchByPage($page);
	}

	public function getPageCount(): int
	{
		return $this->providerDAO->calcPageCount();
	}

	public function avaiableCNPJ(string $cnpj): bool
	{
		if (!Functions::validateCNPJ($cnpj))
			throw new ControlValidationException('CNPJ inválido');

		return !$this->providerDAO->existCNPJ($cnpj);
	}

	public static function getFilters(): array
	{
		return [
			'fantasyName' => 'Nome Fantasia',
			'cnpj' => 'CNPJ',
		];
	}
}

?>