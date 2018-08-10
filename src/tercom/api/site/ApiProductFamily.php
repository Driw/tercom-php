<?php

namespace tercom\api\site;

use dProject\Primitive\ArrayData;
use dProject\Primitive\ArrayDataException;
use dProject\Primitive\PostService;
use dProject\restful\ApiConnection;
use dProject\restful\ApiResult;
use dProject\restful\ApiServiceInterface;
use dProject\restful\exception\ApiException;
use dProject\restful\exception\ApiMissParam;
use tercom\entities\ProductFamily;
use tercom\control\ProductFamilyControl;
use tercom\control\ProductGroupControl;

class ApiProductFamily extends ApiServiceInterface
{
	public function __construct(ApiConnection $apiConnection, string $apiname, array $vars)
	{
		parent::__contruct($apiConnection, $apiname, $vars);
	}

	public function execute(): ApiResult
	{
		ApiConnection::validateInternalCall();

		return $this->defaultExecute();
	}

	public function actionAdd(ArrayData $parameters): ApiResult
	{
		$POST = PostService::getInstance();

		try {

			$productFamily = new ProductFamily();
			$productFamily->setName($POST->getString('name'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productFamilyControl = new ProductFamilyControl($this->getMySQL());
		$productFamilyControl->add($productFamily);

		$result = new ApiResultCategory();
		$result->setProductCategory($productFamily);

		return $result;
	}

	public function actionSet(ArrayData $parameters): ApiResult
	{
		$POST = PostService::getInstance();
		$idProductFamily = $parameters->getInt(0);

		$productFamilyControl = new ProductFamilyControl($this->getMySQL());

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

	public function actionRemove(ArrayData $parameters): ApiResult
	{
		$idProductFamily = $parameters->getInt(0);

		$productFamilyControl = new ProductFamilyControl($this->getMySQL());

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

	public function actionGet(ArrayData $parameters): ApiResult
	{
		$idProductFamily = $parameters->getInt(0);
		$productFamilyControl = new ProductFamilyControl($this->getMySQL());

		if (($productFamily = $productFamilyControl->get($idProductFamily)) === null)
			throw new ApiException('família não encontrada');

		$result = new ApiResultCategory();
		$result->setProductCategory($productFamily);

		return $result;
	}

	public function actionGetGroups(ArrayData $parameters): ApiResult
	{
		$idProductFamily = $parameters->getInt(0);
		$productFamilyControl = new ProductFamilyControl($this->getMySQL());

		if (($productFamily = $productFamilyControl->get($idProductFamily)) === null)
			throw new ApiException('família não encontrada');

		$productGroupControl = new ProductGroupControl($this->getMySQL());
		$productGroups = $productGroupControl->getByFamily($idProductFamily);
		$productFamily->setProductGroups($productGroups);

		$result = new ApiResultCategory();
		$result->setProductCategory($productFamily);

		return $result;
	}

	public function actionSearch(ArrayData $parameters): ApiResult
	{
		$name = $parameters->getString(0);
		$productFamilyControl = new ProductFamilyControl($this->getMySQL());
		$productCategories = $productFamilyControl->search($name);

		$result = new ApiResultCategories();
		$result->setProductCategories($productCategories);

		return $result;
	}
}
