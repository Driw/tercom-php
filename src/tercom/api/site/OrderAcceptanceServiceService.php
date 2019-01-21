<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;
use tercom\entities\OrderAcceptanceService;

/**
 *
 *
 * @see DefaultSiteService
 * @see ApiResultObject
 *
 * @author Andrew
 */
class OrderAcceptanceServiceService extends DefaultSiteService
{
	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance","idQuotedServicePrice"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$parameters = $content->getParameters();
		$idOrderAcceptance = $parameters->getInt('idOrderAcceptance');
		$idQuotedServicePrice = $parameters->getInt('idQuotedServicePrice');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance);
		$quotedServicePrice = $this->getQuotedServicePriceControl()->get($idQuotedServicePrice);
		$service = $quotedServicePrice->getService();

		$orderAcceptanceService = new OrderAcceptanceService();
		$orderAcceptanceService->setQuotedServicePrice($quotedServicePrice);
		$orderAcceptanceService->setAmountRequest($post->getInt('amountRequest'));

		if ($post->isSetted('observations')) $orderAcceptanceService->setObservations($post->getString('observations'));
		if ($post->isSetted('subprice')) $orderAcceptanceService->setSubprice($post->getFloat('subprice'));

		$this->getOrderAcceptanceServiceControl()->add($orderAcceptance, $orderAcceptanceService);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptanceService, 'cotação de serviço aceito para "%s" adicionada com êxito', $service->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance","idOrderAcceptanceService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$parameters = $content->getParameters();
		$idOrderAcceptance = $parameters->getInt('idOrderAcceptance');
		$idOrderAcceptanceService = $parameters->getInt('idOrderAcceptanceService');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance);
		$orderAcceptanceService = $this->getOrderAcceptanceServiceControl()->get($orderAcceptance, $idOrderAcceptanceService);
		$service = $orderAcceptanceService->getService();

		if ($post->isSetted('amountRequest')) $orderAcceptanceService->setAmountRequest($post->getInt('amountRequest'));
		if ($post->isSetted('observations')) $orderAcceptanceService->setObservations($post->getString('observations'));
		if ($post->isSetted('subprice')) $orderAcceptanceService->setSubprice($post->getFloat('subprice'));

		$this->getOrderAcceptanceServiceControl()->set($orderAcceptance, $orderAcceptanceService);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptanceService, 'cotação de serviço aceito para "%s" atualizado com êxito', $service->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance","idOrderAcceptanceService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderAcceptance = $parameters->getInt('idOrderAcceptance');
		$idOrderAcceptanceService = $parameters->getInt('idOrderAcceptanceService');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance);
		$orderAcceptanceService = $this->getOrderAcceptanceServiceControl()->get($orderAcceptance, $idOrderAcceptanceService);
		$service = $orderAcceptanceService->getService();
		$this->getOrderAcceptanceServiceControl()->remove($orderAcceptance, $orderAcceptanceService);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptanceService, 'preço de serviço aceito de "%s" excluído com êxito', $service->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemoveAll(ApiContent $content): ApiResultObject
	{
		$idOrderAcceptance = $content->getParameters()->getInt('idOrderAcceptance');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance);
		$this->getOrderAcceptanceServiceControl()->removeAll($orderAcceptance);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptance, 'excluído todos os os preços de serviço aceitos do aceite de pedido número %d', $orderAcceptance->getId());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance","idOrderAcceptanceService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderAcceptance = $parameters->getInt('idOrderAcceptance');
		$idOrderAcceptanceService = $parameters->getInt('idOrderAcceptanceService');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance);
		$orderAcceptanceService = $this->getOrderAcceptanceServiceControl()->get($orderAcceptance, $idOrderAcceptanceService);
		$service = $orderAcceptanceService->getService();

		$result = new ApiResultObject();
		$result->setResult($orderAcceptanceService, 'preço de serviço aceito "%s" obitdo com êxito', $service->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$idOrderAcceptance = $content->getParameters()->getInt('idOrderAcceptance');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance);
		$orderAcceptanceServices = $this->getOrderAcceptanceServiceControl()->getAll($orderAcceptance);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptanceServices, 'encontrado %d preços de serviços aceitos no pedido número %d', $orderAcceptanceServices->size(), $orderAcceptance->getId());

		return $result;
	}
}

