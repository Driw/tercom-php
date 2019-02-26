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
		$service->setName($post->getString('name'));
		$service->setDescription($post->getString('description'));
		$service->setInactive(false);

		if ($post->isSetted('tags')) $service->getTags()->parseString($post->getString('tags'));
		if ($post->isSetted('inactive')) $service->setInactive($post->getString('inactive'));

		$this->getServiceControl()->add($service);

		if ($post->isSetted('idServiceCustomer'))
		{
			if ($this->getCustomerEmployeeNull() === null)
				$customer = $this->getCustomerControl()->get($post->getInt('idCustomer'));
			else
				$customer = null;

			$idServiceCustomer = $post->getString('idServiceCustomer');
			$service->setIdServiceCustomer($idServiceCustomer);
			$this->getServiceControl()->setCustomerId($service, $customer);
		}

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

		if ($post->isSetted('idServiceCustomer'))
		{
			if ($this->getCustomerEmployeeNull() === null)
				$customer = $this->getCustomerControl()->get($post->getInt('idCustomer'));
			else
				$customer = null;

			$idServiceCustomer = $post->getString('idServiceCustomer');
			$service->setIdServiceCustomer($idServiceCustomer);
			$this->getServiceControl()->setCustomerId($service, $customer);
		}

		$result = new ApiResultObject();
		$result->setResult($service, 'serviço %s atualizado com êxito', $service->getName());

		return $result;
	}

	/**
	 * @ApiPermissionAnnotation({"params":["idService","inactive"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSetInactive(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idService = $parameters->getInt('idService');
		$service = $this->getServiceControl()->get($idService);
		$service->setInactive($parameters->getBoolean('inactive'));
		$this->getServiceControl()->set($service);

		$result = new ApiResultObject();
		$result->setResult($service, 'serviço %s atualizado para %s', $service->getName(), $this->getMessageAvaiable(!$service->isInactive()));

		return $result;
	}

	/**
	 * Define um código de identificação personalizado exclusivo para um cliente.
	 * Considera o cliente em acesso, portanto somente clientes podem usar.
	 * @ApiPermissionAnnotation({"params":["idService","idServiceCustomer"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados definidos.
	 */
	public function actionSetCustomerId(ApiContent $content): ApiResultObject
	{
		$idService = $content->getParameters()->getInt('idService');
		$idServiceCustomer = $content->getParameters()->getString('idServiceCustomer');
		$service = $this->getServiceControl()->get($idService);
		$service->setIdServiceCustomer($idServiceCustomer);
		$this->getServiceControl()->setCustomerId($service);

		$result = new ApiResultObject();
		$result->setResult($service, 'código "%s" definido para o cliente no serviço "%s"', $idServiceCustomer, $service->getName());

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
	 * @ApiPermissionAnnotation({})
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
			case 'idServiceCustomer': return $this->searchByIdCustom($content);
			case 'name': return $this->searchByName($content);
		}

		throw new FilterException($filter);
	}

	/**
	 * Procedimento interno usado para especificar a procura por serviços através do cliente serviço ID.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados dos serviços filtrados.
	 */
	private function searchByIdCustom(ApiContent $content): ApiResultObject
	{
		$idServiceCustomer = $content->getParameters()->getString('value');
		$services = $this->getServiceControl()->searchByCustomId($idServiceCustomer);

		$result = new ApiResultObject();
		$result->setResult($services, 'encontrado %d serviços com ID "%s"', $services->size(), $idServiceCustomer);

		return $result;
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
	 * @ApiPermissionAnnotation({"params":["field","value","idService"]})
	 * @param ApiContent $content
	 * @throws FilterException
	 * @return ApiResultSimpleValidation
	 */
	public function actionAvaiable(ApiContent $content): ApiResultSimpleValidation
	{
		$field = $content->getParameters()->getString('field');

		switch ($field)
		{
			case 'name': return $this->avaiableName($content);
			case 'idServiceCustomer': return $this->avaiableIdServiceCustomer($content);
		}

		throw new FilterException($field);
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

	/**
	 * Procedimento interno usado para verificar a disponibilidade de um cliente serviço ID.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultSimpleValidation aquisição do resultado com a validação da disponibilidade do dado.
	 */
	private function avaiableIdServiceCustomer(ApiContent $content): ApiResultSimpleValidation
	{
		$parameters = $content->getParameters();
		$idServiceCustomer = $parameters->getString('value');
		$idService = $this->parseNullToInt($parameters->getInt('idService', false));
		$service = $this->getServiceControl()->get($idService);
		$service->setIdServiceCustomer($idServiceCustomer);
		$avaiable = !$this->getServiceControl()->hasIdServiceCustomer($service);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'cliente produto ID "%s" %s', $idServiceCustomer, $this->getMessageAvaiable($avaiable));

		return $result;
	}
}

