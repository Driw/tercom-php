<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\api\site\results\ApiResultServiceSettings;
use tercom\api\site\results\ApiResultService;
use tercom\entities\Service;
use tercom\api\exceptions\ServiceException;
use tercom\api\site\results\ApiResultServices;
use tercom\api\site\results\ApiResultSimpleValidation;

/**
 * @see DefaultSiteService
 * @see Service
 * @see ServiceControl
 * @author Andrew
 */
class ServiceService extends DefaultSiteService
{
	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultServiceSettings
	 */
	public function actionSettings(ApiContent $content): ApiResultServiceSettings
	{
		return new ApiResultServiceSettings();
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResult
	 */
	public function actionAdd(ApiContent $content): ApiResult
	{
		$post = $content->getPost();

		$service = new Service();
		$service
		->setName($post->getString('name'))
		->setDescription($post->getString('description'))
		->setInactive(false);

		if ($post->isSetted('tags')) $service->getTags()->parseString($post->getString('tags'));
		if ($post->isSetted('inactive')) $service->setInactive($post->getString('inactive'));

		if (!$this->getServiceControl()->add($service))
			throw ServiceException::newAdd();

		$result = new ApiResultService();
		$result->setMessage('serviço %s adicionado com êxito', $service->getName());
		$result->setService($service);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idService"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */
	public function actionSet(ApiContent $content): ApiResult
	{
		$post = $content->getPost();
		$idService = $content->getParameters()->getInt('idService');

		if (($service = $this->getServiceControl()->get($idService)) === null)
			throw ServiceException::newNotFound();

		if ($post->isSetted('name')) $service->setName($post->getString('name'));
		if ($post->isSetted('description')) $service->setDescription($post->getString('description'));
		if ($post->isSetted('tags')) $service->getTags()->parseString($post->getString('tags'));
		if ($post->isSetted('inactive')) $service->setInactive($post->getString('inactive'));

		if (!$this->getServiceControl()->set($service))
			throw ServiceException::newNotSet();

		$result = new ApiResultService();
		$result->setMessage('serviço %s atualizado com êxito', $service->getName());
		$result->setService($service);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idService"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */
	public function actionSetInactive(ApiContent $content): ApiResult
	{
		$post = $content->getPost();
		$idService = $content->getParameters()->getInt('idService');

		if (($service = $this->getServiceControl()->get($idService)) === null)
			throw ServiceException::newNotFound();

		$service->setInactive($post->getString('inactive'));

		if (!$this->getServiceControl()->set($service))
			throw ServiceException::newNotSet();

		$result = new ApiResultService();
		$result->setMessage('serviço %s atualizado para %s', $service->getName(), $service->isInactive() ? 'ativo' : 'inativo');
		$result->setService($service);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idService"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */
	public function actionGet(ApiContent $content): ApiResult
	{
		$idService = $content->getParameters()->getInt('idService');

		if (($service = $this->getServiceControl()->get($idService)) === null)
			throw ServiceException::newNotFound();

		$result = new ApiResultService();
		$result->setMessage('serviço "%s" obtido com êxito', $service->getName());
		$result->setService($service);

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */
	public function actionGetAll(ApiContent $content): ApiResult
	{
		$services = $this->getServiceControl()->getAll();

		$result = new ApiResultServices();
		$result->setMessage('encontrado %d serviços registrados', $services->size());
		$result->setServices($services);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */
	public function actionSearch(ApiContent $content): ApiResult
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'name': return $this->searchByName($content);
		}

		throw ServiceException::newFilterNotFound();
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultServices
	 */
	private function searchByName(ApiContent $content): ApiResultServices
	{
		$name = $content->getParameters()->getString('value');
		$services = $this->getServiceControl()->filterByName($name);

		$result = new ApiResultServices();
		$result->setMessage('encontrado %d serviços com "%s" no nome', $services->size(), $name);
		$result->setServices($services);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["field","value"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */
	public function actionAvaiable(ApiContent $content): ApiResult
	{
		$field = $content->getParameters()->getString('field');

		switch ($field)
		{
			case 'name': return $this->avaiableName($content);
		}

		throw ServiceException::newFieldNotFound();
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultSimpleValidation
	 */
	private function avaiableName(ApiContent $content): ApiResultSimpleValidation
	{
		$name = $content->getParameters()->getString('value');
		$result = new ApiResultSimpleValidation();

		if ($this->getServiceControl()->avaiableName($name))
			$result->setOkMessage(true, 'nome de serviço disponível');
		else
			$result->setOkMessage(false, 'nome de serviço indisponível');

		return $result;
	}
}

