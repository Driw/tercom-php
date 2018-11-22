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
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile);
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
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile);
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
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile);
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
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile);
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
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile);
		$permissions = $this->getCustomerPermissionControl()->getRelationships($customerProfile);

		$result = new ApiResultObject();
		$result->setObject($permissions);
		$result->setMessage('encontrado %d permissões para o perfil de cliente', $permissions->size());

		return $result;
	}
}

