<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\OrderItemService;
use tercom\entities\OrderRequest;
use tercom\entities\Service;
use tercom\entities\Provider;
use tercom\entities\lists\OrderItemServices;
use tercom\exceptions\OrderItemServiceException;

/**
 *
 *
 * @see GenericDAO
 * @see OrderRequest
 * @see OrderItemService
 * @see OrderItemServices
 *
 * @author Andrew
 */
class OrderItemServiceDAO extends GenericDAO
{
	/**
	 * @var array
	 */
	public const ALL_COLUMNS = ['id', 'idOrderRequest', 'idService', 'idProvider', 'betterPrice', 'observations'];

	/**
	 *
	 * @param OrderRequest|NULL $orderRequest
	 * @param OrderItemService $orderItemService
	 * @param bool $validateId
	 * @throws OrderItemServiceException
	 */
	private function validate(?OrderRequest $orderRequest, OrderItemService $orderItemService, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($orderItemService->getId() === 0)
				throw OrderItemServiceException::newNotIdentified();
		} else {
			if ($orderItemService->getId() !== 0)
				throw OrderItemServiceException::newIdentified();
		}

		// UNIQUE KEY
		if ($orderRequest !== null && $this->exist($orderRequest, $orderItemService->getService())) throw OrderItemServiceException::newExist();

		// NOT NULL
		if ($orderItemService->getServiceId() === 0) throw OrderItemServiceException::newServiceEmpty();

		// FOREIGN KEY
		if (!$this->existService($orderItemService->getService())) throw OrderItemServiceException::newServiceInvalid();
		if ($orderItemService->getProviderId() !== 0 && !$this->existProvider($orderItemService->getProvider())) throw OrderItemServiceException::newProviderInvalid();
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @param OrderItemService $orderItemService
	 * @throws OrderItemServiceException
	 * @return bool
	 */
	public function insert(OrderRequest $orderRequest, OrderItemService $orderItemService): bool
	{
		$this->validate($orderRequest, $orderItemService, false);

		$sql = "INSERT INTO order_item_services (idOrderRequest, idService, idProvider, betterPrice, observations)
				VALUES (?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $orderRequest->getId());
		$query->setInteger(2, $orderItemService->getServiceId());
		$query->setInteger(3, $this->parseNullID($orderItemService->getProviderId()));
		$query->setBoolean(4, $orderItemService->isBetterPrice());
		$query->setString(5, $orderItemService->getObservations());

		if (($result = $query->execute())->isSuccessful())
			$orderItemService->setId($result->getInsertID());

		return $orderItemService->getId() !== 0;
	}

	/**
	 *
	 * @param OrderItemService $orderItemService
	 * @throws OrderItemServiceException
	 * @return bool
	 */
	public function update(OrderItemService $orderItemService): bool
	{
		$this->validate(null, $orderItemService, true);

		$sql = "UPDATE order_item_services
				SET idProvider = ?, betterPrice = ?, observations = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $this->parseNullID($orderItemService->getProviderId()));
		$query->setBoolean(2, $orderItemService->isBetterPrice());
		$query->setString(3, $orderItemService->getObservations());
		$query->setInteger(4, $orderItemService->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param OrderItemService $orderItemService
	 * @throws OrderItemServiceException
	 * @return bool
	 */
	public function delete(OrderItemService $orderItemService): bool
	{
		$this->validate(null, $orderItemService, true);

		$sql = "DELETE FROM order_item_services
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderItemService->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @return bool
	 */
	public function deleteAll(OrderRequest $orderRequest): bool
	{
		$sql = "DELETE FROM order_item_services
				WHERE idOrderRequest = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @return string
	 */
	private function newSelect(): string
	{
		$orderItemServiceColumns = $this->buildQuery(self::ALL_COLUMNS, 'order_item_services');
		$serviceColumns = $this->buildQuery(ServiceDAO::ALL_COLUMNS, 'services', 'service');
		$providerColumns = $this->buildQuery(ProviderDAO::ALL_COLUMNS, 'providers', 'provider');

		return "SELECT $orderItemServiceColumns, $serviceColumns, $providerColumns
				FROM order_item_services
				INNER JOIN services ON services.id = order_item_services.idService
				LEFT JOIN providers ON providers.id = order_item_services.idProvider";
	}

	/**
	 *
	 * @param int $idOrderItemService
	 * @return OrderItemServices
	 */
	public function select(int $idOrderItemService): OrderItemService
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_item_services.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idOrderItemService);

		$result = $query->execute();

		return $this->parseOrderItemService($result);
	}

	/**
	 *
	 * @param int $idOrderRequest
	 * @param int $idOrderItemService
	 * @return OrderItemServices
	 */
	public function selectWithOrderRequest(int $idOrderRequest, int $idOrderItemService): ?OrderItemService
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_item_services.id = ? AND order_item_services.idOrderRequest = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idOrderItemService);
		$query->setInteger(2, $idOrderRequest);

		$result = $query->execute();

		return $this->parseOrderItemService($result);
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @return OrderItemServices
	 */
	public function selectAll(OrderRequest $orderRequest): OrderItemServices
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_item_services.idOrderRequest = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());

		$result = $query->execute();

		return $this->parseOrderItemServices($result);
	}

	/**
	 *
	 * @param OrderRequest $orderRequest
	 * @param Service $service
	 * @return bool
	 */
	public function exist(OrderRequest $orderRequest, Service $service): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM order_item_services
				WHERE idOrderRequest = ? AND idService = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());
		$query->setInteger(2, $service->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 *
	 * @param Service $service
	 * @return bool
	 */
	public function existService(Service $service): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM services
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $service->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 *
	 * @param Provider $provider
	 * @return bool
	 */
	public function existProvider(Provider $provider): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM providers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $provider->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 *
	 * @param Result $result
	 * @return OrderItemService|NULL
	 */
	private function parseOrderItemService(Result $result): ?OrderItemService
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newOrderItemService($entry);
	}

	/**
	 *
	 * @param Result $result
	 * @return OrderItemServices
	 */
	private function parseOrderItemServices(Result $result): OrderItemServices
	{
		$orderItemServices = new OrderItemServices();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$orderItemService = $this->newOrderItemService($entry);
			$orderItemServices->add($orderItemService);
		}

		return $orderItemServices;
	}

	/**
	 *
	 * @param array $entry
	 * @return OrderItemService
	 */
	private function newOrderItemService(array $entry): OrderItemService
	{
		$this->parseEntry($entry, 'service', 'provider');

		$orderItemService = new OrderItemService();
		$orderItemService->fromArray($entry);

		return $orderItemService;
	}
}

