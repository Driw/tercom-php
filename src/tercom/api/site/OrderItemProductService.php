<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiOrderItemProductSettings;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\api\exceptions\FilterException;
use tercom\entities\OrderItemProduct;

/**
 *
 *
 * @see DefaultSiteProduct
 * @see ApiContent
 * @see ApiResultObject
 * @see ApiResultSimpleValidation
 *
 * @author Andrew
 */
class OrderItemProductService extends DefaultSiteService
{
	/**
	 *
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content
	 * @return ApiOrderItemProductSettings
	 */
	public function actionSettings(ApiContent $content): ApiOrderItemProductSettings
	{
		return new ApiOrderItemProductSettings();
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProduct = $post->getInt('idProduct');
		$idOrderRequest = $content->getParameters()->getInt('idOrderRequest');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$product = $this->getProductControl()->get($idProduct);

		$orderItemProduct = new OrderItemProduct();
		$orderItemProduct->setProduct($product);
		$orderItemProduct->setBetterPrice($post->getBoolean('betterPrice'));
		$orderItemProduct->setObservations($post->getString('observations', false));

		if (($idProvider = $post->getInt('idProvider', false)) !== null)
		{
			$provider = $this->getProviderControl()->get($idProvider);
			$orderItemProduct->setProvider($provider);
		}

		if (($idManufacturer = $post->getInt('idManufacturer', false)) !== null)
		{
			$manufacturer = $this->getManufacturerControl()->get($idManufacturer);
			$orderItemProduct->setManufacturer($manufacturer);
		}

		$this->getOrderItemProductControl()->add($orderRequest, $orderItemProduct);

		$result = new ApiResultObject();
		$result->setResult($orderItemProduct, 'produto "%s" adicionado ao pedido de cotação', $product->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest","idOrderItemProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemProduct = $parameters->getInt('idOrderItemProduct');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$orderItemProduct = $this->getOrderItemProductControl()->get($idOrderItemProduct, $idOrderRequest);

		$orderItemProduct->setBetterPrice($post->getBoolean('betterPrice'));
		$orderItemProduct->setObservations($post->getString('observations', false));

		if (($idProvider = $post->getInt('idProvider', false)) !== null)
		{
			$provider = $this->getProviderControl()->get($idProvider);
			$orderItemProduct->setProvider($provider);
		}

		if (($idManufacturer = $post->getInt('idManufacturer', false)) !== null)
		{
			$manufacturer = $this->getManufacturerControl()->get($idManufacturer);
			$orderItemProduct->setManufacturer($manufacturer);
		}

		$this->getOrderItemProductControl()->set($orderRequest, $orderItemProduct);

		$result = new ApiResultObject();
		$result->setResult($orderItemProduct, 'item de produto "%s" atualizado no pedido', $orderItemProduct->getProduct()->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest","idOrderItemProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemProduct = $parameters->getInt('idOrderItemProduct');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$orderItemProduct = $this->getOrderItemProductControl()->get($idOrderItemProduct, $orderRequest->getId());
		$this->getOrderItemProductControl()->remove($orderRequest, $orderItemProduct);

		$result = new ApiResultObject();
		$result->setResult($orderItemProduct, 'item de produto "%s" excluído no pedido', $orderItemProduct->getProduct()->getName());

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
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$this->getOrderItemProductControl()->removeAll($orderRequest);

		$result = new ApiResultObject();
		$result->setResult($orderRequest, 'excluído todos os itens de produto do pedido nº %d', $orderRequest->getId());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest","idOrderItemProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemProduct = $parameters->getInt('idOrderItemProduct');
		$orderItemProduct = $this->getOrderItemProductControl()->get($idOrderItemProduct, $idOrderRequest);

		$result = new ApiResultObject();
		$result->setResult($orderItemProduct, 'item de produto "%s" obtido', $orderItemProduct->getProduct()->getName());

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
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest, true);
		$orderItemProducts = $this->getOrderItemProductControl()->getAll($orderRequest);

		$result = new ApiResultObject();
		$result->setResult($orderItemProducts, 'encontrado %d itens de produto no pedido de nº %d', $orderItemProducts->size(), $idOrderRequest);

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
			case 'product': return $this->avaiableProduct($content);
		}

		throw new FilterException($filter);
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultSimpleValidation
	 */
	private function avaiableProduct(ApiContent $content): ApiResultSimpleValidation
	{
		$idProduct = $content->getParameters()->getInt('value');
		$idOrderRequest = $content->getParameters()->getInt('idOrderRequest');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest, true);
		$product = $this->getProductControl()->get($idProduct);
		$avaiable = $this->getOrderItemProductControl()->avaiableProduct($orderRequest, $product);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'produto "%s" %s', $product->getName(), $this->getMessageAvaiable($avaiable));

		return $result;
	}
}

