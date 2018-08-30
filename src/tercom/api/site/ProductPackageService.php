<?php

namespace tercom\api\site;

use dProject\Primitive\ArrayDataException;
use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use dProject\restful\exception\ApiMissParam;
use dProject\restful\exception\ApiException;
use tercom\api\site\results\ApiResultProductPackage;
use tercom\api\site\results\ApiResultProductPackages;
use tercom\api\site\results\ApiResultProductPackageSettings;
use tercom\entities\ProductPackage;
use tercom\control\ProductPackageControl;
use tercom\core\System;

/**
 * @see DefaultSiteService
 * @see ApiResultProductPackage
 * @see ApiResultProductPackages
 * @see ApiResultProductPackageSettings
 * @author Andrew
 */

class ProductPackageService extends DefaultSiteService
{
	/**
	 * @param ApiContent $content
	 * @return ApiResultProductPackageSettings
	 */

	public function actionSettings(ApiContent $content): ApiResultProductPackageSettings
	{
		return new ApiResultProductPackageSettings();
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionAdd(ApiContent $content): ApiResult
	{
		$post = $content->getPost();

		try {

			$productPackage = new ProductPackage();
			$productPackage->setName($post->getString('name'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productPackageControl = new ProductPackageControl(System::getWebConnection());
		$productPackageControl->add($productPackage);

		$result = new ApiResultProductPackage();
		$result->setProductPackage($productPackage);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductPackage"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionSet(ApiContent $content): ApiResult
	{
		$post = $content->getPost();
		$idProductPackage = $content->getParameters()->getInt('idProductPackage');
		$productPackageControl = new ProductPackageControl(System::getWebConnection());

		if (($productPackage = $productPackageControl->get($idProductPackage)) === null)
			throw new ApiException('embalagem de produto não encontrado');

		try {

			if ($post->isSetted('name')) $productPackage->setName($post->getString('name'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$result = new ApiResultProductPackage();
		$result->setProductPackage($productPackage);

		if ($productPackageControl->set($productPackage))
			$result->setMessage('embalagem de produto atualizado');
		else
			$result->setMessage('nenhuma informação alterada');

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductPackage"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionRemove(ApiContent $content): ApiResult
	{
		$idProductPackage = $content->getParameters()->getInt('idProductPackage');
		$productPackageControl = new ProductPackageControl(System::getWebConnection());

		if (($productPackage = $productPackageControl->get($idProductPackage)) === null)
			throw new ApiException('embalagem de produto não encontrado');

		$result = new ApiResultProductPackage();
		$result->setProductPackage($productPackage);

		if ($productPackageControl->remove($productPackage))
			$result->setMessage('embalagem de produto excluído');
		else
			$result->setMessage('embalagem de produto não definido');

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductPackage"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionGet(ApiContent $content): ApiResult
	{
		$idProductPackage = $content->getParameters()->getInt('idProductPackage');
		$productPackageControl = new ProductPackageControl(System::getWebConnection());

		if (($productPackage = $productPackageControl->get($idProductPackage)) === null)
			throw new ApiException('embalagem de produto não encontrado');

		$result = new ApiResultProductPackage();
		$result->setProductPackage($productPackage);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionSearch(ApiContent $content): ApiResultProductPackages
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
	 * @return ApiResultProductPackages
	 */

	private function onSearchByname(ApiContent $content): ApiResultProductPackages
	{
		$value = $content->getParameters()->getString('value');
		$productPackageControl = new ProductPackageControl(System::getWebConnection());
		$productPackages = $productPackageControl->filterByName($value);

		$result = new ApiResultProductPackages();
		$result->setProductPackages($productPackages);

		return $result;
	}
}

?>