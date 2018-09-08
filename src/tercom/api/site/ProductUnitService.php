<?php

namespace tercom\api\site;

use dProject\Primitive\ArrayDataException;
use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use dProject\restful\exception\ApiException;
use dProject\restful\exception\ApiMissParam;
use tercom\api\site\results\ApiResultProductUnit;
use tercom\api\site\results\ApiResultProductUnits;
use tercom\api\site\results\ApiResultProductUnitSettings;
use tercom\entities\ProductUnit;
use tercom\control\ProductUnitControl;
use tercom\core\System;

/**
 * @see DefaultSiteService
 * @see ApiResultProductUnit
 * @see ApiResultProductUnits
 * @see ApiResultProductUnitSettings
 * @author Andrew
 */

class ProductUnitService extends DefaultSiteService
{
	/**
	 * @param ApiContent $content
	 * @return ApiResultProductUnitSettings
	 */

	public function actionSettings(ApiContent $content): ApiResultProductUnitSettings
	{
		return new ApiResultProductUnitSettings();
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionAdd(ApiContent $content): ApiResult
	{
		$post = $content->getPost();

		try {

			$productUnit = new ProductUnit();
			$productUnit->setName($post->getString('name'));
			$productUnit->setShortName($post->getString('shortName'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productUnitControl = new ProductUnitControl(System::getWebConnection());
		$productUnitControl->add($productUnit);

		$result = new ApiResultProductUnit();
		$result->setProductUnit($productUnit);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductUnit"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionSet(ApiContent $content): ApiResult
	{
		$post = $content->getPost();
		$idProductUnit = $content->getParameters()->getInt('idProductUnit');
		$productUnitControl = new ProductUnitControl(System::getWebConnection());

		if (($productUnit = $productUnitControl->get($idProductUnit)) === null)
			throw new ApiException('unidade de produto não encontrado');

		try {

			if ($post->isSetted('name')) $productUnit->setName($post->getString('name'));
			if ($post->isSetted('shortName')) $productUnit->setShortName($post->getString('shortName'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$result = new ApiResultProductUnit();
		$result->setProductUnit($productUnit);

		if ($productUnitControl->set($productUnit))
			$result->setMessage('unidade de produto atualizado');
		else
			$result->setMessage('nenhuma informação alterada');

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductUnit"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionRemove(ApiContent $content): ApiResult
	{
		$idProductUnit = $content->getParameters()->getInt('idProductUnit');
		$productUnitControl = new ProductUnitControl(System::getWebConnection());

		if (($productUnit = $productUnitControl->get($idProductUnit)) === null)
			throw new ApiException('unidade de produto não encontrado');

		$result = new ApiResultProductUnit();
		$result->setProductUnit($productUnit);

		if ($productUnitControl->remove($productUnit))
			$result->setMessage('unidade de produto excluído');
		else
			$result->setMessage('unidade de produto não definido');

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductUnit"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionGet(ApiContent $content): ApiResult
	{
		$idProductUnit = $content->getParameters()->getInt('idProductUnit');
		$productUnitControl = new ProductUnitControl(System::getWebConnection());

		if (($productUnit = $productUnitControl->get($idProductUnit)) === null)
			throw new ApiException('unidade de produto não encontrado');

		$result = new ApiResultProductUnit();
		$result->setProductUnit($productUnit);

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionGetAll(ApiContent $content): ApiResult
	{
		$productUnitControl = new ProductUnitControl(System::getWebConnection());
		$productUnits = $productUnitControl->getAll();

		$result = new ApiResultProductUnits();
		$result->setProductUnits($productUnits);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionSearch(ApiContent $content): ApiResultProductUnits
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
	 * @return ApiResultProductUnits
	 */

	private function onSearchByname(ApiContent $content): ApiResultProductUnits
	{
		$value = $content->getParameters()->getString('value');
		$productUnitControl = new ProductUnitControl(System::getWebConnection());
		$productUnits = $productUnitControl->filterByName($value);

		$result = new ApiResultProductUnits();
		$result->setProductUnits($productUnits);

		return $result;
	}
}

?>