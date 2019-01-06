<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\exceptions\FilterException;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultServiceSettings;
use tercom\entities\Service;

/**
 * @see DefaultSiteService
 * @see Service
 * @see ServiceControl
 * @author Andrew
 */
class ServiceService extends DefaultSiteService
{
	/**
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content
	 * @return ApiResultServiceSettings
	 */
	public function actionSettings(ApiContent $content): ApiResultServiceSettings
	{
		return new ApiResultServiceSettings();
	}

	/**
	 * @ApiPermissionAnnotation({"method":"post"})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();

		$service = new Service();
		$service
		->setName($post->getString('name'))
		->setDescription($post->getString('description'))
		->setInactive(false);

		if ($post->isSetted('tags')) $service->getTags()->parseString($post->getString('tags'));
		if ($post->isSetted('inactive')) $service->setInactive($post->getString('inactive'));

		$this->getServiceControl()->add($service);

		$result = new ApiResultObject();
		$result->setResult($service, 'serviço %s adicionado com êxito', $service->getName());

		return $result;
	}

	/**
	 * @ApiPermissionAnnotation({"params":["idService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idService = $content->getParameters()->getInt('idService');
		$service = $this->getServiceControl()->get($idService);

		if ($post->isSetted('name')) $service->setName($post->getString('name'));
		if ($post->isSetted('description')) $service->setDescription($post->getString('description'));
		if ($post->isSetted('tags')) $service->getTags()->parseString($post->getString('tags'));
		if ($post->isSetted('inactive')) $service->setInactive($post->getString('inactive'));

		$this->getServiceControl()->set($service);

		$result = new ApiResultObject();
		$result->setResult($service, 'serviço %s atualizado com êxito', $service->getName());

		return $result;
	}

	/**
	 * @ApiPermissionAnnotation({"params":["idService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSetInactive(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idService = $content->getParameters()->getInt('idService');
		$service = $this->getServiceControl()->get($idService);
		$service->setInactive(($avaiable = $post->getBoolean('inactive')));
		$this->getServiceControl()->set($service);

		$result = new ApiResultObject();
		$result->setResult($service, 'serviço %s atualizado para %s', $service->getName(), $this->getMessageAvaiable($avaiable));

		return $result;
	}

	/**
	 * @ApiPermissionAnnotation({"params":["idService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idService = $content->getParameters()->getInt('idService');
		$service = $this->getServiceControl()->get($idService);

		$result = new ApiResultObject();
		$result->setResult($service, 'serviço "%s" obtido com êxito', $service->getName());

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$services = $this->getServiceControl()->getAll();

		$result = new ApiResultObject();
		$result->setResult($services, 'encontrado %d serviços registrados', $services->size());

		return $result;
	}

	/**
	 * @ApiPermissionAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content
	 * @throws FilterException
	 * @return ApiResultObject
	 */
	public function actionSearch(ApiContent $content): ApiResultObject
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'name': return $this->searchByName($content);
		}

		throw new FilterException();
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	private function searchByName(ApiContent $content): ApiResultObject
	{
		$name = $content->getParameters()->getString('value');
		$services = $this->getServiceControl()->filterByName($name);

		$result = new ApiResultObject();
		$result->setResult($services, 'encontrado %d serviços com "%s" no nome', $services->size(), $name);

		return $result;
	}

	/**
	 * @ApiPermissionAnnotation({"params":["field","value"]})
	 * @param ApiContent $content
	 * @throws FilterException
	 * @return ApiResultObject
	 */
	public function actionAvaiable(ApiContent $content): ApiResultObject
	{
		$field = $content->getParameters()->getString('field');

		switch ($field)
		{
			case 'name': return $this->avaiableName($content);
		}

		throw new FilterException();
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultSimpleValidation
	 */
	private function avaiableName(ApiContent $content): ApiResultSimpleValidation
	{
		$name = $content->getParameters()->getString('value');
		$avaiable = $this->getServiceControl()->avaiableName($name);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, $avaiable ? 'nome de serviço disponível' : 'nome de serviço indisponível');

		return $result;
	}
}

