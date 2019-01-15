<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;
use tercom\entities\QuotedOrderService;

/**
 * @author Andrew
 */
class QuotedServicePriceService extends DefaultSiteService
{
	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest","idOrderItemService","idServicePrice"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemService = $parameters->getInt('idOrderItemService');
		$idServicePrice = $parameters->getInt('idServicePrice');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$this->getQuotedOrderServiceControl()->validateManagement($orderRequest, $this->getTercomEmployee());

		$orderItemService = $this->getOrderItemServiceControl()->get($idOrderItemService, $orderRequest->getId());
		$servicePrice = $this->getServicePriceControl()->get($idServicePrice);

		$quotedOrderService = new QuotedOrderService();
		$quotedOrderService->setObservations($post->getString('observations', false));
		$quotedOrderService->setOrderItemService($orderItemService);

		$quotedServicePrice = $this->getQuotedServicePriceControl()->quote($servicePrice);
		$quotedOrderService->setQuotedServicePrice($quotedServicePrice);
		$this->getQuotedOrderServiceControl()->add($quotedOrderService, $this->getTercomEmployee());

		$result = new ApiResultObject();
		$result->setResult($quotedOrderService, 'preço do serviço "%s" cotado à R$ %.2f', $servicePrice->getName(), $servicePrice->getPrice());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderRequest","idQuotedOrderService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idQuotedOrderService = $parameters->getInt('idQuotedOrderService');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$this->getQuotedOrderServiceControl()->validateManagement($orderRequest, $this->getTercomEmployee());

		$quotedOrderService = $this->getQuotedOrderServiceControl()->get($idQuotedOrderService, $orderRequest);
		$quotedServicePrice = $quotedOrderService->getQuotedServicePrice();
		$this->getQuotedOrderServiceControl()->remove($quotedOrderService, $this->getTercomEmployee());

		$result = new ApiResultObject();
		$result->setResult($quotedOrderService, 'cotação de R$ %.2f para "%s" excluído', $quotedServicePrice->getPrice(), $quotedServicePrice->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderRequest","idOrderItemService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemoveAll(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemService = $parameters->getInt('idOrderItemService');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$this->getQuotedOrderServiceControl()->validateManagement($orderRequest, $this->getTercomEmployee());

		$orderItemService = $this->getOrderItemServiceControl()->get($idOrderItemService, $orderRequest->getId());
		$this->getQuotedOrderServiceControl()->removeAll($orderRequest, $orderItemService, $this->getTercomEmployee());

		$result = new ApiResultObject();
		$result->setResult($orderItemService, 'cotações do serviço "%s" excluídas com êxito', $orderItemService->getService()->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest","idQuotedOrderService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idQuotedOrderService = $parameters->getInt('idQuotedOrderService');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$quotedOrderService = $this->getQuotedOrderServiceControl()->get($idQuotedOrderService, $orderRequest);
		$service = $quotedOrderService->getQuotedServicePrice()->getService();

		$result = new ApiResultObject();
		$result->setResult($quotedOrderService, 'cotação do serviço "%s" obitda com êxito', $service->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest","idOrderItemService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemService = $parameters->getInt('idOrderItemService');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$orderItemService = $this->getOrderItemServiceControl()->get($idOrderItemService, $orderRequest->getId());
		$quotedOrderServices = $this->getQuotedOrderServiceControl()->getAll($orderItemService);
		$service = $orderItemService->getService();

		$result = new ApiResultObject();
		$result->setResult($quotedOrderServices, 'encontrado %d cotações para o serviço "%s"', $quotedOrderServices->size(), $service->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderRequest","idOrderItemService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionPrices(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemService = $parameters->getInt('idOrderItemService');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$orderItemService = $this->getOrderItemServiceControl()->get($idOrderItemService, $orderRequest->getId());
		$servicePrices = $this->getServicePriceControl()->searchByItem($orderItemService);
		$service = $orderItemService->getService();

		$result = new ApiResultObject();
		$result->setResult($servicePrices, 'encontrado %d preços de serviços para o item de serviço "%s"', $servicePrices->size(), $service->getName());

		return $result;
	}
}

