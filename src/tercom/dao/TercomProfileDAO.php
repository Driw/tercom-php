<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\Functions;
use tercom\dao\exceptions\DAOException;
use tercom\entities\TercomProfile;
use tercom\entities\lists\TercomProfiles;

/**
 * @see TercomProfile
 * @author Andrew
 */
class TercomProfileDAO extends GenericDAO
{
	/**
	 * @var array
	 */
	public const ALL_COLUMNS = ['id', 'name', 'assignmentLevel'];

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @param bool $validateID
	 * @throws DAOException
	 */
	private function validate(TercomProfile $tercomProfile, bool $validateID)
	{
		if ($validateID) {
			if ($tercomProfile->getId() === 0)
				throw new DAOException('perfil não identificado');
		} else {
			if ($tercomProfile->getId() !== 0)
				throw new DAOException('perfil já identificado');
		}

		if (StringUtil::isEmpty($tercomProfile->getName())) throw new DAOException('nome não definido');
		if ($tercomProfile->getAssignmentLevel() === 0) throw new DAOException('nível de assinatura não definido');
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @return bool
	 */
	public function insert(TercomProfile $tercomProfile): bool
	{
		$this->validate($tercomProfile, false);

		$sql = "INSERT INTO tercom_profiles (name, assignmentLevel)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $tercomProfile->getName());
		$query->setInteger(2, $tercomProfile->getAssignmentLevel());

		if (($result = $query->execute())->isSuccessful())
			$tercomProfile->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @return bool
	 */
	public function update(TercomProfile $tercomProfile): bool
	{
		$this->validate($tercomProfile, true);

		$sql = "UPDATE tercom_profiles
				SET name = ?, assignmentLevel = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $tercomProfile->getName());
		$query->setInteger(2, $tercomProfile->getAssignmentLevel());
		$query->setInteger(3, $tercomProfile->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @return bool
	 */
	public function delete(TercomProfile $tercomProfile): bool
	{
		$this->validate($tercomProfile, true);

		$sql = "DELETE FROM tercom_profiles
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @return string
	 */
	private function newBaseSelect(): string
	{
		return "SELECT id, name, assignmentLevel
				FROM tercom_profiles";
	}

	/**
	 *
	 * @param int $idTercomProfile
	 * @return TercomProfile|NULL
	 */
	public function select(int $idTercomProfile): ?TercomProfile
	{
		$sqlBase = $this->newBaseSelect();
		$sql = "$sqlBase
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idTercomProfile);

		$result = $query->execute();

		return $this->parseTercomProfile($result);
	}

	/**
	 *
	 * @param int $assignmentLevel
	 * @return TercomProfiles|NULL
	 */
	public function selectByAssignmentLevel(int $assignmentLevel): TercomProfiles
	{
		$sqlBase = $this->newBaseSelect();
		$sql = "$sqlBase
				WHERE assignmentLevel <= ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $assignmentLevel);

		$result = $query->execute();

		return $this->parseTercomProfiles($result);
	}

	/**
	 *
	 * @return TercomProfiles|NULL
	 */
	public function selectAll(): TercomProfiles
	{
		$sql = $this->newBaseSelect();

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseTercomProfiles($result);
	}

	/**
	 *
	 * @param int $idTercomProfile
	 * @return bool
	 */
	public function exist(int $idTercomProfile): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_profiles
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idTercomProfile);

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) === 1;
	}

	/**
	 *
	 * @param string $name
	 * @param int $idTercomProfile
	 * @return bool
	 */
	public function existName(string $name, int $idTercomProfile): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_profiles
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idTercomProfile);

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) === 1;
	}

	/**
	 *
	 * @param Result $result
	 * @return TercomProfile|NULL
	 */
	private function parseTercomProfile(Result $result): ?TercomProfile
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newTercomProfile($entry);
	}

	/**
	 *
	 * @param Result $result
	 * @return TercomProfiles
	 */
	private function parseTercomProfiles(Result $result): TercomProfiles
	{
		$tercomProfiles = new TercomProfiles();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$tercomProfile = $this->newTercomProfile($entry);
			$tercomProfiles->add($tercomProfile);
		}

		return $tercomProfiles;
	}

	/**
	 *
	 * @param array $entry
	 * @return TercomProfile
	 */
	private function newTercomProfile(array $entry): TercomProfile
	{
		Functions::parseArrayJoin($entry);

		$tercomProfile = new TercomProfile();
		$tercomProfile->fromArray($entry);

		return $tercomProfile;
	}
}

