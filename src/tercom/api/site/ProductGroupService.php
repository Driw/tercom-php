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
use tercom\control\ProductGroupControl;
use tercom\control\ProductSubGroupControl;
use tercom\core\System;
use tercom\entities\ProductGroup;
use tercom\api\site\results\ApiResultCategory;
use tercom\api\site\results\ApiResultCategories;

class ProductGroupService extends ApiServiceInterface
{
	public function __construct(ApiConnection $apiConnection, string $apiname, SiteService $parent)
	{
		parent::__contruct($apiConnection, $apiname, $parent);
	}

	public function execute():ApiResult
	{
		return $this->defaultExecute();
	}

	public function actionAdd(ApiContent $content):ApiResult
	{
		$POST = PostService::getInstance();

		try {

			$productGroup = new ProductGroup();
			$productGroup->setName($POST->getString('name'));
			$productGroup->setProductFamilyID($POST->getInt('idProductFamily'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productGroupControl = new ProductGroupControl(System::getWebConnection());
		$productGroupControl->add($productGroup);

		$result = new ApiResultCategory();
		$result->setProductCategory($productGroup);

		return $result;
	}

	public function actionSet(ApiContent $content):ApiResult
	{
		$POST = PostService::getInstance();
		$idProductGroup = $content->getParameters()->getInt(0);

		$productGroupControl = new ProductGroupControl(System::getWebConnection());

		if (($productGroup = $productGroupControl->get($idProductGroup)) === null)
			throw new ApiException('grupo não encontrada');

		try {

			if ($POST->isSetted('name')) $productGroup->setName($POST->getString('name'));
			if ($POST->isSetted('idProductFamily')) $productGroup->setProductFamilyID($POST->getInt('idProductFamily'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$result = new ApiResultCategory();
		$result->setProductCategory($productGroup);

		if ($productGroupControl->set($productGroup))
			$result->setMessage('grupo atualizada com êxito');
		else
			$result->setMessage('nenhuma informação atualizada');

		return $result;
	}

	public function actionRemove(ApiContent $content):ApiResult
	{
		$idProductGroup = $content->getParameters()->getInt(0);

		$productGroupControl = new ProductGroupControl(System::getWebConnection());

		if (($productGroup = $productGroupControl->get($idProductGroup)) === null)
			throw new ApiException('grupo não encontrada');

		$result = new ApiResultCategory();
		$result->setProductCategory($productGroup);

		if ($productGroupControl->remove($productGroup))
			$result->setMessage('grupo excluída com êxito');
		else
			$result->setMessage('grupo já não existe mais');

		return $result;
	}

	public function actionGet(ApiContent $content):ApiResult
	{
		$idProductGroup = $content->getParameters()->getInt(0);
		$productGroupControl = new ProductGroupControl(System::getWebConnection());

		if (($productGroup = $productGroupControl->get($idProductGroup)) === null)
			throw new ApiException('grupo não encontrada');

		$result = new ApiResultCategory();
		$result->setProductCategory($productGroup);

		return $result;
	}

	public function actionGetSubGroups(ApiContent $content):ApiResult
	{
		$idProductGroup = $content->getParameters()->getInt(0);
		$productGroupControl = new ProductGroupControl(System::getWebConnection());

		if (($productGroup = $productGroupControl->get($idProductGroup)) === null)
			throw new ApiException('grupo não encontrada');

		$productSubGroupControl = new ProductSubGroupControl(System::getWebConnection());
		$productSubGroups = $productSubGroupControl->getByGroup($idProductGroup);
		$productGroup->setProductSubGroups($productSubGroups);

		$result = new ApiResultCategory();
		$result->setProductCategory($productGroup);

		return $result;
	}

	public function actionSearch(ApiContent $content):ApiResult
	{
		$name = $content->getParameters()->getString(0);
		$productGroupControl = new ProductGroupControl(System::getWebConnection());
		$productCategories = $productGroupControl->search($name);

		$result = new ApiResultCategories();
		$result->setProductCategories($productCategories);

		return $result;
	}
}
