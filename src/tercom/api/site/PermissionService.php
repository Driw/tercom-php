<?php

namespace tercom\api\site;

use tercom\api\site\results\ApiResultPermissionSettings;
use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultPermission;
use tercom\entities\Permission;

/**
 * @see DefaultSiteService
 * @author andrews
 */
class PermissionService extends DefaultSiteService
{
	/**
	 *
	 * @return ApiResultPermissionSettings
	 */
	public function actionSettings(): ApiResultPermissionSettings
	{
		return new ApiResultPermissionSettings();
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content
	 * @return ApiResultPermission
	 */
	public function actionAdd(ApiContent $content): ApiResultPermission
	{
		$post = $content->getPost();

		$permission = new Permission();
		$permission->setPacket($post->getString('packet'));
		$permission->setAction($post->getString('action'));
		$permission->setAssignmentLevel($post->getString('assignmentLevel'));
		$this->getPermissionControl()->add($permission);

		$result = new ApiResultPermission();
		$result->setPermission($permission);
		$result->setMessage('permissão para %s.%s adicionada com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post","params":["idPermission"]})
	 * @param ApiContent $content
	 * @return ApiResultPermission
	 */
	public function actionSet(ApiContent $content): ApiResultPermission
	{
		$post = $content->getPost();
		$idPermission = $content->getParameters()->getInt('idPermission');
		$permission = $this->getPermissionControl()->get($idPermission);

		if ($post->isSetted('packet')) $permission->setPacket($post->getString('packet'));
		if ($post->isSetted('action')) $permission->setAction($post->getString('action'));
		if ($post->isSetted('assignmentLevel')) $permission->setAssignmentLevel($post->getString('assignmentLevel'));

		$this->getPermissionControl()->set($permission);

		$result = new ApiResultPermission();
		$result->setPermission($permission);
		$result->setMessage('permissão para %s.%s atualizada com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["idPermission"]})
	 * @param ApiContent $content
	 * @return ApiResultPermission
	 */
	public function actionRemove(ApiContent $content): ApiResultPermission
	{
		$idPermission = $content->getParameters()->getInt('idPermission');
		$permission = $this->getPermissionControl()->get($idPermission);
		$this->getPermissionControl()->remove($permission);

		$result = new ApiResultPermission();
		$result->setPermission($permission);
		$result->setMessage('permissão para %s.%s excluída com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["idPermission"]})
	 * @param ApiContent $content
	 * @return ApiResultPermission
	 */
	public function actionGet(ApiContent $content): ApiResultPermission
	{
		$idPermission = $content->getParameters()->getInt('idPermission');
		$permission = $this->getPermissionControl()->get($idPermission);

		$result = new ApiResultPermission();
		$result->setPermission($permission);
		$result->setMessage('permissão para %s.%s obtida com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}
}

