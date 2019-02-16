<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\entities\Permission;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultPermissionSettings;

/**
 * @author Andrew
 */
class ManagePermissionsService extends RelationshipService
{
	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\RelationshipService::getRelationshipName()
	 */
	public function getRelationshipName(): string
	{
		return nameOf(Permission::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\RelationshipService::actionSettings()
	 */
	public function actionSettings(ApiContent $content): ApiResult
	{
		return new ApiResultPermissionSettings();
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomerProfile
	 */
	protected function addCustomerPermission(ApiContent $content, int $idCustomerProfile): ApiResultObject
	{
		$idPermission = $content->getPost()->getInt('idPermission');
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile, false, $this->getCurrentAssignmentLevel());
		$permission = $this->getPermissionControl()->get($idPermission);
		$this->getCustomerPermissionControl()->addRelationship($customerProfile, $permission);

		$result = new ApiResultObject();
		$result->setObject($permission);
		$result->setMessage('permissão para "%s.%s" adicionada com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomerProfile
	 * @param int $idPermission
	 */
	protected function setCustomerPermission(ApiContent $content, int $idCustomerProfile, int $idPermission): ApiResultObject
	{
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile, false, $this->getCurrentAssignmentLevel());
		$permission = $this->getPermissionControl()->get($idPermission);

		$result = new ApiResultObject();
		$result->setObject($permission);

		if ($this->getCustomerPermissionControl()->setRelationship($customerProfile, $permission))
			$result->setMessage('permissão para "%s.%s" verificada com êxito', $permission->getPacket(), $permission->getAction());
		else
			$result->setMessage('permissão "%s.%s" desvinculada por nível de assinatura insuficiente', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomerProfile
	 * @param int $idPermission
	 */
	protected function removeCustomerPermission(ApiContent $content, int $idCustomerProfile, int $idPermission): ApiResultObject
	{
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile, false, $this->getCurrentAssignmentLevel());
		$permission = $this->getPermissionControl()->get($idPermission);
		$this->getCustomerPermissionControl()->removeRelationship($customerProfile, $permission);

		$result = new ApiResultObject();
		$result->setObject($permission);
		$result->setMessage('permissão para "%s.%s" excluída com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomerProfile
	 * @param int $idPermission
	 */
	protected function getCustomerPermission(ApiContent $content, int $idCustomerProfile, int $idPermission): ApiResultObject
	{
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile, false, $this->getCurrentAssignmentLevel());
		$permission = $this->getCustomerPermissionControl()->getRelationship($customerProfile, $idPermission);

		$result = new ApiResultObject();
		$result->setObject($permission);
		$result->setMessage('permissão para "%s.%s" obtida com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomerProfile
	 * @param int $idPermission
	 */
	protected function getAllCustomerPermission(ApiContent $content, int $idCustomerProfile): ApiResultObject
	{
		$this->setHeaderCache();
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile, false, $this->getCurrentAssignmentLevel());
		$permissions = $this->getCustomerPermissionControl()->getRelationships($customerProfile);

		$result = new ApiResultObject();
		$result->setObject($permissions);
		$result->setMessage('encontrado %d permissões no perfil', $permissions->size());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idTercomProfile
	 */
	protected function addTercomPermission(ApiContent $content, int $idTercomProfile): ApiResultObject
	{
		$idPermission = $content->getPost()->getInt('idPermission');
		$tercomProfile = $this->getTercomProfileControl()->get($idTercomProfile);
		$permission = $this->getPermissionControl()->get($idPermission);
		$this->getTercomPermissionControl()->addRelationship($tercomProfile, $permission, $this->getCurrentAssignmentLevel());

		$result = new ApiResultObject();
		$result->setObject($permission);
		$result->setMessage('permissão para "%s.%s" adicionada com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idTercomProfile
	 * @param int $idPermission
	 */
	protected function setTercomPermission(ApiContent $content, int $idTercomProfile, int $idPermission): ApiResultObject
	{
		$customerProfile = $this->getTercomProfileControl()->get($idTercomProfile);
		$permission = $this->getPermissionControl()->get($idPermission);

		$result = new ApiResultObject();
		$result->setObject($permission);

		if ($this->getTercomPermissionControl()->setRelationship($customerProfile, $permission))
			$result->setMessage('permissão para "%s.%s" verificada com êxito', $permission->getPacket(), $permission->getAction());
		else
			$result->setMessage('permissão "%s.%s" desvinculada por nível de assinatura insuficiente', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idTercomProfile
	 * @param int $idPermission
	 */
	protected function removeTercomPermission(ApiContent $content, int $idTercomProfile, int $idPermission): ApiResultObject
	{
		$tercomProfile = $this->getTercomProfileControl()->get($idTercomProfile);
		$permission = $this->getPermissionControl()->get($idPermission);
		$this->getTercomPermissionControl()->removeRelationship($tercomProfile, $permission);

		$result = new ApiResultObject();
		$result->setObject($permission);
		$result->setMessage('permissão para "%s.%s" excluída com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idTercomProfile
	 * @param int $idPermission
	 */
	protected function getTercomPermission(ApiContent $content, int $idTercomProfile, int $idPermission): ApiResultObject
	{
		$tercomProfile = $this->getTercomProfileControl()->get($idTercomProfile);
		$permission = $this->getTercomPermissionControl()->getRelationship($tercomProfile, $idPermission);

		$result = new ApiResultObject();
		$result->setObject($permission);
		$result->setMessage('permissão para "%s.%s" obtida com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idTercomProfile
	 * @param int $idPermission
	 */
	protected function getAllTercomPermission(ApiContent $content, int $idTercomProfile): ApiResultObject
	{
		$this->setHeaderCache();
		$tercomProfile = $this->getTercomProfileControl()->get($idTercomProfile);
		$permissions = $this->getTercomPermissionControl()->getRelationships($tercomProfile);

		$result = new ApiResultObject();
		$result->setObject($permissions);
		$result->setMessage('encontrado %d permissões no perfil', $permissions->size());

		return $result;
	}

	private function setHeaderCache(): void
	{
		$seconds = 3600;
		$ts = gmdate("D, d M Y H:i:s", time() + $seconds) . " GMT";

		header("Expires: $ts");
		header("Pragma: cache");
		header("Cache-Control: max-age=$seconds");
	}
}

