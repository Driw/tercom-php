<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\entities\OrderAcceptance;
use tercom\entities\OrderAcceptanceService;
use tercom\entities\Service;
use tercom\entities\Provider;
use tercom\entities\lists\OrderAcceptanceServices;
use tercom\exceptions\OrderAcceptanceServiceException;

/**
 * @author Andrew
 */
class OrderAcceptanceServiceDAO extends GenericDAO
{
	public const ALL_COLUMNS = [
		'id', 'idQuotedServicePrice', 'idService', 'idProvider',
		'name', 'amountRequest', 'price', 'subprice', 'additionalDescription', 'observations', 'lastUpdate'
	];

	private function validate(?OrderAcceptance $orderAcceptance, OrderAcceptanceService $orderAcceptanceService, bool $validaId): void
	{
		// PRIMARY KEY
		if ($validaId) {
			if ($orderAcceptanceService->getId() === 0)
				throw OrderAcceptanceServiceException::newNotIdentified();
		} else {
			if ($orderAcceptanceService->getId() !== 0)
				throw OrderAcceptanceServiceException::newIdentified();
		}

		// UNIQUE KEY
		if ($orderAcceptance !== null)
			if ($this->existQuotedPrice($orderAcceptance, $orderAcceptanceService)) throw OrderAcceptanceServiceException::newQuotedPriceUsed();

		// NOT NULL
		if ($orderAcceptance !== null && $orderAcceptance->getId() === 0) throw OrderAcceptanceServiceException::newAcceptanceEmpty();
		if (StringUtil::isEmpty($orderAcceptanceService->getName())) throw OrderAcceptanceServiceException::newNameEmpty();
		if ($orderAcceptanceService->getPrice() === 0.0) throw OrderAcceptanceServiceException::newPriceEmpty();
		if ($orderAcceptanceService->getAmountRequest() === 0) throw OrderAcceptanceServiceException::newAmountRequestEmpty();
		if ($orderAcceptanceService->getSubprice() === 0.0) throw OrderAcceptanceServiceException::newSubpriceEmpty();
		if ($orderAcceptanceService->getServiceId() === 0) throw OrderAcceptanceServiceException::newServiceEmpty();
		if ($orderAcceptanceService->getProviderId() === 0) throw OrderAcceptanceServiceException::newProviderEmpty();

		// FOREIGN KEY
		if ($orderAcceptance !== null)
			if (!$this->existOrderAcceptance($orderAcceptance)) throw OrderAcceptanceServiceException::newAcceptanceInvalid();
		if (!$this->existService($orderAcceptanceService->getService())) throw OrderAcceptanceServiceException::newServiceInvalid();
		if (!$this->existProvider($orderAcceptanceService->getProvider())) throw OrderAcceptanceServiceException::newProviderInvalid();
	}

	public function insert(OrderAcceptance $orderAcceptance, OrderAcceptanceService $orderAcceptanceService): bool
	{
		$this->validate($orderAcceptance, $orderAcceptanceService, false);

		$sql = "INSERT INTO order_acceptance_services (
					idOrderAcceptance, idQuotedServicePrice, idService, idProvider,
					name, price, amountRequest, subprice, additionalDescription, observations, lastUpdate
				) VALUES (
					?, ?, ?, ?, ?,
					?, ?, ?, ?, ?, ?
				)";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $orderAcceptance->getId());
		$query->setInteger(2, $orderAcceptanceService->getIdQuotedServicePrice());
		$query->setInteger(3, $orderAcceptanceService->getServiceId());
		$query->setInteger(4, $orderAcceptanceService->getProviderId());
		$query->setString(5, $orderAcceptanceService->getName());
		$query->setFloat(6, $orderAcceptanceService->getPrice());
		$query->setInteger(7, $orderAcceptanceService->getAmountRequest());
		$query->setFloat(8, $orderAcceptanceService->getSubprice());
		$query->setString(9, $orderAcceptanceService->getAdditionalDescription());
		$query->setString(10, $orderAcceptanceService->getObservations());
		$query->setDateTime(11, $orderAcceptanceService->getLastUpdate());

		if (($result = $query->execute())->isSuccessful())
			$orderAcceptanceService->setId($result->getInsertID());

		return $orderAcceptanceService->getId() !== 0;
	}

	public function update(OrderAcceptanceService $orderAcceptanceService): bool
	{
		$this->validate(null, $orderAcceptanceService, true);

		$sql = "UPDATE order_acceptance_services
				SET amountRequest = ?, subprice = ?, observations = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $orderAcceptanceService->getAmountRequest());
		$query->setFloat(2, $orderAcceptanceService->getSubprice());
		$query->setString(3, $orderAcceptanceService->getObservations());
		$query->setInteger(4, $orderAcceptanceService->getId());

		return ($query->execute())->isSuccessful();
	}

	public function delete(OrderAcceptanceService $orderAcceptanceService): bool
	{
		$sql = "DELETE FROM order_acceptance_services
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderAcceptanceService->getId());

		return ($query->execute())->isSuccessful();
	}

	public function deleteAll(OrderAcceptance $orderAcceptance): bool
	{
		$sql = "DELETE FROM order_acceptance_services
				WHERE idOrderAcceptance = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderAcceptance->getId());

		return ($query->execute())->isSuccessful();
	}

	private function newSelect(): string
	{
		$orderAcceptanceService = $this->buildQuery(self::ALL_COLUMNS, 'order_acceptance_services');
		$serviceColumns = $this->buildQuery(ServiceDAO::ALL_COLUMNS, 'services', 'service');
		$serviceProviderColumns = $this->buildQuery(ProviderDAO::ALL_COLUMNS, 'providers', 'provider');

		return "SELECT $orderAcceptanceService, $serviceColumns, $serviceProviderColumns
				FROM order_acceptance_services
				INNER JOIN services ON services.id = order_acceptance_services.idService
				INNER JOIN providers ON providers.id = order_acceptance_services.idProvider";
	}

	public function select(int $idOrderAcceptanceService, int $idOrderAcceptance = 0): ?OrderAcceptanceService
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_acceptance_services.id = ? AND ? IN (0, order_acceptance_services.idOrderAcceptance)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idOrderAcceptanceService);
		$query->setInteger(2, $idOrderAcceptance);

		$result = $query->execute();

		return $this->parseOrderAcceptanceService($result);
	}

	public function selectByOrderAcceptance(OrderAcceptance $orderAcceptance): OrderAcceptanceServices
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_acceptance_services.idOrderAcceptance = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderAcceptance->getId());

		$result = $query->execute();

		return $this->parseOrderAcceptanceServices($result);
	}

	private function existQuotedPrice(OrderAcceptance $orderAcceptance, OrderAcceptanceService $orderAcceptanceService): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM order_acceptance_services
				WHERE idOrderAcceptance = ? AND idQuotedServicePrice = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderAcceptance->getId());
		$query->setInteger(2, $orderAcceptanceService->getIdQuotedServicePrice());

		return $this->parseQueryExist($query);
	}

	private function existOrderAcceptance(OrderAcceptance $orderAcceptance): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM order_acceptances
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderAcceptance->getId());

		return $this->parseQueryExist($query);
	}

	private function existService(Service $service): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM services
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $service->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação de fornecedor existe.
	 * @param Provider $provider objeto do tipo fornecedor à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	private function existProvider(Provider $provider): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM providers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $provider->getId());

		return $this->parseQueryExist($query);
	}

	private function parseOrderAcceptanceService(Result $result): ?OrderAcceptanceService
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newOrderAcceptanceService($entry);
	}

	private function parseOrderAcceptanceServices(Result $result): OrderAcceptanceServices
	{
		$orderAcceptanceServices = new OrderAcceptanceServices();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$orderAcceptanceService = $this->newOrderAcceptanceService($entry);
			$orderAcceptanceServices->add($orderAcceptanceService);
		}

		return $orderAcceptanceServices;
	}

	private function newOrderAcceptanceService(array $entry): OrderAcceptanceService
	{
		$this->parseEntry($entry, 'service', 'provider');

		$orderAcceptanceService = new OrderAcceptanceService();
		$orderAcceptanceService->fromArray($entry);

		return $orderAcceptanceService;
	}
}

