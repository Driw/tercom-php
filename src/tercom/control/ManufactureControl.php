<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use dProject\Primitive\StringUtil;
use tercom\entities\Manufacture;
use tercom\dao\ManufactureDAO;
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

	public function getByFantasyName(string $fantasyName, int $amount = 5):Manufactures
	{
		if (empty($fantasyName) || !StringUtil::hasMinLength($fantasyName, MIN_FANTASY_NAME))
			throw new ControlException('nome fantasia não definido');

		return $this->manufactureDAO->selectByFantasyName($fantasyName, $amount);
	}
}

