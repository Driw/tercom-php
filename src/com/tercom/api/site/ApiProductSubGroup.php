<?php

namespace tercom\api\site;

use tercom\api\ApiActionInterface;
use tercom\api\ApiConnection;
use tercom\api\ApiResult;
use dProject\Primitive\ArrayData;
use dProject\Primitive\ArrayDataException;
use dProject\Primitive\PostService;
use tercom\api\ApiMissParam;
use tercom\entities\ProductSubGroup;
use tercom\control\ProductSubGroupControl;
use tercom\api\ApiException;
use tercom\control\ProductSectorControl;

class ApiProductSubGroup extends ApiActionInterface
{
	public function __construct(ApiConnection $apiConnection, string $apiname, array $vars)
	{
		parent::__contruct($apiConnection, $apiname, $vars);
	}

	public function execute():ApiResult
	{
		ApiConnection::validateInternalCall();

		return $this->defaultExecute();
	}

	public function actionAdd(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();

		try {

			$productSubGroup = new ProductSubGroup();
			$productSubGroup->setName($POST->getString('name'));
			$productSubGroup->setProductGroupID($POST->getInt('idProductGroup'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productSubGroupControl = new ProductSubGroupControl($this->getMySQL());
		$productSubGroupControl->add($productSubGroup);

		$result = new ApiResultCategory();
		$result->setProductCategory($productSubGroup);

		return $result;
	}

	public function actionSet(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();
		$idProductSubGroup = $parameters->getInt(0);

		$productSubGroupControl = new ProductSubGroupControl($this->getMySQL());

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

	public function actionRemove(ArrayData $parameters):ApiResult
	{
		$idProductSubGroup = $parameters->getInt(0);

		$productSubGroupControl = new ProductSubGroupControl($this->getMySQL());

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

	public function actionGet(ArrayData $parameters):ApiResult
	{
		$idProductSubGroup = $parameters->getInt(0);
		$productSubGroupControl = new ProductSubGroupControl($this->getMySQL());

		if (($productSubGroup = $productSubGroupControl->get($idProductSubGroup)) === null)
			throw new ApiException('subgrupo não encontrada');

			$result = new ApiResultCategory();
			$result->setProductCategory($productSubGroup);

			return $result;
	}

	public function actionGetSectores(ArrayData $parameters):ApiResult
	{
		$idProductSubGroup = $parameters->getInt(0);
		$productSubGroupControl = new ProductSubGroupControl($this->getMySQL());

		if (($productSubGroup = $productSubGroupControl->get($idProductSubGroup)) === null)
			throw new ApiException('subgrupo não encontrada');

		$productSectorControl = new ProductSectorControl($this->getMySQL());
		$productSectores = $productSectorControl->getBySubGroup($idProductSubGroup);
		$productSubGroup->setProductSectores($productSectores);

		$result = new ApiResultCategory();
		$result->setProductCategory($productSubGroup);

		return $result;
	}

	public function actionSearch(ArrayData $parameters):ApiResult
	{
		$name = $parameters->getString(0);
		$productSubGroupControl = new ProductSubGroupControl($this->getMySQL());
		$productCategories = $productSubGroupControl->search($name);

		$result = new ApiResultCategories();
		$result->setProductCategories($productCategories);

		return $result;
	}
}
