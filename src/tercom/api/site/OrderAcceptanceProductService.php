<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;
use tercom\entities\OrderAcceptanceProduct;

/**
 *
 *
 * @see DefaultSiteService
 * @see ApiResultObject
 *
 * @author Andrew
 */
class OrderAcceptanceProductService extends DefaultSiteService
{
	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance","idQuotedProductPrice"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$parameters = $content->getParameters();
		$idOrderAcceptance = $parameters->getInt('idOrderAcceptance');
		$idQuotedProductPrice = $parameters->getInt('idQuotedProductPrice');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance);
		$quotedProductPrice = $this->getQuotedProductPriceControl()->get($idQuotedProductPrice);
		$product = $quotedProductPrice->getProduct();

		$orderAcceptanceProduct = new OrderAcceptanceProduct();
		$orderAcceptanceProduct->setQuotedProductPrice($quotedProductPrice);
		$orderAcceptanceProduct->setAmountRequest($post->getInt('amountRequest'));

		if ($post->isSetted('observations')) $orderAcceptanceProduct->setObservations($post->getString('observations'));
		if ($post->isSetted('subprice')) $orderAcceptanceProduct->setSubprice($post->getFloat('subprice'));

		$this->getOrderAcceptanceProductControl()->add($orderAcceptance, $orderAcceptanceProduct);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptanceProduct, 'cotação de produto aceito para "%s" adicionada com êxito', $product->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance","idOrderAcceptanceProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$parameters = $content->getParameters();
		$idOrderAcceptance = $parameters->getInt('idOrderAcceptance');
		$idOrderAcceptanceProduct = $parameters->getInt('idOrderAcceptanceProduct');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance);
		$orderAcceptanceProduct = $this->getOrderAcceptanceProductControl()->get($orderAcceptance, $idOrderAcceptanceProduct);
		$product = $orderAcceptanceProduct->getProduct();

		if ($post->isSetted('amountRequest')) $orderAcceptanceProduct->setAmountRequest($post->getInt('amountRequest'));
		if ($post->isSetted('observations')) $orderAcceptanceProduct->setObservations($post->getString('observations'));
		if ($post->isSetted('subprice')) $orderAcceptanceProduct->setSubprice($post->getFloat('subprice'));

		$this->getOrderAcceptanceProductControl()->set($orderAcceptance, $orderAcceptanceProduct);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptanceProduct, 'cotação de produto aceito para "%s" atualizado com êxito', $product->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance","idOrderAcceptanceProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderAcceptance = $parameters->getInt('idOrderAcceptance');
		$idOrderAcceptanceProduct = $parameters->getInt('idOrderAcceptanceProduct');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance);
		$orderAcceptanceProduct = $this->getOrderAcceptanceProductControl()->get($orderAcceptance, $idOrderAcceptanceProduct);
		$product = $orderAcceptanceProduct->getProduct();
		$this->getOrderAcceptanceProductControl()->remove($orderAcceptance, $orderAcceptanceProduct);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptanceProduct, 'preço de produto aceito de "%s" excluído com êxito', $product->getName());

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
		$this->getOrderAcceptanceProductControl()->removeAll($orderAcceptance);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptance, 'excluído todos os os preços de produto aceitos do aceite de pedido número %d', $orderAcceptance->getId());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance","idOrderAcceptanceProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderAcceptance = $parameters->getInt('idOrderAcceptance');
		$idOrderAcceptanceProduct = $parameters->getInt('idOrderAcceptanceProduct');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance);
		$orderAcceptanceProduct = $this->getOrderAcceptanceProductControl()->get($orderAcceptance, $idOrderAcceptanceProduct);
		$product = $orderAcceptanceProduct->getProduct();

		$result = new ApiResultObject();
		$result->setResult($orderAcceptanceProduct, 'preço de produto aceito "%s" obitdo com êxito', $product->getName());

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
		$orderAcceptanceProducts = $this->getOrderAcceptanceProductControl()->getAll($orderAcceptance);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptanceProducts, 'encontrado %d preços de produtos aceitos no pedido número %d', $orderAcceptanceProducts->size(), $orderAcceptance->getId());

		return $result;
	}
}

