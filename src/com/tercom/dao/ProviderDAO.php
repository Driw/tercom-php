<?php

namespace tercom\DAO;

use dProject\MySQL\MySQL;
use dProject\MySQL\Result;
use tercom\entities\Provider;
use dProject\Primitive\IntegerUtil;

class ProviderDAO extends GenericDAO
{
	/**
	 * @var ProviderContactDAO
	 */
	private $providerContactDAO;

	public function __construct(MySQL $mysql)
	{
		parent::__construct($mysql);

		$this->providerContactDAO = new ProviderContactDAO($mysql);
	}

	public function insert(Provider $provider):bool
	{
		$sql = "INSERT INTO providers (cnpj, companyName, fantasyName, spokesman, site, commercial, otherphone, inactive)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $provider->getCNPJ());
		$query->setString(2, $provider->getCompanyName());
		$query->setString(3, $provider->getFantasyName());
		$query->setString(4, $provider->getSpokesman());
		$query->setString(5, $provider->getSite());
		$query->setInteger(6, $provider->getCommercial() != null ? $provider->getCommercial()->getID() : null);
		$query->setInteger(7, $provider->getOtherPhone() != null ? $provider->getOtherPhone()->getID() : null);
		$query->setBoolean(8, $provider->isInactive());

		$result = $query->execute(true);

		if ($result->isSuccessful())
			$provider->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(Provider $provider):bool
	{
		$sql = "UPDATE providers
				SET cnpj = ?, companyName = ?, fantasyName = ?, spokesman = ?, site = ?, commercial = ?, otherphone = ?, inactive = ?
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $provider->getCNPJ());
		$query->setString(2, $provider->getCompanyName());
		$query->setString(3, $provider->getFantasyName());
		$query->setString(4, $provider->getSpokesman());
		$query->setString(5, $provider->getSite());
		$query->setInteger(6, $provider->getCommercial()->getID() > 0 ? $provider->getCommercial()->getID() : null);
		$query->setInteger(7, $provider->getOtherPhone()->getID() > 0 ? $provider->getOtherPhone()->getID() : null);
		$query->setString(8, $provider->isInactive() ? 'yes' : 'no');
		$query->setInteger(9, $provider->getID());

		$result = $query->execute(true);

		return $result->isSuccessful();
	}

	public function updatePhones(Provider $provider):bool
	{
		$sql = "UPDATE providers
				SET commercial = ?, otherphone = ?
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $provider->getCommercial()->getID() > 0 ? $provider->getCommercial()->getID() : null);
		$query->setInteger(2, $provider->getOtherPhone()->getID() > 0 ? $provider->getOtherPhone()->getID() : null);
		$query->setInteger(3, $provider->getID());

		$result = $query->execute(true);

		return $result->isSuccessful();
	}

	public function updateInactive(Provider $provider):bool
	{
		$sql = "UPDATE provider SET inactive = ? WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setBoolean(1, $provider->isInactive());
		$query->setInteger(2, $provider->getID());

		$result = $query->execute(true);

		return $result->isSuccessful();
	}

	public function selectByID(int $providerID):?Provider
	{
		$sql = "SELECT id, cnpj, companyName, fantasyName, spokesman, site, commercial, otherphone, inactive
				FROM providers
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $providerID);

		$result = $query->execute(true);

		return $this->parseProvider($result, true);
	}

	public function selectByCNPJ(string $cnpj):Provider
	{
		$sql = "SELECT id, cnpj, companyName, fantasyName, spokesman, site, commercial, otherphone, inactive
				FROM providers
				WHERE cnpj = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $cnpj);

		$result = $query->execute(true);

		return $this->parseProvider($result, true);
	}

	public function filterByCNPJ(string $cnpj):array
	{
		$sql = "SELECT id, cnpj, companyName, fantasyName, spokesman, site, commercial, otherphone, inactive
				FROM providers
				WHERE cnpj LIKE ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, "%$cnpj%");

		return $this->parseProviders($result, true);
	}

	public function filterByFantasyName(string $fantasyName):array
	{
		$sql = "SELECT id, cnpj, companyName, fantasyName, spokesman, site, commercial, otherphone, inactive
				FROM providers
				WHERE fantasyName LIKE ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, "%$fantasyName%");

		$result = $query->execute(true);

		return $this->parseProviders($result, true);
	}

	private function parseProvider(Result $result, $loadProviderContacts):?Provider
	{
		if (($providerArray = $this->parseSingleResult($result)) == null)
			return null;

		$provider = $this->newProvider($providerArray);

		return $provider;
	}

	private function parseProviders(Result $result, bool $loadContatos):?array
	{
		$result = $query->execute(true);
		$provideres = [];

		if (($provideresArray = $this->parseSingleResult($result)) == null)
			return null;

		foreach ($provideresArray as $providerArray)
		{
			$provider = $this->newProvider($providerArray);
			array_push($provideres, $provider);

			if ($loadContatos)
				$this->loadContatos($provider);
		}

		return $providers;
	}

	private function newProvider(array $providerArray):Provider
	{
		$commercialID = $providerArray['commercial']; unset($providerArray['commercial']);
		$otherphoneID = $providerArray['otherphone']; unset($providerArray['otherphone']);

		$provider = new Provider();
		$provider->fromArray($providerArray);
		$provider->getCommercial()->setID(IntegerUtil::parse($commercialID));
		$provider->getOtherPhone()->setID(IntegerUtil::parse($otherphoneID));

		return $provider;
	}

	private function loadContatos(Provider $provider)
	{
		$contatos = $this->providerContactDAO->filterByProviderID($provider->getID());

		foreach ($contatos as $contato)
			$provider->getContatos()->add($contato);
	}
}

?>