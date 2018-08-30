<?php

namespace tercom\api\site;

use dProject\Primitive\ArrayDataException;
use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use dProject\restful\exception\ApiMissParam;
use tercom\api\site\results\ApiResultProductType;
use tercom\api\site\results\ApiResultProductTypes;
use tercom\api\site\results\ApiResultProductTypeSettings;
use tercom\entities\ProductType;
use tercom\control\ProductTypeControl;
use tercom\core\System;
use dProject\restful\exception\ApiException;

/**
 * @see DefaultSiteService
 * @see ApiResultProductType
 * @see ApiResultProductTypes
 * @see ApiResultProductTypeSettings
 * @author Andrew
 */

class ProductTypeService extends DefaultSiteService
{
	/**
	 * @param ApiContent $content
	 * @return ApiResultProductTypeSettings
	 */

	public function actionSettings(ApiContent $content): ApiResultProductTypeSettings
	{
		return new ApiResultProductTypeSettings();
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionAdd(ApiContent $content): ApiResult
	{
		$post = $content->getPost();

		try {

			$productType = new ProductType();
			$productType->setName($post->getString('name'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productTypeControl = new ProductTypeControl(System::getWebConnection());
		$productTypeControl->add($productType);

		$result = new ApiResultProductType();
		$result->setProductType($productType);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductType"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionSet(ApiContent $content): ApiResult
	{
		$post = $content->getPost();
		$idProductType = $content->getParameters()->getInt('idProductType');
		$productTypeControl = new ProductTypeControl(System::getWebConnection());

		if (($productType = $productTypeControl->get($idProductType)) === null)
			throw new ApiException('tipo de produto não encontrado');

		try {

			if ($post->isSetted('name')) $productType->setName($post->getString('name'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$result = new ApiResultProductType();
		$result->setProductType($productType);

		if ($productTypeControl->set($productType))
			$result->setMessage('tipo de produto atualizado');
		else
			$result->setMessage('nenhuma informação alterada');

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductType"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionRemove(ApiContent $content): ApiResult
	{
		$idProductType = $content->getParameters()->getInt('idProductType');
		$productTypeControl = new ProductTypeControl(System::getWebConnection());

		if (($productType = $productTypeControl->get($idProductType)) === null)
			throw new ApiException('tipo de produto não encontrado');

		$result = new ApiResultProductType();
		$result->setProductType($productType);

		if ($productTypeControl->remove($productType))
			$result->setMessage('tipo de produto excluído');
		else
			$result->setMessage('tipo de produto não definido');

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductType"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionGet(ApiContent $content): ApiResult
	{
		$idProductType = $content->getParameters()->getInt('idProductType');
		$productTypeControl = new ProductTypeControl(System::getWebConnection());

		if (($productType = $productTypeControl->get($idProductType)) === null)
			throw new ApiException('tipo de produto não encontrado');

		$result = new ApiResultProductType();
		$result->setProductType($productType);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionSearch(ApiContent $content): ApiResultProductTypes
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'name': return $this->onSearchByName($content);
		}

		throw new ApiException('opção inexistente');
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultProductTypes
	 */

	private function onSearchByname(ApiContent $content): ApiResultProductTypes
	{
		$value = $content->getParameters()->getString('value');
		$productTypeControl = new ProductTypeControl(System::getWebConnection());
		$productTypes = $productTypeControl->filterByName($value);

		$result = new ApiResultProductTypes();
		$result->setProductTypes($productTypes);

		return $result;
	}
}

?>