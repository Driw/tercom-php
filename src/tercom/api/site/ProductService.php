<?php

namespace tercom\api\site;

use dProject\Primitive\ArrayDataException;
use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use dProject\restful\exception\ApiException;
use dProject\restful\exception\ApiMissParam;
use tercom\api\site\results\ApiResultProduct;
use tercom\api\site\results\ApiResultProducts;
use tercom\api\site\results\ApiResultProductSettings;
use tercom\control\ProductControl;
use tercom\core\System;
use tercom\entities\Product;

/**
 * @see DefaultSiteService
 * @see ApiResultProduct
 * @see ApiResultProducts
 * @see ApiResultProductSettings
 * @author Andrew
 */

class ProductService extends DefaultSiteService
{
	/**
	 * @param ApiContent $content
	 * @return ApiResultProductSettings
	 */

	public function actionSettings(ApiContent $content): ApiResultProductSettings
	{
		return new ApiResultProductSettings();
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionAdd(ApiContent $content): ApiResult
	{
		$post = $content->getPost();

		try {

			$product = new Product();
			$product->setName($post->getString('name'));
			$product->setDescription($post->getString('description'));
			$product->setUtility($post->getString('utility'));
			$product->setInactive(false);
			$product->getUnit()->setID($post->getInt('idProductUnit'));

			if ($post->isSetted('idProductFamily')) $product->getCategory()->getFamily()->setID($post->getInt('idProductFamily'));
			if ($post->isSetted('idProductGroup')) $product->getCategory()->getGroup()->setID($post->getInt('idProductGroup'));
			if ($post->isSetted('idProductSubGroup')) $product->getCategory()->getSubgroup()->setID($post->getInt('idProductSubGroup'));
			if ($post->isSetted('idProductSector')) $product->getCategory()->getSector()->setID($post->getInt('idProductSector'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productControl = new ProductControl(System::getWebConnection());
		$productControl->add($product);

		$result = new ApiResultProduct();
		$result->setProduct($product);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProduct"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionSet(ApiContent $content): ApiResult
	{
		$post = $content->getPost();
		$idProduct = $content->getParameters()->getInt('idProduct');
		$productControl = new ProductControl(System::getWebConnection());

		if (($product = $productControl->get($idProduct)) === null)
			throw new ApiException('produto não encontrado');

		try {

			if ($post->isSetted('name')) $product->setName($post->getString('name'));
			if ($post->isSetted('description')) $product->setDescription($post->getString('description'));
			if ($post->isSetted('utility')) $product->setUtility($post->getString('utility'));
			if ($post->isSetted('inactive')) $product->setInactive($post->getBoolean('inactive'));
			if ($post->isSetted('idProductUnit')) $product->getUnit()->setID($post->getInt('idProductUnit'));
			if ($post->isSetted('idProductFamily')) $product->getCategory()->getFamily()->setID($post->getInt('idProductFamily'));
			if ($post->isSetted('idProductGroup')) $product->getCategory()->getGroup()->setID($post->getInt('idProductGroup'));
			if ($post->isSetted('idProductSubGroup')) $product->getCategory()->getSubgroup()->setID($post->getInt('idProductSubGroup'));
			if ($post->isSetted('idProductSector')) $product->getCategory()->getSector()->setID($post->getInt('idProductSector'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$result = new ApiResultProduct();
		$result->setProduct($product);

		if ($productControl->set($product))
			$result->setMessage('produto atualizado');
		else
			$result->setMessage('nenhuma informação alterada');

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProduct","inactive"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionSetInactive(ApiContent $content): ApiResult
	{
		$idProduct = $content->getParameters()->getInt('idProduct');
		$productControl = new ProductControl(System::getWebConnection());

		if (($product = $productControl->get($idProduct)) === null)
			throw new ApiException('produto não encontrado');

		try {

			$product->setInactive($content->getParameters()->getBoolean('inactive'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$result = new ApiResultProduct();
		$result->setProduct($product);

		if ($productControl->set($product))
			$result->setMessage('produto atualizado');
		else
			$result->setMessage('nenhuma informação alterada');

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProduct"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionGet(ApiContent $content): ApiResult
	{
		$idProduct = $content->getParameters()->getInt('idProduct');
		$productControl = new ProductControl(System::getWebConnection());

		if (($product = $productControl->get($idProduct)) === null)
			throw new ApiException('produto não encontrado');

			$result = new ApiResultProduct();
			$result->setProduct($product);

			return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionGetAll(ApiContent $content): ApiResult
	{
		$productControl = new ProductControl(System::getWebConnection());

		if (($products = $productControl->getAll()) === null)
			throw new ApiException('produto não encontrado');

		$result = new ApiResultProducts();
		$result->setProducts($products);

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
			case 'name': return $this->onSearchByName($content);
			case 'family': return $this->onSearchFamily($content);
			case 'group': return $this->onSearchGroup($content);
			case 'subgroup': return $this->onSearchSubGroup($content);
			case 'sector': return $this->onSearchSector($content);
		}

		throw new ApiException('opção inexistente');
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function onSearchByName(ApiContent $content): ApiResult
	{
		$name = $content->getParameters()->getString('value');
		$productControl = new ProductControl(System::getWebConnection());
		$products = $productControl->searchByName($name);

		$result = new ApiResultProducts();
		$result->setProducts($products);

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function onSearchFamily(ApiContent $content): ApiResult
	{
		$idProductFamily = $content->getParameters()->getInt('value');
		$productControl = new ProductControl(System::getWebConnection());
		$products = $productControl->searchByProductFamily($idProductFamily);

		$result = new ApiResultProducts();
		$result->setProducts($products);

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function onSearchGroup(ApiContent $content): ApiResult
	{
		$idProductGroup = $content->getParameters()->getInt('value');
		$productControl = new ProductControl(System::getWebConnection());
		$products = $productControl->searchByProductGroup($idProductGroup);

		$result = new ApiResultProducts();
		$result->setProducts($products);

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function onSearchSubGroup(ApiContent $content): ApiResult
	{
		$idProductSubGroup = $content->getParameters()->getInt('value');
		$productControl = new ProductControl(System::getWebConnection());
		$products = $productControl->searchByProductSubGroup($idProductSubGroup);

		$result = new ApiResultProducts();
		$result->setProducts($products);

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function onSearchSector(ApiContent $content): ApiResult
	{
		$idProductSector = $content->getParameters()->getInt('value');
		$productControl = new ProductControl(System::getWebConnection());
		$products = $productControl->searchByProductSector($idProductSector);

		$result = new ApiResultProducts();
		$result->setProducts($products);

		return $result;
	}
}

