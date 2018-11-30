<?php

namespace tercom\control;

use tercom\dao\TercomProfileDAO;
use tercom\entities\TercomProfile;
use tercom\entities\lists\TercomProfiles;

/**
 * @see TercomProfileDAO
 * @see TercomProfile
 * @see TercomProfiles
 * @author Andrew
 */
class TercomProfileControl extends GenericControl
{
	/**
	 * @var TercomProfileDAO
	 */
	private $tercomProfileDAO;

	/**
	 *
	 */
	public function __construct()
	{
		$this->tercomProfileDAO = new TercomProfileDAO();
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @return bool
	 */
	public function add(TercomProfile $tercomProfile): bool
	{
		if (!$this->avaiableName($tercomProfile->getName(), $tercomProfile->getId()))
			throw new ControlException('nome de perfil já registrado');

		return $this->tercomProfileDAO->insert($tercomProfile);
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @return bool
	 */
	public function set(TercomProfile $tercomProfile): bool
	{
		if (!$this->avaiableName($tercomProfile->getName(), $tercomProfile->getId()))
			throw new ControlException('nome de perfil já registrado');

		return $this->tercomProfileDAO->update($tercomProfile);
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @return bool
	 */
	public function remove(TercomProfile $tercomProfile): bool
	{
		return $this->tercomProfileDAO->delete($tercomProfile);
	}

	/**
	 *
	 * @param int $idTercomProfile
	 * @return TercomProfile
	 */
	public function get(int $idTercomProfile): TercomProfile
	{
		if (($tercomProfile = $this->tercomProfileDAO->select($idTercomProfile)) === null)
			throw new ControlException('perfil da TERCOM não encontrado');

		return $tercomProfile;
	}

	/**
	 *
	 * @param int $assignmentLevel
	 * @return TercomProfiles
	 */
	public function getByAssignmentLevel(int $assignmentLevel): TercomProfiles
	{
		return $this->tercomProfileDAO->selectByAssignmentLevel($assignmentLevel);
	}

	/**
	 *
	 * @return TercomProfiles
	 */
	public function getAll(): TercomProfiles
	{
		return $this->tercomProfileDAO->selectAll();
	}

	/**
	 *
	 * @param int $idTercomProfile
	 * @return bool
	 */
	public function has(int $idTercomProfile): bool
	{
		return $this->tercomProfileDAO->exist($idTercomProfile);
	}

	/**
	 *
	 * @param string $name
	 * @param int $idTercomProfile
	 * @return bool
	 */
	public function avaiableName(string $name, int $idTercomProfile = 0): bool
	{
		return !$this->tercomProfileDAO->existName($name, $idTercomProfile);
	}
}

