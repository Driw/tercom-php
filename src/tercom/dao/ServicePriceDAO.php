<?php

namespace tercom\dao;

use tercom\entities\ServicePrice;
use tercom\entities\lists\ServicePrices;
use dProject\MySQL\Result;

class ServicePriceDAO extends GenericDAO
{
	public function insert(ServicePrice $servicePrice): bool
	{
		$sql = "INSERT INTO service_values (idService, idProvider, name, additionalDescription, price)
				VALUES (?, ?, ?, ?, ?)";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $servicePrice->getIdService());
		$query->setInteger(2, $servicePrice->getIdProvider());
		$query->setString(3, $servicePrice->getName());
		$query->setString(4, $servicePrice->getAdditionalDescription());
		$query->setFloat(5, $servicePrice->getPrice());
		$query->setEmptyAsNull(true);

		$result = $query->execute();

		if ($result->isSuccessful())
			$servicePrice->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(ServicePrice $servicePrice): bool
	{
		$sql = "UPDATE service_values
				SET name = ?, additionalDescription = ?, price = ?
				WHERE idService = ? AND idProvider = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $servicePrice->getName());
		$query->setString(2, $servicePrice->getAdditionalDescription());
		$query->setFloat(3, $servicePrice->getPrice());
		$query->setInteger(4, $servicePrice->getIdService());
		$query->setInteger(5, $servicePrice->getIdProvider());
		$query->setEmptyAsNull(true);

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function delete(ServicePrice $servicePrice): bool
	{
		$sql = "DELETE FROM service_values
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $servicePrice->getID());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function select(int $idServicePrice): ServicePrice
	{
		$sql = "SELECT id, idService, idProvider, name, additionalDescription, price
				FROM service_values
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idServicePrice);

		$result = $query->execute();

		return $this->parseServicePrice($result);
	}

	public function selectByService(int $idService): ServicePrices
	{
		$sql = "SELECT id, idService, idProvider, name, additionalDescription, price
				FROM service_values
				WHERE idService = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idService);

		$result = $query->execute();

		return $this->parseServicePrices($result);
	}

	public function selectByProvider(int $idService, int $idProvider): ?ServicePrice
	{
		$sql = "SELECT id, idService, idProvider, name, additionalDescription, price
				FROM service_values
				WHERE idService = ? AND idProvider";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idService);
		$query->setInteger(2, $idProvider);

		$result = $query->execute();

		return $this->parseServicePrices($result);
	}

	private function parseServicePrice(Result $result): ?ServicePrice
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$servicePrice = $this->newServicePrice($array);

		return $servicePrice;
	}

	private function parseServicePrices(Result $result): ServicePrices
	{
		$servicePrices = new ServicePrices();

		while ($result->hasNext())
		{
			$array = $result->next();
			$servicePrice = $this->newServicePrice($array);
			$servicePrices->add($servicePrice);
		}

		return $servicePrices;
	}

	private function newServicePrice(array $entry): ServicePrice
	{
		$idService = intval($entry['idService']);
		$idProvider = intval($entry['idProvider']);

		$servicePrice = new ServicePrice();
		$servicePrice->fromArray($entry);
		$servicePrice->getService()->setId($idService);
		$servicePrice->getProvider()->setID($idProvider);

		return $servicePrice;
	}
}

