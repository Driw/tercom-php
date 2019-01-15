<?php

namespace tercom\control;

use tercom\dao\ServicePriceDAO;
use tercom\entities\ServicePrice;
use tercom\entities\lists\ServicePrices;
use tercom\entities\OrderItemService;
use tercom\TercomException;

/**
 * @see GenericControl
 * @see ServicePriceDAO
 * @see ServicePrice
 * @author Andrew
 */
class ServicePriceControl extends GenericControl
{
	/**
	 * @var ServicePriceDAO
	 */
	private $servicePriceDAO;

	/**
	 *
	 */
	public function __construct()
	{
		$this->servicePriceDAO = new ServicePriceDAO();
	}

	public function add(ServicePrice $servicePrice): void
	{
		if (!$this->servicePriceDAO->insert($servicePrice))
			throw new ControlException('não foi possível adicionar o preço de serviço');
	}

	public function set(ServicePrice $servicePrice): void
	{
		if (!$this->servicePriceDAO->update($servicePrice))
			throw new ControlException('não foi possível atualizar o preço de serviço');
	}

	public function remove(ServicePrice $servicePrice): void
	{
		if (!$this->servicePriceDAO->delete($servicePrice))
			throw new ControlException('não foi possível exlcuir o preço de serviço');
	}

	public function get(int $idServicePrice): ServicePrice
	{
		if (($servicePrice = $this->servicePriceDAO->select($idServicePrice)) === null)
			throw new ControlException('preço de serviço não encontrado');

		return $servicePrice;
	}

	public function searchByService(int $idService): ServicePrices
	{
		return $this->servicePriceDAO->selectByService($idService);
	}

	public function searchByProvider(int $idProvider): ServicePrices
	{
		return $this->servicePriceDAO->selectByProvider($idProvider);
	}

	public function searchByItem(OrderItemService $orderItemService): ServicePrices
	{
		if (!$this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		return $this->servicePriceDAO->selectByItem($orderItemService);
	}
}

