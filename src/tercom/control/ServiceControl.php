<?php

namespace tercom\control;

use tercom\dao\ServiceDAO;
use tercom\entities\Service;
use tercom\entities\lists\Services;
use dProject\MySQL\MySQL;

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
	public function __construct(MySQL $mysql)
	{
		$this->serviceDAO = new ServiceDAO($mysql);
	}

	/**
	 * @param Service $service
	 * @throws ControlException
	 * @return bool
	 */
	private function validate(Service $service, bool $validateID)
	{
		if ($validateID) {
			if ($service->getId() === 0)
				throw new ControlException('serviço não identificado');
		} else {
			if ($service->getId() !== 0)
				throw new ControlException('serviço já identificado');
		}

		if (!$this->avaiableName($service->getName(), $service->getId())) throw new ControlException('nome de serviço indisponível');
	}

	/**
	 * @param Service $service
	 * @return bool
	 */
	public function add(Service $service): bool
	{
		$this->validate($service, false);

		return $this->serviceDAO->insert($service);
	}

	/**
	 * @param Service $service
	 * @return bool
	 */
	public function set(Service $service): bool
	{
		$this->validate($service, true);

		return $this->serviceDAO->update($service);
	}

	/**
	 * @param int $idService
	 * @return Service|NULL
	 */
	public function get(int $idService): ?Service
	{
		return $this->serviceDAO->select($idService);
	}

	/**
	 * @return Services
	 */
	public function getAll(): Services
	{
		return $this->serviceDAO->selectAll();
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
		return $this->serviceDAO->countByName($name, $idService) === 0;
	}
}

