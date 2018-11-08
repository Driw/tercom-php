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
		if ($provider->getCommercial()->getId() !== 0)
			if ($this->phoneControl->removePhone($provider->getCommercial()))
			{
				$provider->setCommercial(new Phone());
				return true;
			}

		return false;
	}

	public function removeOtherphone(Provider $provider):bool
	{
		if ($provider->getOtherPhone()->getId() !== 0)
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

	public function getAll(): Providers
	{
		return $this->providerDAO->selectAll();
	}

	public function getByCNPJ(string $cnpj): ?Provider
	{
		return $this->providerDAO->selectByCNPJ($cnpj);
	}

	public function getByPage(int $page): Providers
	{
		return $this->providerDAO->searchByPage($page);
	}

	public function getPageCount(): int
	{
		return $this->providerDAO->calcPageCount();
	}

	public function filterByCNPJ(string $cnpj): Providers
	{
		return $this->providerDAO->searchByCNPJ($cnpj);
	}

	public function filterByFantasyName(string $fantasyName): Providers
	{
		return $this->providerDAO->searchByFantasyName($fantasyName);
	}

	public function has(int $idProvider)
	{
		return $this->providerDAO->existID($idProvider);
	}

	public function hasAvaiableCNPJ(string $cnpj, int $idProvider): bool
	{
		if (!Functions::validateCNPJ($cnpj))
			throw new ControlValidationException('CNPJ inválido');

		return !$this->providerDAO->existCNPJ($cnpj, $idProvider);
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