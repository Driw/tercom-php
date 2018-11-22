<?php

namespace tercom\dao;

use tercom\entities\CustomerProfile;
use tercom\entities\Permission;
use tercom\entities\lists\Permissions;
use tercom\dao\exceptions\DAOException;
use dProject\MySQL\Result;

/**
 * @see Customer
 * @see CustomerProfile
 * @see GenericDAO
 * @author Andrew
 */
class CustomerPermissionDAO extends GenericDAO
{
	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @throws DAOException
	 */
	private function validateCustomerProfile(CustomerProfile $customerProfile)
	{
		if ($customerProfile->getId() === 0)
			throw new DAOException('perfil de cliente não identificado');
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @param Permission $permission
	 * @throws DAOException
	 */
	private function validate(CustomerProfile $customerProfile, Permission $permission)
	{
		$this->validateCustomerProfile($customerProfile);

		if ($permission->getId() === 0)
			throw new DAOException('permissão não identificada');
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @param Permission $permission
	 */
	public function insert(CustomerProfile $customerProfile, Permission $permission): bool
	{
		$this->validate($customerProfile, $permission);

		$sql = "INSERT INTO customer_profile_permissions (idCustomerProfile, idPermission)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());
		$query->setInteger(2, $permission->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @param Permission $permission
	 */
	public function delete(CustomerProfile $customerProfile, Permission $permission): bool
	{
		$this->validate($customerProfile, $permission);

		$sql = "DELETE FROM customer_profile_permissions
				WHERE idCustomerProfile = ? AND idPermission = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());
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
				INNER JOIN customer_profile_permissions ON customer_profile_permissions.idPermission = permissions.id";
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @param int $idPermission
	 * @return Permission
	 */
	public function select(CustomerProfile $customerProfile, int $idPermission): Permission
	{
		$sqlPermission = $this->newSelectPermission();
		$sql = "$sqlPermission
				WHERE customer_profile_permissions.idCustomerProfile = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());

		$result = $query->execute();

		return $this->parsePermission($result);
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @return Permissions
	 */
	public function selectByCustomer(CustomerProfile $customerProfile): Permissions
	{
		$sqlPermission = $this->newSelectPermission();
		$sql = "$sqlPermission
				WHERE customer_profile_permissions.idCustomerProfile = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());

		$result = $query->execute();

		return $this->parsePermissions($result);
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @param Permission $permission
	 * @return bool
	 */
	public function exist(CustomerProfile $customerProfile, Permission $permission): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_profile_permissions
				WHERE idCustomerProfile = ? AND idPermission = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());
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

