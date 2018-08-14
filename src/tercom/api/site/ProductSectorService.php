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
use tercom\control\ProductSectorControl;
use tercom\entities\ProductSector;
use tercom\api\SiteService;
use tercom\core\System;
use tercom\api\site\results\ApiResultCategory;
use tercom\api\site\results\ApiResultCategories;

class ProductSectorService extends ApiServiceInterface
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

			$productSector = new ProductSector();
			$productSector->setName($POST->getString('name'));
			$productSector->setProductSubGroupID($POST->getInt('idProductSubGroup'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productSectorControl = new ProductSectorControl(System::getWebConnection());
		$productSectorControl->add($productSector);

		$result = new ApiResultCategory();
		$result->setProductCategory($productSector);

		return $result;
	}

	public function actionSet(ApiContent $content):ApiResult
	{
		$POST = PostService::getInstance();
		$idProductSector = $content->getParameters()->getInt(0);

		$productSectorControl = new ProductSectorControl(System::getWebConnection());

		if (($productSector = $productSectorControl->get($idProductSector)) === null)
			throw new ApiException('setor não encontrado');

		try {

			if ($POST->isSetted('name')) $productSector->setName($POST->getString('name'));
			if ($POST->isSetted('idProductSubGroup')) $productSector->setProductSubGroupID($POST->getInt('idProductSubGroup'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$result = new ApiResultCategory();
		$result->setProductCategory($productSector);

		if ($productSectorControl->set($productSector))
			$result->setMessage('setor atualizado com êxito');
		else
			$result->setMessage('nenhuma informação atualizada');

		return $result;
	}

	public function actionRemove(ApiContent $content):ApiResult
	{
		$idProductSector = $content->getParameters()->getInt(0);

		$productSectorControl = new ProductSectorControl(System::getWebConnection());

		if (($productSector = $productSectorControl->get($idProductSector)) === null)
			throw new ApiException('setor não encontrado');

		$result = new ApiResultCategory();
		$result->setProductCategory($productSector);

		if ($productSectorControl->remove($productSector))
			$result->setMessage('setor excluído com êxito');
		else
			$result->setMessage('setor já não existe mais');

		return $result;
	}

	public function actionGet(ApiContent $content):ApiResult
	{
		$idProductSector = $content->getParameters()->getInt(0);
		$productSectorControl = new ProductSectorControl(System::getWebConnection());

		if (($productSector = $productSectorControl->get($idProductSector)) === null)
			throw new ApiException('setor não encontrado');

		$result = new ApiResultCategory();
		$result->setProductCategory($productSector);

		return $result;
	}

	public function actionSearch(ApiContent $content):ApiResult
	{
		$name = $content->getParameters()->getString(0);
		$productSectorControl = new ProductSectorControl(System::getWebConnection());
		$productCategories = $productSectorControl->search($name);

		$result = new ApiResultCategories();
		$result->setProductCategories($productCategories);

		return $result;
	}
}
