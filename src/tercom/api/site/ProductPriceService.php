<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use dProject\Primitive\ArrayDataException;
use dProject\restful\exception\ApiMissParam;
use tercom\api\site\results\ApiResultProductPriceSettings;
use tercom\entities\ProductPrice;
use tercom\control\ProductControl;
use tercom\core\System;
use dProject\restful\exception\ApiException;
use tercom\control\ProductPriceControl;
use tercom\api\site\results\ApiResultProductPrice;
use tercom\api\site\results\ApiResultProductPrices;

class ProductPriceService extends DefaultSiteService
{
	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionSettings(ApiContent $content): ApiResult
	{
		return new ApiResultProductPriceSettings();
	}

	/**
	 * @ApiAnnotation({"method":"post","params":["idProduct"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionAdd(ApiContent $content): ApiResult
	{
		try {

			$post = $content->getPost();
			$idProduct = $content->getParameters()->getInt('idProduct');
			$productControl = new ProductControl(System::getWebConnection());

			if (($product = $productControl->get($idProduct)) === null)
				throw new ApiException('produto não encontrado');

			$productPrice = new ProductPrice();
			$productPrice->setProduct($product);
			$productPrice->getProvider()->setID($post->getInt('idProvider'));
			$productPrice->getManufacture()->setID($post->getInt('idManufacture'));
			$productPrice->getPackage()->setID($post->getInt('idProductPackage'));
			$productPrice->getType()->setID($post->getInt('idProductType'));
			$productPrice->setName($product->getName());
			$productPrice->setAmount($post->getInt('amount'));
			$productPrice->setPrice($post->getFloat('price'));

			if ($post->isSetted('name') && !empty($post->getString('name'))) $productPrice->setName($post->getString('name'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productPriceControl = new ProductPriceControl(System::getWebConnection());

		if (!$productPriceControl->add($productPrice))
			throw new ApiException('não foi possível adicionar o valor do produto');

		$result = new ApiResultProductPrice();
		$result->setProductPrice($productPrice);
		$result->setMessage('valor do produto adicionado com êxito');

		return $result;
	}

	/**
	 * @ApiAnnotation({"method":"post","params":["idProductPrice"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionSet(ApiContent $content): ApiResult
	{
		try {

			$post = $content->getPost();
			$idProductPrice = $content->getParameters()->getInt('idProductPrice');
			$productPriceControl = new ProductPriceControl(System::getWebConnection());

			if (($productPrice = $productPriceControl->get($idProductPrice)) === null)
				throw new ApiException('valor do produto não encontrado');

			if ($post->isSetted('idProduct')) $productPrice->getProduct()->setID($post->getInt('idProduct'));
			if ($post->isSetted('idProvider')) $productPrice->getProvider()->setID($post->getInt('idProvider'));
			if ($post->isSetted('idManufacture')) $productPrice->getManufacture()->setID($post->getInt('idManufacture'));
			if ($post->isSetted('idProductPackage')) $productPrice->getPackage()->setID($post->getInt('idProductPackage'));
			if ($post->isSetted('idProductType')) $productPrice->getType()->setID($post->getInt('idProductType'));
			if ($post->isSetted('amount')) $productPrice->setAmount($post->getInt('amount'));
			if ($post->isSetted('price')) $productPrice->setPrice($post->getFloat('price'));
			if ($post->isSetted('name')) $productPrice->setName($post->getString('name'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$result = new ApiResultProductPrice();
		$result->setProductPrice($productPrice);

		if ($productPriceControl->set($productPrice))
			$result->setMessage('valor do produto atualizado com êxito');
		else
			$result->setMessage('nenhuma informação alterada no valor do produto');

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductPrice"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionRemove(ApiContent $content): ApiResult
	{
		$idProductPrice = $content->getParameters()->getInt('idProductPrice');
		$productPriceControl = new ProductPriceControl(System::getWebConnection());

		if (($productPrice = $productPriceControl->get($idProductPrice)) === null)
			throw new ApiException('valor do produto não encontrado');

		$result = new ApiResultProductPrice();
		$result->setProductPrice($productPrice);

		if ($productPriceControl->remove($productPrice))
			$result->setMessage('valor do produto excluído com êxito');
		else
			$result->setMessage('valor do produto já não existe mais');

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductPrice"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionGet(ApiContent $content): ApiResult
	{
		$idProductPrice = $content->getParameters()->getInt('idProductPrice');
		$productPriceControl = new ProductPriceControl(System::getWebConnection());

		if (($productPrice = $productPriceControl->get($idProductPrice)) === null)
			throw new ApiException('valor do produto não encontrado');

		$result = new ApiResultProductPrice();
		$result->setProductPrice($productPrice);
		$result->setMessage('valor do produto carregado com êxito');

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProduct"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionGetAll(ApiContent $content): ApiResult
	{
		$idProduct = $content->getParameters()->getInt('idProduct');
		$productPriceControl = new ProductPriceControl(System::getWebConnection());
		$productPrices = $productPriceControl->getByProduct($idProduct);

		$result = new ApiResultProductPrices();
		$result->setProductPrices($productPrices);

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
			case 'product':
			case 'provider':
				return $this->onSearchByProvider($content);

			case 'name':
				return $this->onSearchByName($content);
		}

		throw new ApiException('opção inexistente');
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function onSearchByProvider(ApiContent $content): ApiResult
	{
		try {

			$idProduct = $content->getParameters()->getInt('value');
			$idProvider = $this->parseNullToInt($content->getPost()->getInt('idProvider', false));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productPriceControl = new ProductPriceControl(System::getWebConnection());
		$productPrices = $productPriceControl->searchByProvider($idProduct, $idProvider);

		$result = new ApiResultProductPrices();
		$result->setProductPrices($productPrices);

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function onSearchByName(ApiContent $content): ApiResult
	{
		$name = $content->getParameters()->getString('value');
		$productPriceControl = new ProductPriceControl(System::getWebConnection());
		$productPrices = $productPriceControl->searchByName($name);

		$result = new ApiResultProductPrices();
		$result->setProductPrices($productPrices);

		return $result;
	}
}

