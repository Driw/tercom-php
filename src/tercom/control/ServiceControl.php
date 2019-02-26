<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use tercom\dao\ServiceDAO;
use tercom\entities\Customer;
use tercom\entities\Service;
use tercom\entities\lists\Services;
use tercom\exceptions\ServiceException;

/**
 * @see GenericControl
 * @see ServiceDAO
 * @see Service
 * @author Andrew
 */
class ServiceControl extends GenericControl
{
	/**
	 * @var ServiceDAO
	 */
	private $serviceDAO;

	/**
	 * @param MySQL $mysql
	 */
	public function __construct()
	{
		$this->serviceDAO = new ServiceDAO();
	}

	/**
	 * @param Service $service
	 */
	public function add(Service $service): void
	{
		if (!$this->serviceDAO->insert($service))
			throw ServiceException::newNotAdd();
	}

	/**
	 * @param Service $service
	 */
	public function set(Service $service): void
	{
		if (!$this->serviceDAO->update($service))
			throw ServiceException::newNotSet();
	}

	/**
	 * @param Service $service
	 */
	public function setCustomerId(Service $service, ?Customer $customer = null): void
	{
		if ($customer === null)
			$customer = $this->getCustomerLogged();

		if (!$this->serviceDAO->replaceCustomerId($customer, $service))
			throw ServiceException::newCustomerId();
	}

	/**
	 * @param int $idService
	 * @return Service
	 */
	public function get(int $idService): Service
	{
		if (($service = (
			$this->isTercomManagement() ?
				$this->serviceDAO->select($idService) : 
				$this->serviceDAO->select($idService, $this->getCustomerLogged())
		)) === null)
			throw ServiceException::newNotFound();

		return $service;
	}

	/**
	 * @return Services
	 */
	public function getAll(): Services
	{
		return $this->isTercomManagement() ? $this->serviceDAO->selectAll() : $this->serviceDAO->selectAllWithCustomer($this->getCustomerLogged());
	}

	/**
	 * @param string $name
	 * @return Services
	 */
	public function filterByName(string $name): Services
	{
		return $this->serviceDAO->selectByName($name);
	}

	public function searchByCustomId(string $idServiceCustomer): Services
	{
		$customer = $this->getCustomerLogged();

		return $this->serviceDAO->selectLikeIdCustom($idServiceCustomer, $customer);
	}

	/**
	 * @param string $name
	 * @param int $idService
	 * @return bool
	 */
	public function avaiableName(string $name, int $idService = 0): bool
	{
		return !$this->serviceDAO->existName($name, $idService);
	}

	public function hasIdServiceCustomer(Service $service): bool
	{
		$customer = $this->getCustomerLogged();

		return $this->serviceDAO->existIdServiceCustomer($service, $customer);
	}

	public static function getFilters(): array
	{
		return [
			'idServiceCustomer' => 'Cliente Serviço ID',
			'name' => 'Nome',
		];
	}
}

