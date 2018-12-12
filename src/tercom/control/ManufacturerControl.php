<?php

namespace tercom\control;

use tercom\dao\ManufacturerDAO;
use tercom\entities\Manufacturer;
use tercom\entities\lists\Manufacturers;
use tercom\exceptions\ManufacturerException;
use tercom\dao\ProductPriceDAO;

class ManufacturerControl extends GenericControl
{
	/**
	 * @var ManufacturerDAO
	 */
	private $manufactureDAO;
	/**
	 * @var ProductPriceDAO
	 */
	private $productPriceDAO;

	public function __construct()
	{
		$this->manufactureDAO = new ManufacturerDAO();
		$this->productPriceDAO = new ProductPriceDAO();
	}

	public function add(Manufacturer $manufacturer)
	{
		return $this->manufactureDAO->insert($manufacturer);
	}

	public function set(Manufacturer $manufacturer)
	{
		return $this->manufactureDAO->update($manufacturer);
	}

	public function remove(Manufacturer $manufacturer)
	{
		if ($this->productPriceDAO->existManufacturer($manufacturer->getId()))
			throw ManufacturerException::newHasUses();

		return $this->manufactureDAO->dalete($manufacturer);
	}

	public function get(int $idManufacturer): Manufacturer
	{
		if (($manufacturer = $this->manufactureDAO->select($idManufacturer)) === null)
			throw ManufacturerException::newNotFound();

		return $manufacturer;
	}

	public function getAll(): Manufacturers
	{
		return $this->manufactureDAO->selectAll();
	}

	public function searchByFantasyName(string $fantasyName, int $amount = 5): Manufacturers
	{
		return $this->manufactureDAO->selectLikeFantasyName($fantasyName, $amount);
	}

	public function has(int $idManufacturer): bool
	{
		return $this->manufactureDAO->exist($idManufacturer);
	}

	public function hasFantasyName(string $fantasyName, int $idManufacturer = 0): bool
	{
		return !$this->manufactureDAO->existFantasyName($fantasyName, $idManufacturer);
	}

	public static function getFilters(): array
	{
		return [
			'fantasyName' => 'Nome Fantasia',
		];
	}
}

