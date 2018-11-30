<?php

namespace tercom\control;

use tercom\dao\TercomPermissionDAO;
use tercom\entities\TercomProfile;
use tercom\entities\Permission;
use tercom\entities\lists\Permissions;

/**
 * @see GenericControl
 * @see RelationshipControl
 * @see TercomProfile
 * @see Permission
 * @author Andrew
 */
class TercomPermissionControl extends GenericControl implements RelationshipControl
{
	/**
	 * @var TercomPermissionDAO
	 */
	private $tercomPermissionDAO;
	/**
	 * @var PermissionControl
	 */
	private $permissionControl;

	/**
	 *
	 */
	public function __construct()
	{
		$this->tercomPermissionDAO = new TercomPermissionDAO();
		$this->permissionControl = new PermissionControl();
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @param Permission $permission
	 */
	private function validateRelanshionship(TercomProfile $tercomProfile, Permission $permission)
	{
		if (!$this->hasRelationship($tercomProfile, $permission))
			throw new ControlException('perfil da TERCOM não possui essa permissão');
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @param Permission $permission
	 * @return bool
	 */
	private function hasAssignmentLevel(TercomProfile $tercomProfile, Permission $permission): bool
	{
		return $permission->getAssignmentLevel() <= $tercomProfile->getAssignmentLevel();
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::addRelationship()
	 * @param TercomProfile $tercomProfile
	 * @param Permission $permission
	 */
	public function addRelationship($tercomProfile, $permission): bool
	{
		$this->tercomPermissionDAO->beginTransaction();
		{
			if ($permission->getId() === 0)
				$this->permissionControl->add($permission);

			if ($this->hasRelationship($tercomProfile, $permission))
				throw new ControlException('permissão já existente no perfil');

			if (!$this->hasAssignmentLevel($tercomProfile, $permission))
				throw new ControlException('o nível de assinatura da permissão é maior que o nível de assinatura do perfil');

			if (!$this->tercomPermissionDAO->insert($tercomProfile, $permission))
			{
				$this->tercomPermissionDAO->rollback();
				throw new ControlException('não foi possível adicionar a permissão ao perfil');
			}
		}
		$this->tercomPermissionDAO->commit();

		return true;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::setRelationship()
	 * @param TercomProfile $tercomProfile
	 * @param Permission $permission
	 */
	public function setRelationship($tercomProfile, $permission): bool
	{
		$this->validateRelanshionship($tercomProfile, $permission);

		if (!$this->hasAssignmentLevel($tercomProfile, $permission))
		{
			if (!$this->tercomPermissionDAO->delete($tercomProfile, $permission))
				throw new ControlException('não foi possível descvincular a permissão do perfil');

			return false;
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::removeRelationship()
	 * @param TercomProfile $tercomProfile
	 * @param Permission $permission
	 */
	public function removeRelationship($tercomProfile, $permission): bool
	{
		$this->validateRelanshionship($tercomProfile, $permission);

		return $this->tercomPermissionDAO->delete($tercomProfile, $permission);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::getRelationship()
	 * @param TercomProfile $tercomProfile
	 * @param int $idPermission
	 * @return Permission
	 */
	public function getRelationship($tercomProfile, $idPermission)
	{
		if (($permission = $this->tercomPermissionDAO->select($tercomProfile, $idPermission)) === null)
			throw new ControlException('permissão não encontrada');

		return $permission;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::getRelationships()
	 * @param TercomProfile $tercomProfile
	 * @return Permissions
	 */
	public function getRelationships($tercomProfile)
	{
		return $this->tercomPermissionDAO->selectByTercom($tercomProfile);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::hasRelationship()
	 * @param TercomProfile $tercomProfile
	 * @param Permission $permission
	 */
	public function hasRelationship($tercomProfile, $permission): bool
	{
		return $this->tercomPermissionDAO->exist($tercomProfile, $permission);
	}
}

