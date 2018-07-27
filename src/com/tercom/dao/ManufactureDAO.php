<?php

namespace tercom\dao;

use tercom\entities\Manufacture;
use tercom\entities\lists\Manufactures;
use dProject\MySQL\Result;

class ManufactureDAO extends GenericDAO
{
	public function insert(Manufacture $manufacture):bool
	{
		$sql = "INSERT INTO manufacturers (fantasyName)
				VALUES (?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $manufacture->getFantasyName());

		$result = $query->execute();

		if ($result->isSuccessful())
			$manufacture->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(Manufacture $manufacture):bool
	{
		$sql = "UPDATE manufacturers
				SET fantasyName = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $manufacture->getFantasyName());
		$query->setInteger(2, $manufacture->getID());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function dalete(Manufacture $manufacture):bool
	{
		$sql = "DELETE FROM manufacturers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $manufacture->getID());

		$result = $query->execute();

		return $result->getAffectedRows();
	}

	public function select(int $idManufacture):?Manufacture
	{
		$sql = "SELECT id, fantasyName
				FROM manufacturers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idManufacture);

		$result = $query->execute();

		return $this->parseManufacture($result);
	}

	public function selectByFantasyName(string $fantasyName, int $amount):?Manufacture
	{
		$sql = "SELECT id, fantasyName
				FROM manufacturers
				WHERE fantasyName LIKE ?
				LIMIT 0, ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$fantasyName%");
		$query->setInteger(2, $amount);

		$result = $query->execute();

		return $this->parseManufactures($result);
	}

	private function parseManufacture(Result $result):?Manufacture
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$manufacture = $this->newManufacture($array);

		return $manufacture;
	}

	private function parseManufactures(Result $result):Manufactures
	{
		$manufactures = new Manufactures();

		while ($result->hasNext())
		{
			$array = $result->next();
			$manufacture = $this->newManufacture($array);
			$manufactures->add($manufacture);
		}

		return $manufactures;
	}

	private function newManufacture(array $array):Manufacture
	{
		$manufacture = new Manufacture();
		$manufacture->fromArray($array);

		return $manufacture;
	}
}

