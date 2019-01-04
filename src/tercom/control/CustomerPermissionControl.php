<?php

namespace tercom\control;

use tercom\TercomException;
use tercom\dao\CustomerPermissionDAO;
use tercom\entities\CustomerProfile;
use tercom\entities\Permission;
use tercom\entities\lists\Permissions;

/**
 * @see GenericControl
 * @see RelationshipControl
 * @see CustomerProfile
 * @see Permission
 * @author Andrew
 */
class CustomerPermissionControl extends GenericControl implements RelationshipControl
{
	/**
	 * @var CustomerPermissionDAO
	 */
	private $customerPermissionDAO;
	/**
	 * @var PermissionControl
	 */
	private $permissionControl;

	/**
	 *
	 */
	public function __construct()
	{
		$this->customerPermissionDAO = new CustomerPermissionDAO();
		$this->permissionControl = new PermissionControl();
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @param Permission $permission
	 */
	private function validateRelanshionship(CustomerProfile $customerProfile, Permission $permission)
	{
		if (!$this->hasRelationship($customerProfile, $permission))
			throw new ControlException('perfil de cliente não possui essa permissão');
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @param Permission $permission
	 * @return bool
	 */
	private function hasAssignmentLevel(CustomerProfile $customerProfile, Permission $permission): bool
	{
		return $permission->getAssignmentLevel() <= $customerProfile->getAssignmentLevel();
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::addRelationship()
	 * @param CustomerProfile $customerProfile
	 * @param Permission $permission
	 */
	public function addRelationship($customerProfile, $permission): bool
	{
		$this->customerPermissionDAO->beginTransaction();
		{
			if ($permission->getId() === 0)
				$this->permissionControl->add($permission);

			if ($this->hasRelationship($customerProfile, $permission))
				throw new ControlException('permissão já existente no perfil');

			if (!$this->hasAssignmentLevel($customerProfile, $permission))
				throw new ControlException('o nível de assinatura da permissão é maior que o nível de assinatura do perfil');

			if (!$this->customerPermissionDAO->insert($customerProfile, $permission))
			{
				$this->customerPermissionDAO->rollback();
				throw new ControlException('não foi possível adicionar a permissão ao perfil');
			}
		}
		$this->customerPermissionDAO->commit();

		return true;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::setRelationship()
	 * @param CustomerProfile $customerProfile
	 * @param Permission $permission
	 */
	public function setRelationship($customerProfile, $permission): bool
	{
		$this->validateRelanshionship($customerProfile, $permission);

		if (!$this->hasAssignmentLevel($customerProfile, $permission))
		{
			if (!$this->customerPermissionDAO->delete($customerProfile, $permission))
				throw new ControlException('não foi possível descvincular a permissão do perfil');

			return false;
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::removeRelationship()
	 * @param CustomerProfile $customerProfile
	 * @param Permission $permission
	 */
	public function removeRelationship($customerProfile, $permission): bool
	{
		$this->validateRelanshionship($customerProfile, $permission);

		return $this->customerPermissionDAO->delete($customerProfile, $permission);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::getRelationship()
	 * @param CustomerProfile $customerProfile
	 * @param int $idPermission
	 * @return Permission
	 */
	public function getRelationship($customerProfile, $idPermission)
	{
		return $this->customerPermissionDAO->select($customerProfile, $idPermission);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::getRelationships()
	 * @param CustomerProfile $customerProfile
	 * @return Permissions
	 */
	public function getRelationships($customerProfile)
	{
		return $this->customerPermissionDAO->selectByCustomer($customerProfile);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::hasRelationship()
	 * @param CustomerProfile $customerProfile
	 * @param Permission $permission
	 */
	public function hasRelationship($customerProfile, $permission): bool
	{
		return $this->customerPermissionDAO->exist($customerProfile, $permission);
	}

	/**
	 * Verifica se um determinado perfil possui uma determinada permissão e verifica o nível de acesso.
	 * @param CustomerProfile $customerProfile objeto do tipo perfil de cliente à verificar.
	 * @param Permission $permission objeto do tipo permissão à verificar.
	 */
	public function verifyCustomerPermission(CustomerProfile $customerProfile, Permission $permission): void
	{
		if (!$this->hasRelationship($customerProfile, $permission) ||
			!$this->hasAssignmentLevel($customerProfile, $permission))
			throw TercomException::newPermissionCustomerEmployee(); // FIXME trocar por CustomerPermissionException
	}
}

