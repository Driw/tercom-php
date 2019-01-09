<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiOrderItemServiceSettings;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\api\exceptions\FilterException;
use tercom\entities\OrderItemService;

/**
 *
 *
 * @see DefaultSiteService
 * @see ApiContent
 * @see ApiResultObject
 * @see ApiResultSimpleValidation
 *
 * @author Andrew
 */
class OrderItemServiceService extends DefaultSiteService
{
	/**
	 *
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content
	 * @return ApiOrderItemServiceSettings
	 */
	public function actionSettings(ApiContent $content): ApiOrderItemServiceSettings
	{
		return new ApiOrderItemServiceSettings();
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$customerEmployee = $this->getCustomerEmployee();

		$post = $content->getPost();
		$idService = $post->getInt('idService');
		$idOrderRequest = $content->getParameters()->getInt('idOrderRequest');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest, $customerEmployee);
		$service = $this->getServiceControl()->get($idService);

		$orderItemService = new OrderItemService();
		$orderItemService->setService($service);
		$orderItemService->setBetterPrice($post->getBoolean('betterPrice'));
		$orderItemService->setObservations($post->getString('observations', false));

		if (($idProvider = $post->getInt('idProvider', false)) !== null)
		{
			$provider = $this->getProviderControl()->get($idProvider);
			$orderItemService->setProvider($provider);
		}

		if (($idManufacturer = $post->getInt('idManufacturer', false)) !== null)
		{
			$manufacturer = $this->getManufacturerControl()->get($idManufacturer);
			$orderItemService->setManufacturer($manufacturer);
		}

		$this->getOrderItemServiceControl()->add($orderRequest, $orderItemService);

		$result = new ApiResultObject();
		$result->setResult($orderItemService, 'serviço "%s" adicionado ao pedido de cotação', $service->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest","idOrderItemService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemService = $parameters->getInt('idOrderItemService');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest, $this->getCustomerEmployee());
		$orderItemService = $this->getOrderItemServiceControl()->get($idOrderItemService, $idOrderRequest);

		$orderItemService->setBetterPrice($post->getBoolean('betterPrice'));
		$orderItemService->setObservations($post->getString('observations', false));

		if (($idProvider = $post->getInt('idProvider', false)) !== null)
		{
			$provider = $this->getProviderControl()->get($idProvider);
			$orderItemService->setProvider($provider);
		}

		if (($idManufacturer = $post->getInt('idManufacturer', false)) !== null)
		{
			$manufacturer = $this->getManufacturerControl()->get($idManufacturer);
			$orderItemService->setManufacturer($manufacturer);
		}

		$this->getOrderItemServiceControl()->set($orderRequest, $orderItemService);

		$result = new ApiResultObject();
		$result->setResult($orderItemService, 'item de serviço "%s" atualizado no pedido', $orderItemService->getService()->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest","idOrderItemService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemService = $parameters->getInt('idOrderItemService');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest, $this->getCustomerEmployeeNull());
		$orderItemService = $this->getOrderItemServiceControl()->get($idOrderItemService, $orderRequest->getId());
		$this->getOrderItemServiceControl()->remove($orderRequest, $orderItemService);

		$result = new ApiResultObject();
		$result->setResult($orderItemService, 'item de serviço "%s" excluído no pedido', $orderItemService->getService()->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemoveAll(ApiContent $content): ApiResultObject
	{
		$idOrderRequest = $content->getParameters()->getInt('idOrderRequest');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest, $this->getCustomerEmployeeNull());
		$this->getOrderItemServiceControl()->removeAll($orderRequest);

		$result = new ApiResultObject();
		$result->setResult($orderRequest, 'excluído todos os itens de serviço do pedido nº %d', $orderRequest->getId());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest","idOrderItemService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemService = $parameters->getInt('idOrderItemService');
		$orderItemService = $this->getOrderItemServiceControl()->get($idOrderItemService, $idOrderRequest);

		$result = new ApiResultObject();
		$result->setResult($orderItemService, 'item de serviço "%s" obtido', $orderItemService->getService()->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$idOrderRequest = $content->getParameters()->getInt('idOrderRequest');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest, $this->getCustomerEmployeeNull());
		$orderItemServices = $this->getOrderItemServiceControl()->getAll($orderRequest);

		$result = new ApiResultObject();
		$result->setResult($orderItemServices, 'encontrado %d itens de serviço no pedido de nº %d', $orderItemServices->size(), $idOrderRequest);

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["filter","value","idOrderRequest"]})
	 * @param ApiContent $content
	 * @throws FilterException
	 * @return ApiResultSimpleValidation
	 */
	public function actionAvaiable(ApiContent $content): ApiResultSimpleValidation
	{
		switch (($filter = $content->getParameters()->getString('filter')))
		{
			case 'service': return $this->avaiableService($content);
		}

		throw new FilterException($filter);
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultSimpleValidation
	 */
	private function avaiableService(ApiContent $content): ApiResultSimpleValidation
	{
		$idService = $content->getParameters()->getInt('value');
		$idOrderRequest = $content->getParameters()->getInt('idOrderRequest');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest, $this->getLoginCustomerControl()->getCurrent());
		$service = $this->getServiceControl()->get($idService);
		$avaiable = $this->getOrderItemServiceControl()->avaiableService($orderRequest, $service);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'serviço "%s" %s', $service->getName(), $this->getMessageAvaiable($avaiable));

		return $result;
	}
}

