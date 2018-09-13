<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use tercom\dao\ManufactureDAO;
use tercom\entities\Manufacture;
use tercom\entities\lists\Manufactures;

class ManufactureControl extends GenericControl
{
	/**
	 * @var ManufactureDAO
	 */
	private $manufactureDAO;

	public function __construct(MySQL $mysql)
	{
		$this->manufactureDAO = new ManufactureDAO($mysql);
	}

	private function validate(Manufacture $manufacture, bool $validateID)
	{
		if ($validateID) {
			if ($manufacture->getID() === 0)
				throw new ControlException('fabricante não identificado');
		} else {
			if ($manufacture->getID() !== 0)
				throw new ControlException('fornecedor já identificado');
		}

		if (empty($manufacture->getFantasyName()))
			throw new ControlException('nome do fabricante não definido');
	}

	public function add(Manufacture $manufacture)
	{
		$this->validate($manufacture, false);

		return $this->manufactureDAO->insert($manufacture);
	}

	public function set(Manufacture $manufacture)
	{
		$this->validate($manufacture, true);

		return $this->manufactureDAO->update($manufacture);
	}

	public function remove(Manufacture $manufacture)
	{
		return $this->manufactureDAO->dalete($manufacture);
	}

	public function get(int $idManufacture):?Manufacture
	{
		if ($idManufacture < 1)
			throw new ControlException('identificação inválida');

		return $this->manufactureDAO->select($idManufacture);
	}

	public function getAll(): Manufactures
	{
		return $this->manufactureDAO->selectAll();
	}

	public function listByFantasyName(string $fantasyName, int $amount = 5): Manufactures
	{
		return $this->manufactureDAO->searchByFantasyName($fantasyName, $amount);
	}

	public function has(int $idManufacture): bool
	{
		return $this->manufactureDAO->existID($idManufacture);
	}

	public function hasAvaiableFantasyName(string $fantasyName, int $idManufacturer = 0): bool
	{
		return !$this->manufactureDAO->existName($fantasyName, $idManufacturer);
	}

	public static function getFilters(): array
	{
		return [
			'fantasyName' => 'Nome Fantasia',
		];
	}
}

