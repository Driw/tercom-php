<?php

namespace tercom\api\site;

use dProject\Primitive\ArrayDataException;
use dProject\Primitive\PostService;
use dProject\restful\ApiContent;
use dProject\restful\ApiConnection;
use dProject\restful\ApiResult;
use dProject\restful\ApiServiceInterface;
use dProject\restful\exception\ApiException;
use dProject\restful\exception\ApiMissParam;
use tercom\api\SiteService;
use tercom\api\site\results\ApiResultCategory;
use tercom\api\site\results\ApiResultCategories;
use tercom\control\ProductFamilyControl;
use tercom\control\ProductGroupControl;
use tercom\core\System;
use tercom\entities\ProductFamily;

class ProductFamilyService extends ApiServiceInterface
{
	public function __construct(ApiConnection $apiConnection, string $apiname, SiteService $parent)
	{
		parent::__contruct($apiConnection, $apiname, $parent);
	}

	public function execute(): ApiResult
	{
		return $this->defaultExecute();
	}

	public function actionAdd(ApiContent $content): ApiResult
	{
		$POST = PostService::getInstance();

		try {

			$productFamily = new ProductFamily();
			$productFamily->setName($POST->getString('name'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productFamilyControl = new ProductFamilyControl(System::getWebConnection());
		$productFamilyControl->add($productFamily);

		$result = new ApiResultCategory();
		$result->setProductCategory($productFamily);

		return $result;
	}

	public function actionSet(ApiContent $content): ApiResult
	{
		$POST = PostService::getInstance();
		$idProductFamily = $content->getParameters()->getInt(0);

		$productFamilyControl = new ProductFamilyControl(System::getWebConnection());

		if (($productFamily = $productFamilyControl->get($idProductFamily)) === null)
			throw new ApiException('família não encontrada');

		try {

			if ($POST->isSetted('name')) $productFamily->setName($POST->getString('name'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$result = new ApiResultCategory();
		$result->setProductCategory($productFamily);

		if ($productFamilyControl->set($productFamily))
			$result->setMessage('família atualizada com êxito');
		else
			$result->setMessage('nenhuma informação atualizada');

		return $result;
	}

	public function actionRemove(ApiContent $content): ApiResult
	{
		$idProductFamily = $content->getParameters()->getInt(0);

		$productFamilyControl = new ProductFamilyControl(System::getWebConnection());

		if (($productFamily = $productFamilyControl->get($idProductFamily)) === null)
			throw new ApiException('família não encontrada');

		$result = new ApiResultCategory();
		$result->setProductCategory($productFamily);

		if ($productFamilyControl->remove($productFamily))
			$result->setMessage('família excluída com êxito');
		else
			$result->setMessage('família já não existe mais');

		return $result;
	}

	public function actionGet(ApiContent $content): ApiResult
	{
		$idProductFamily = $content->getParameters()->getInt(0);
		$productFamilyControl = new ProductFamilyControl(System::getWebConnection());

		if (($productFamily = $productFamilyControl->get($idProductFamily)) === null)
			throw new ApiException('família não encontrada');

		$result = new ApiResultCategory();
		$result->setProductCategory($productFamily);

		return $result;
	}

	public function actionGetGroups(ApiContent $content): ApiResult
	{
		$idProductFamily = $content->getParameters()->getInt(0);
		$productFamilyControl = new ProductFamilyControl(System::getWebConnection());

		if (($productFamily = $productFamilyControl->get($idProductFamily)) === null)
			throw new ApiException('família não encontrada');

		$productGroupControl = new ProductGroupControl(System::getWebConnection());
		$productGroups = $productGroupControl->getByFamily($idProductFamily);
		$productFamily->setProductGroups($productGroups);

		$result = new ApiResultCategory();
		$result->setProductCategory($productFamily);

		return $result;
	}

	public function actionSearch(ApiContent $content): ApiResult
	{
		$name = $content->getParameters()->getString(0);
		$productFamilyControl = new ProductFamilyControl(System::getWebConnection());
		$productCategories = $productFamilyControl->search($name);

		$result = new ApiResultCategories();
		$result->setProductCategories($productCategories);

		return $result;
	}
}
