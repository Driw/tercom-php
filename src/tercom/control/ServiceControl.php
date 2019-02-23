<?php

namespace tercom\control;

use tercom\dao\ServiceDAO;
use tercom\entities\Service;
use tercom\entities\lists\Services;
use dProject\MySQL\MySQL;
use tercom\api\exceptions\ServiceException;

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
	public function setCustomerId(Service $service): void
	{
		if (!$this->serviceDAO->replaceCustomerId($this->getCustomerLogged(), $service))
			throw ServiceException::newCustomerId();
	}

	/**
	 * @param int $idService
	 * @return Service
	 */
	public function get(int $idService): Service
	{
		if (($service = $this->serviceDAO->select($idService)) === null)
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

	/**
	 * @param string $name
	 * @param int $idService
	 * @return bool
	 */
	public function avaiableName(string $name, int $idService = 0): bool
	{
		return !$this->serviceDAO->existName($name, $idService);
	}

	public static function getFilters(): array
	{
		return [
			'name' => 'Nome',
		];
	}
}

