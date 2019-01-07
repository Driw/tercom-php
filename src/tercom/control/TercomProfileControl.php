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
	 * @param int $assignmentLevel
	 * @throws ControlException
	 */
	private function validateLoginAndAssignment(TercomProfile $tercomProfile, int $assignmentLevel)
	{
		if ($tercomProfile->getAssignmentLevel() > $assignmentLevel)
			throw new ControlException('nível de assinatura acima do permitido');

		if ($tercomProfile->getId() === (new LoginTercomControl)->getCurrent()->getTercomEmployee()->getTercomProfileId())
			throw new ControlException('não é permitido alterar o próprio perfil');
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @param int $assignmentLevel
	 * @throws ControlException
	 */
	public function add(TercomProfile $tercomProfile, int $assignmentLevel): void
	{
		$this->validateLoginAndAssignment($tercomProfile, $assignmentLevel);

		if (!$this->tercomProfileDAO->insert($tercomProfile))
			throw new ControlException('não foi possível adicionar o perfil');
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @param int $assignmentLevel
	 * @throws ControlException
	 */
	public function set(TercomProfile $tercomProfile, int $assignmentLevel): void
	{
		$this->validateLoginAndAssignment($tercomProfile, $assignmentLevel);

		if (!$this->tercomProfileDAO->update($tercomProfile))
			throw new ControlException('não foi possível atualizar o perfil');
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @return bool
	 */
	public function remove(TercomProfile $tercomProfile, int $assignmentLevel): void
	{
		$this->validateLoginAndAssignment($tercomProfile, $assignmentLevel);

		if (!$this->tercomProfileDAO->delete($tercomProfile))
			throw new ControlException('não foi possível exlcuir o perfil');
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

