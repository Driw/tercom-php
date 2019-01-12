<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;
use tercom\entities\QuotedOrderProduct;

/**
 * @author Andrew
 */
class QuotedProductPriceService extends DefaultSiteService
{
	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest","idOrderItemProduct","idProductPrice"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemProduct = $parameters->getInt('idOrderItemProduct');
		$idProductPrice = $parameters->getInt('idProductPrice');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$this->getQuotedOrderProductControl()->validateManagement($orderRequest, $this->getTercomEmployee());

		$orderItemProduct = $this->getOrderItemProductControl()->get($idOrderItemProduct, $orderRequest->getId());
		$productPrice = $this->getProductPriceControl()->get($idProductPrice);

		$quotedOrderProduct = new QuotedOrderProduct();
		$quotedOrderProduct->setObservations($post->getString('observations', false));
		$quotedOrderProduct->setOrderItemProduct($orderItemProduct);

		$quotedProductPrice = $this->getQuotedProductPriceControl()->quote($productPrice);
		$quotedOrderProduct->setQuotedProductPrice($quotedProductPrice);
		$this->getQuotedOrderProductControl()->add($quotedOrderProduct, $this->getTercomEmployee());

		$result = new ApiResultObject();
		$result->setResult($quotedOrderProduct, 'preço do produto "%s" cotado à %.2f', $productPrice->getName(), $productPrice->getAmount());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderRequest","idQuotedOrderProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idQuotedOrderProduct = $parameters->getInt('idQuotedOrderProduct');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$this->getQuotedOrderProductControl()->validateManagement($orderRequest, $this->getTercomEmployee());

		$quotedOrderProduct = $this->getQuotedOrderProductControl()->get($idQuotedOrderProduct, $orderRequest);
		$quotedProductPrice = $quotedOrderProduct->getQuotedProductPrice();
		$this->getQuotedOrderProductControl()->remove($quotedOrderProduct, $this->getTercomEmployee());

		$result = new ApiResultObject();
		$result->setResult($quotedOrderProduct, 'cotação de R$ %.2f para "%s" excluído', $quotedOrderProduct->getQuotedProductPrice()->getPrice(), $quotedProductPrice->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderRequest","idOrderItemProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemoveAll(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemProduct = $parameters->getInt('idOrderItemProduct');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$this->getQuotedOrderProductControl()->validateManagement($orderRequest, $this->getTercomEmployee());

		$orderItemProduct = $this->getOrderItemProductControl()->get($idOrderItemProduct, $orderRequest->getId());
		$this->getQuotedOrderProductControl()->removeAll($orderRequest, $orderItemProduct, $this->getTercomEmployee());

		$result = new ApiResultObject();
		$result->setResult($orderItemProduct, 'cotações do produto "%s" excluídas com êxito', $orderItemProduct->getProduct()->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest","idQuotedOrderProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idQuotedOrderProduct = $parameters->getInt('idQuotedOrderProduct');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$quotedOrderProduct = $this->getQuotedOrderProductControl()->get($idQuotedOrderProduct, $orderRequest);
		$product = $quotedOrderProduct->getQuotedProductPrice()->getProduct();

		$result = new ApiResultObject();
		$result->setResult($quotedOrderProduct, 'cotação do produto "%s" obitda com êxito', $product->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest","idOrderItemProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemProduct = $parameters->getInt('idOrderItemProduct');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$orderItemProduct = $this->getOrderItemProductControl()->get($idOrderItemProduct, $orderRequest->getId());
		$quotedOrderProducts = $this->getQuotedOrderProductControl()->getAll($orderItemProduct);
		$product = $orderItemProduct->getProduct();

		$result = new ApiResultObject();
		$result->setResult($quotedOrderProducts, 'encontrado %d cotações para o produto "%s"', $quotedOrderProducts->size(), $product->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderRequest","idOrderItemProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionPrices(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idOrderRequest = $parameters->getInt('idOrderRequest');
		$idOrderItemProduct = $parameters->getInt('idOrderItemProduct');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$orderItemProduct = $this->getOrderItemProductControl()->get($idOrderItemProduct, $orderRequest->getId());
		$productPrices = $this->getProductPriceControl()->searchByItem($orderItemProduct);
		$product = $orderItemProduct->getProduct();

		$result = new ApiResultObject();
		$result->setResult($productPrices, 'encontrado %d preços de produtos para o item de produto "%s"', $productPrices->size(), $product->getName());

		return $result;
	}
}

