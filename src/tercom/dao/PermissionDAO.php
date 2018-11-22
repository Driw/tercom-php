<?php

namespace tercom\dao;

use tercom\entities\Permission;
use tercom\entities\lists\Permissions;
use dProject\MySQL\Result;
use tercom\dao\exceptions\DAOException;
use dProject\Primitive\StringUtil;

/**
 * @see GenericDAO
 * @see Permissiond
 * @author andrews
 */
class PermissionDAO extends GenericDAO
{
	/**
	 * @var string[]
	 */
	public const ALL_COLUMNS = ['id', 'packet', 'action', 'assignmentLevel'];

	/**
	 *
	 * @param Permission $permission
	 * @param bool $validateID
	 * @throws DAOException
	 */
	private function validatePermission(Permission $permission, bool $validateID)
	{
		if ($validateID) {
			if ($permission->getId() === 0)
				throw new DAOException('permissão não identificada');
		} else {
			if ($permission->getId() !== 0)
				throw new DAOException('permissão já identificada');
		}

		if (StringUtil::isEmpty($permission->getPacket())) throw new DAOException('pacote não definido');
		if (StringUtil::isEmpty($permission->getAction())) throw new DAOException('ação não definida');
		if ($permission->getAssignmentLevel() < 0) throw new DAOException('nível de assinatura inválido');
	}

	/**
	 *
	 * @param Permission $permission
	 * @return bool
	 */
	public function insert(Permission $permission): bool
	{
		$this->validatePermission($permission, false);

		$sql = "INSERT INTO permissions (packet, action, assignmentLevel)
				VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $permission->getPacket());
		$query->setString(2, $permission->getAction());
		$query->setInteger(3, $permission->getAssignmentLevel());

		if (($result = $query->execute())->isSuccessful())
			$permission->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 *
	 * @param Permission $permission
	 * @return bool
	 */
	public function update(Permission $permission): bool
	{
		$this->validatePermission($permission, true);

		$sql = "UPDATE permissions
				SET packet = ?, action = ?, assignmentLevel = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $permission->getPacket());
		$query->setString(2, $permission->getAction());
		$query->setInteger(3, $permission->getAssignmentLevel());
		$query->setInteger(4, $permission->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param Permission $permission
	 * @return bool
	 */
	public function delete(Permission $permission): bool
	{
		$this->validatePermission($permission, true);

		$sql = "DELETE FROM permissions
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $permission->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param int $idPermission
	 * @return Permission|NULL
	 */
	public function select(int $idPermission): ?Permission
	{
		$sql = "SELECT id, packet, action, assignmentLevel
				FROM permissions
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idPermission);

		$result = $query->execute();
		$permission = $this->parsePermission($result);

		return $permission;
	}

	/**
	 *
	 * @param string $packet
	 * @param string $action
	 * @return Permission|NULL
	 */
	public function selectByPacketAction(string $packet, string $action): ?Permission
	{
		$sql = "SELECT id, packet, action, assignmentLevel
				FROM permissions
				WHERE packet = ? AND action = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $packet);
		$query->setString(2, $action);

		$result = $query->execute();
		$permission = $this->parsePermission($result);

		return $permission;
	}

	/**
	 *
	 * @param string $packet
	 * @return Permissions
	 */
	public function selectByPacket(string $packet): Permissions
	{
		$sql = "SELECT id, packet, action, assignmentLevel
				FROM permissions
				WHERE packet = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $packet);

		$result = $query->execute();
		$permissions = $this->parsePermissions($result);

		return $permissions;
	}

	/**
	 *
	 * @param string $packet
	 * @param string $action
	 * @param int $idPermission
	 * @return bool
	 */
	public function existAction(string $packet, string $action, int $idPermission): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM permissions
				WHERE packet = ? AND action = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $packet);
		$query->setString(2, $action);
		$query->setInteger(3, $idPermission);

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) === 1;
	}

	/**
	 *
	 * @param Result $result
	 * @return Permission|NULL
	 */
	private function parsePermission(Result $result): ?Permission
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newPermission($entry);
	}

	/**
	 *
	 * @param Result $result
	 * @return Permissions
	 */
	private function parsePermissions(Result $result): Permissions
	{
		$permissions = new Permissions();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$permission = $this->newPermission($entry);
			$permissions->add($permission);
		}

		return $permissions;
	}

	/**
	 *
	 * @param array $entry
	 * @return Permission
	 */
	private function newPermission(array $entry): Permission
	{
		$permission = new Permission();
		$permission->fromArray($entry);

		return $permission;
	}
}

