<?php

namespace tercom\dao;

use dProject\MySQL\MySQL;
use dProject\MySQL\Result;
use tercom\entities\Provider;
use dProject\Primitive\IntegerUtil;
use tercom\entities\lists\Providers;

class ProviderDAO extends GenericDAO
{
	/**
	 * @var int
	 */
	private const PAGE_LENGTH = 10;
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
		$query->setEmptyAsNull(true);
		$query->setString(1, $provider->getCNPJ());
		$query->setString(2, $provider->getCompanyName());
		$query->setString(3, $provider->getFantasyName());
		$query->setString(4, $provider->getSpokesman());
		$query->setString(5, $provider->getSite());
		$query->setInteger(6, $provider->getCommercial()->getID() > 0 ? $provider->getCommercial()->getID() : null);
		$query->setInteger(7, $provider->getOtherPhone()->getID() > 0 ? $provider->getOtherPhone()->getID() : null);
		$query->setBoolean(8, $provider->isInactive());

		$result = $query->execute();

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
		$query->setString(8, $provider->isInactive());
		$query->setInteger(9, $provider->getID());

		$result = $query->execute();

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

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function updateInactive(Provider $provider):bool
	{
		$sql = "UPDATE provider SET inactive = ? WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setBoolean(1, $provider->isInactive());
		$query->setInteger(2, $provider->getID());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function selectByID(int $providerID):?Provider
	{
		$sql = "SELECT id, cnpj, companyName, fantasyName, spokesman, site, commercial, otherphone, inactive
				FROM providers
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $providerID);

		$result = $query->execute();

		return $this->parseProvider($result);
	}

	public function selectAll(): Providers
	{
		$sql = "SELECT id, cnpj, companyName, fantasyName, spokesman, site, commercial, otherphone, inactive
				FROM providers";

		$query = $this->mysql->createQuery($sql);
		$result = $query->execute();

		return $this->parseProviders($result);
	}

	public function selectByCNPJ(string $cnpj):Provider
	{
		$sql = "SELECT id, cnpj, companyName, fantasyName, spokesman, site, commercial, otherphone, inactive
				FROM providers
				WHERE cnpj = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $cnpj);

		$result = $query->execute();

		return $this->parseProvider($result);
	}

	public function searchByCNPJ(string $cnpj): Providers
	{
		$sql = "SELECT id, cnpj, companyName, fantasyName, spokesman, site, commercial, otherphone, inactive
				FROM providers
				WHERE cnpj LIKE ?
				ORDER BY fantasyName";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, "%$cnpj%");

		$result = $query->execute();

		return $this->parseProviders($result, true);
	}

	public function searchByFantasyName(string $fantasyName): Providers
	{
		$sql = "SELECT id, cnpj, companyName, fantasyName, spokesman, site, commercial, otherphone, inactive
				FROM providers
				WHERE fantasyName LIKE ?
				ORDER BY fantasyName";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, "%$fantasyName%");

		$result = $query->execute();

		return $this->parseProviders($result, true);
	}

	public function searchByPage(int $page): Providers
	{
		$sqlLimit = $page !== -1 ? $this->parsePage($page, self::PAGE_LENGTH) : '';
		$sql = "SELECT id, cnpj, companyName, fantasyName, spokesman, site, commercial, otherphone
				FROM providers
				WHERE inactive = 0
				ORDER BY id DESC
				$sqlLimit";

		$query = $this->mysql->createQuery($sql);
		$result = $query->execute();

		return $this->parseProviders($result, true);
	}

	public function calcPageCount(): int
	{
		$sql = "SELECT COUNT(*) AS qtd FROM providers WHERE inactive = 0";

		$query = $this->mysql->createQuery($sql);
		$result = $query->execute();
		$providers = $result->next();

		return ceil(intval($providers['qtd']) / self::PAGE_LENGTH);
	}

	public function existID(int $idProvider): bool
	{
		$sql = "SELECT COUNT(*) AS qtd FROM providers WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idProvider);

		$result = $query->execute();
		$providers = $result->next();

		return intval($providers['qtd']) === 1;
	}

	public function existCNPJ(string $cnpj, int $idProvider): bool
	{
		$sql = "SELECT COUNT(*) AS qtd FROM providers WHERE cnpj = ? AND id <> ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $cnpj);
		$query->setInteger(2, $idProvider);

		$result = $query->execute();
		$providers = $result->next();

		return intval($providers['qtd']) !== 0;
	}

	private function parseProvider(Result $result): ?Provider
	{
		if (($providerArray = $this->parseSingleResult($result)) == null)
			return null;

		$provider = $this->newProvider($providerArray);

		return $provider;
	}

	private function parseProviders(Result $result, bool $loadContatos = false): Providers
	{
		$providers = new Providers();

		while ($result->hasNext())
		{
			$providerArray = $result->next();
			$provider = $this->newProvider($providerArray);
			$providers->add($provider);
		}

		return $providers;
	}

	private function newProvider(array $providerArray):Provider
	{
		$commercialID = $providerArray['commercial']; unset($providerArray['commercial']);
		$otherphoneID = $providerArray['otherphone']; unset($providerArray['otherphone']);

		$provider = new Provider();
		$provider->fromArray($providerArray);

		if ($commercialID > 0) $provider->getCommercial()->setID(IntegerUtil::parse($commercialID));
		if ($otherphoneID) $provider->getOtherPhone()->setID(IntegerUtil::parse($otherphoneID));

		return $provider;
	}

	private function loadContatos(Provider $provider)
	{
		$contatos = $this->providerContactDAO->filterByProviderID($provider->getID());

		foreach ($contatos as $contato)
			$provider->getContatos()->addProviderContact($contato);
	}
}

?>