<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use dProject\Primitive\StringUtil;
use tercom\dao\ServicePriceDAO;
use tercom\entities\ServicePrice;
use tercom\entities\lists\ServicePrices;

/**
 * @see GenericControl
 * @see ServicePriceDAO
 * @see ServicePrice
 * @author Andrew
 *
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
	public function __construct(MySQL $mysql)
	{
		$this->servicePriceDAO = new ServicePriceDAO($mysql);
	}

	private function validate(ServicePrice $servicePrice, bool $validateID)
	{
		if ($validateID) {
			if ($servicePrice->getId() === 0)
				throw new ControlException('preço de serviço não identificado');
		} else {
			if ($servicePrice->getId() !== 0)
				throw new ControlException('preço de serviço já identificado');
		}

		if ($servicePrice->getIdService() === 0) throw new ControlException('serviço não informado');
		if ($servicePrice->getIdProvider() === 0) throw new ControlException('fornecedor não informado');
		if (StringUtil::isEmpty($servicePrice->getName())) throw new ControlException('nome não informado');
	}

	public function add(ServicePrice $servicePrice): bool
	{
		$this->validate($servicePrice, false);

		return $this->servicePriceDAO->insert($servicePrice);
	}

	public function set(ServicePrice $servicePrice): bool
	{
		$this->validate($servicePrice, true);

		return $this->servicePriceDAO->update($servicePrice);
	}

	public function remove(ServicePrice $servicePrice): bool
	{
		$this->validate($servicePrice, true);

		return $this->servicePriceDAO->delete($servicePrice);
	}

	public function get(int $idServicePrice): ServicePrice
	{
		return $this->servicePriceDAO->select($idServicePrice);
	}

	public function getByService(int $idService): ServicePrices
	{
		return $this->servicePriceDAO->selectByService($idService);
	}

	public function getByProvider(int $idService, int $idProvider): ServicePrices
	{
		return $this->servicePriceDAO->selectByProvider($idService, $idProvider);
	}
}

