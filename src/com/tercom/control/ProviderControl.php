<?php

namespace tercom\control;

use tercom\entities\Provider;
use tercom\DAO\ProviderDAO;
use dProject\MySQL\MySQL;

class ProviderControl extends GenericControl
{
	private $providerDAO;

	public function __construct(MySQL $mysql)
	{
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