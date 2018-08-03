<?php

namespace tercom\api\site;

use dProject\Primitive\ArrayData;
use dProject\Primitive\ArrayDataException;
use dProject\Primitive\PostService;
use tercom\api\ApiActionInterface;
use tercom\api\ApiConnection;
use tercom\api\ApiException;
use tercom\api\ApiMissParam;
use tercom\api\ApiResult;
use tercom\control\ProductGroupControl;
use tercom\entities\ProductGroup;
use tercom\control\ProductSubGroupControl;

class ApiProductGroup extends ApiActionInterface
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

			$productGroup = new ProductGroup();
			$productGroup->setName($POST->getString('name'));
			$productGroup->setProductFamilyID($POST->getInt('idProductFamily'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productGroupControl = new ProductGroupControl($this->getMySQL());
		$productGroupControl->add($productGroup);

		$result = new ApiResultCategory();
		$result->setProductCategory($productGroup);

		return $result;
	}

	public function actionSet(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();
		$idProductGroup = $parameters->getInt(0);

		$productGroupControl = new ProductGroupControl($this->getMySQL());

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

	public function actionRemove(ArrayData $parameters):ApiResult
	{
		$idProductGroup = $parameters->getInt(0);

		$productGroupControl = new ProductGroupControl($this->getMySQL());

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

	public function actionGet(ArrayData $parameters):ApiResult
	{
		$idProductGroup = $parameters->getInt(0);
		$productGroupControl = new ProductGroupControl($this->getMySQL());

		if (($productGroup = $productGroupControl->get($idProductGroup)) === null)
			throw new ApiException('grupo não encontrada');

		$result = new ApiResultCategory();
		$result->setProductCategory($productGroup);

		return $result;
	}

	public function actionGetSubGroups(ArrayData $parameters):ApiResult
	{
		$idProductGroup = $parameters->getInt(0);
		$productGroupControl = new ProductGroupControl($this->getMySQL());

		if (($productGroup = $productGroupControl->get($idProductGroup)) === null)
			throw new ApiException('grupo não encontrada');

		$productSubGroupControl = new ProductSubGroupControl($this->getMySQL());
		$productSubGroups = $productSubGroupControl->getByGroup($idProductGroup);
		$productGroup->setProductSubGroups($productSubGroups);

		$result = new ApiResultCategory();
		$result->setProductCategory($productGroup);

		return $result;
	}

	public function actionSearch(ArrayData $parameters):ApiResult
	{
		$name = $parameters->getString(0);
		$productGroupControl = new ProductGroupControl($this->getMySQL());
		$productCategories = $productGroupControl->search($name);

		$result = new ApiResultCategories();
		$result->setProductCategories($productCategories);

		return $result;
	}
}
