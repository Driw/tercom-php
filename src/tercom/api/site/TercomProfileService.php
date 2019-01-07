<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\entities\TercomProfile;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultTercomProfileSettings;

/**
 * @see DefaultSiteService
 * @author Andrew
 */
class TercomProfileService extends DefaultSiteService
{
	/**
	 *
	 * @ApiPermissionAnnotation({})
	 * @return ApiResultTercomProfileSettings
	 */
	public function actionSettings(): ApiResultTercomProfileSettings
	{
		return new ApiResultTercomProfileSettings();
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post"})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();

		$tercomProfile = new TercomProfile();
		$tercomProfile->setName($post->getString('name'));
		$tercomProfile->setAssignmentLevel($post->getInt('assignmentLevel'));
		$this->getTercomProfileControl()->add($tercomProfile, $this->getCurrentAssignmentLevel());

		$result = new ApiResultObject();
		$result->setObject($tercomProfile);
		$result->setMessage('perfil da TERCOM "%s" adicionado com êxito', $tercomProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idTercomProfile"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idTercomProfile = $content->getParameters()->getInt('idTercomProfile');
		$tercomProfile = $this->getTercomProfileControl()->get($idTercomProfile, true);

		if ($post->isSetted('name')) $tercomProfile->setName($post->getString('name'));
		if ($post->isSetted('assignmentLevel')) $tercomProfile->setAssignmentLevel($post->getInt('assignmentLevel'));

		$this->getTercomProfileControl()->set($tercomProfile, $this->getCurrentAssignmentLevel());

		$result = new ApiResultObject();
		$result->setObject($tercomProfile);
		$result->setMessage('perfil da TERCOM "%s" atualizado com êxito', $tercomProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idTercomProfile"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$idTercomProfile = $content->getParameters()->getInt('idTercomProfile');
		$tercomProfile = $this->getTercomProfileControl()->get($idTercomProfile, true);

		$this->getTercomProfileControl()->remove($tercomProfile, $this->getCurrentAssignmentLevel());

		$result = new ApiResultObject();
		$result->setObject($tercomProfile);
		$result->setMessage('perfil da TERCOM "%s" excluído com êxito', $tercomProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idTercomProfile"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idTercomProfile = $content->getParameters()->getInt('idTercomProfile');
		$tercomProfile = $this->getTercomProfileControl()->get($idTercomProfile);

		$result = new ApiResultObject();
		$result->setObject($tercomProfile);
		$result->setMessage('perfil da TERCOM "%s" obtido com êxito', $tercomProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$tercomProfiles = $this->getTercomProfileControl()->getAll();

		$result = new ApiResultObject();
		$result->setObject($tercomProfiles);
		$result->setMessage('encontrados %d perfis da TERCOM no banco de dados', $tercomProfiles->size());

		return $result;
	}
}

