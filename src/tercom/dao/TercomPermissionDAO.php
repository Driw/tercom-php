<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\dao\exceptions\DAOException;
use tercom\entities\Permission;
use tercom\entities\TercomProfile;
use tercom\entities\lists\Permissions;

/**
 * @see Tercom
 * @see TercomProfile
 * @see GenericDAO
 * @author Andrew
 */
class TercomPermissionDAO extends GenericDAO
{
	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @throws DAOException
	 */
	private function validateTercomProfile(TercomProfile $tercomProfile)
	{
		if ($tercomProfile->getId() === 0)
			throw new DAOException('perfil da TERCOM não identificado');
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @param Permission $permission
	 * @throws DAOException
	 */
	private function validate(TercomProfile $tercomProfile, Permission $permission)
	{
		$this->validateTercomProfile($tercomProfile);

		if ($permission->getId() === 0)
			throw new DAOException('permissão não identificada');
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @param Permission $permission
	 */
	public function insert(TercomProfile $tercomProfile, Permission $permission): bool
	{
		$this->validate($tercomProfile, $permission);

		$sql = "INSERT INTO tercom_profile_permissions (idTercomProfile, idPermission)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());
		$query->setInteger(2, $permission->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @param Permission $permission
	 */
	public function delete(TercomProfile $tercomProfile, Permission $permission): bool
	{
		$this->validate($tercomProfile, $permission);

		$sql = "DELETE FROM tercom_profile_permissions
				WHERE idTercomProfile = ? AND idPermission = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());
		$query->setInteger(2, $permission->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @return string
	 */
	private function newSelectPermission(): string
	{
		$permissionColumns = $this->buildQuery(PermissionDAO::ALL_COLUMNS, 'permissions');

		return "SELECT $permissionColumns
				FROM permissions
				INNER JOIN tercom_profile_permissions ON tercom_profile_permissions.idPermission = permissions.id";
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @param int $idPermission
	 * @return Permission
	 */
	public function select(TercomProfile $tercomProfile, int $idPermission): ?Permission
	{
		$sqlPermission = $this->newSelectPermission();
		$sql = "$sqlPermission
				WHERE tercom_profile_permissions.idTercomProfile = ? AND tercom_profile_permissions.idPermission = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());
		$query->setInteger(2, $idPermission);

		$result = $query->execute();

		return $this->parsePermission($result);
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @return Permissions
	 */
	public function selectByTercom(TercomProfile $tercomProfile): Permissions
	{
		$sqlPermission = $this->newSelectPermission();
		$sql = "$sqlPermission
				WHERE tercom_profile_permissions.idTercomProfile = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());

		$result = $query->execute();

		return $this->parsePermissions($result);
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @param Permission $permission
	 * @return bool
	 */
	public function exist(TercomProfile $tercomProfile, Permission $permission): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_profile_permissions
				WHERE idTercomProfile = ? AND idPermission = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());
		$query->setInteger(2, $permission->getId());

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) > 0;
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

