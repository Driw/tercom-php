<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\entities\Permission;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultPermissionSettings;
use tercom\TercomException;

/**
 * @see DefaultSiteService
 * @author andrews
 */
class PermissionService extends DefaultSiteService
{
	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::defaultExecute()
	 */
	protected function defaultExecute()
	{
		if (!DEV)
			throw TercomException::newPermissionRestrict();

		parent::defaultExecute();
	}

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
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();

		$permission = new Permission();
		$permission->setPacket($post->getString('packet'));
		$permission->setAction($post->getString('action'));
		$permission->setAssignmentLevel($post->getString('assignmentLevel'));
		$this->getPermissionControl()->add($permission);

		$result = new ApiResultObject();
		$result->setObject($permission);
		$result->setMessage('permissão para %s.%s adicionada com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post","params":["idPermission"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idPermission = $content->getParameters()->getInt('idPermission');
		$permission = $this->getPermissionControl()->get($idPermission);

		if ($post->isSetted('packet')) $permission->setPacket($post->getString('packet'));
		if ($post->isSetted('action')) $permission->setAction($post->getString('action'));
		if ($post->isSetted('assignmentLevel')) $permission->setAssignmentLevel($post->getString('assignmentLevel'));

		$this->getPermissionControl()->set($permission);

		$result = new ApiResultObject();
		$result->setObject($permission);
		$result->setMessage('permissão para %s.%s atualizada com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["idPermission"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$idPermission = $content->getParameters()->getInt('idPermission');
		$permission = $this->getPermissionControl()->get($idPermission);
		$this->getPermissionControl()->remove($permission);

		$result = new ApiResultObject();
		$result->setObject($permission);
		$result->setMessage('permissão para %s.%s excluída com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["idPermission"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idPermission = $content->getParameters()->getInt('idPermission');
		$permission = $this->getPermissionControl()->get($idPermission);

		$result = new ApiResultObject();
		$result->setObject($permission);
		$result->setMessage('permissão para %s.%s obtida com êxito', $permission->getPacket(), $permission->getAction());

		return $result;
	}
}

