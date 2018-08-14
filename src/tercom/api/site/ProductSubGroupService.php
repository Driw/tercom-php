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
use tercom\control\ProductSubGroupControl;
use tercom\control\ProductSectorControl;
use tercom\core\System;
use tercom\entities\ProductSubGroup;
use tercom\api\site\results\ApiResultCategory;
use tercom\api\site\results\ApiResultCategories;

class ProductSubGroupService extends ApiServiceInterface
{
	public function __construct(ApiConnection $apiConnection, string $apiname, ApiServiceInterface $parent)
	{
		parent::__construct($apiConnection, $apiname, $parent);
	}

	public function execute():ApiResult
	{
		return $this->defaultExecute();
	}

	public function actionAdd(ApiContent $content):ApiResult
	{
		$POST = PostService::getInstance();

		try {

			$productSubGroup = new ProductSubGroup();
			$productSubGroup->setName($POST->getString('name'));
			$productSubGroup->setProductGroupID($POST->getInt('idProductGroup'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productSubGroupControl = new ProductSubGroupControl(System::getWebConnection());
		$productSubGroupControl->add($productSubGroup);

		$result = new ApiResultCategory();
		$result->setProductCategory($productSubGroup);

		return $result;
	}

	public function actionSet(ApiContent $content):ApiResult
	{
		$POST = PostService::getInstance();
		$idProductSubGroup = $content->getParameters()->getInt(0);

		$productSubGroupControl = new ProductSubGroupControl(System::getWebConnection());

		if (($productSubGroup = $productSubGroupControl->get($idProductSubGroup)) === null)
			throw new ApiException('subgrupo não encontrada');

		try {

			if ($POST->isSetted('name')) $productSubGroup->setName($POST->getString('name'));
			if ($POST->isSetted('idProductGroup')) $productSubGroup->setProductGroupID($POST->getInt('idProductGroup'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$result = new ApiResultCategory();
		$result->setProductCategory($productSubGroup);

		if ($productSubGroupControl->set($productSubGroup))
			$result->setMessage('subgrupo atualizada com êxito');
		else
			$result->setMessage('nenhuma informação atualizada');

		return $result;
	}

	public function actionRemove(ApiContent $content):ApiResult
	{
		$idProductSubGroup = $content->getParameters()->getInt(0);

		$productSubGroupControl = new ProductSubGroupControl(System::getWebConnection());

		if (($productSubGroup = $productSubGroupControl->get($idProductSubGroup)) === null)
			throw new ApiException('subgrupo não encontrada');

		$result = new ApiResultCategory();
		$result->setProductCategory($productSubGroup);

		if ($productSubGroupControl->remove($productSubGroup))
			$result->setMessage('subgrupo excluída com êxito');
		else
			$result->setMessage('subgrupo já não existe mais');

		return $result;
	}

	public function actionGet(ApiContent $content):ApiResult
	{
		$idProductSubGroup = $content->getParameters()->getInt(0);
		$productSubGroupControl = new ProductSubGroupControl(System::getWebConnection());

		if (($productSubGroup = $productSubGroupControl->get($idProductSubGroup)) === null)
			throw new ApiException('subgrupo não encontrada');

			$result = new ApiResultCategory();
			$result->setProductCategory($productSubGroup);

			return $result;
	}

	public function actionGetSectores(ApiContent $content):ApiResult
	{
		$idProductSubGroup = $content->getParameters()->getInt(0);
		$productSubGroupControl = new ProductSubGroupControl(System::getWebConnection());

		if (($productSubGroup = $productSubGroupControl->get($idProductSubGroup)) === null)
			throw new ApiException('subgrupo não encontrada');

		$productSectorControl = new ProductSectorControl(System::getWebConnection());
		$productSectores = $productSectorControl->getBySubGroup($idProductSubGroup);
		$productSubGroup->setProductSectores($productSectores);

		$result = new ApiResultCategory();
		$result->setProductCategory($productSubGroup);

		return $result;
	}

	public function actionSearch(ApiContent $content):ApiResult
	{
		$name = $content->getParameters()->getString(0);
		$productSubGroupControl = new ProductSubGroupControl(System::getWebConnection());
		$productCategories = $productSubGroupControl->search($name);

		$result = new ApiResultCategories();
		$result->setProductCategories($productCategories);

		return $result;
	}
}
