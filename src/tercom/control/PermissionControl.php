<?php

namespace tercom\control;

use tercom\entities\Permission;
use tercom\dao\PermissionDAO;
use tercom\entities\lists\Permissions;

/**
 * @see Permission
 * @see PermissionDAO
 * @author andrews
 */
class PermissionControl
{
	/**
	 * @var PermissionDAO
	 */
	private $permissionDAO;

	/**
	 *
	 */
	public function __construct()
	{
		$this->permissionDAO = new PermissionDAO();
	}

	/**
	 *
	 * @param Permission $permission
	 * @return bool
	 */
	public function add(Permission $permission): bool
	{
		if (!$this->avaiableAction($permission->getPacket(), $permission->getAction(), $permission->getId()))
			throw new ControlException(format('permissão já existe'));

		return $this->permissionDAO->insert($permission);
	}

	/**
	 *
	 * @param Permission $permission
	 * @return bool
	 */
	public function set(Permission $permission): bool
	{
		if (!$this->avaiableAction($permission->getPacket(), $permission->getAction(), $permission->getId()))
			throw new ControlException('não foi possível trocar a permissão');

		return $this->permissionDAO->update($permission);
	}

	/**
	 *
	 * @param Permission $permission
	 * @return bool
	 */
	public function remove(Permission $permission): bool
	{
		return $this->permissionDAO->delete($permission);
	}

	/**
	 *
	 * @param int $idPermission
	 * @throws ControlException
	 * @return bool
	 */
	public function get(int $idPermission): Permission
	{
		if (($permission = $this->permissionDAO->select($idPermission)) === null)
			throw new ControlException('permissão não encontrada');

		return $permission;
	}

	/**
	 *
	 * @param string $packet
	 * @param string $action
	 * @return Permission
	 */
	public function getAction(string $packet, string $action): Permission
	{
		if (($permission = $this->permissionDAO->selectByPacketAction($packet, $action)) === null)
			throw new ControlException('permissão não encontrada');

		return $permission;
	}

	/**
	 *
	 * @param string $packet
	 * @return Permissions
	 */
	public function getPacket(string $packet): Permissions
	{
		$permissions = $this->permissionDAO->selectByPacket($packet);

		return $permissions;
	}

	/**
	 *
	 * @param string $packet
	 * @param string $action
	 * @param int $idPermission
	 * @return bool
	 */
	public function avaiableAction(string $packet, string $action, int $idPermission): bool
	{
		return !$this->permissionDAO->existAction($packet, $action, $idPermission);
	}
}

