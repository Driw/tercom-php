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
use tercom\entities\ProductSector;
use tercom\control\ProductSectorControl;

class ApiProductSector extends ApiServiceInterface
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

			$productSector = new ProductSector();
			$productSector->setName($POST->getString('name'));
			$productSector->setProductSubGroupID($POST->getInt('idProductSubGroup'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productSectorControl = new ProductSectorControl($this->getMySQL());
		$productSectorControl->add($productSector);

		$result = new ApiResultCategory();
		$result->setProductCategory($productSector);

		return $result;
	}

	public function actionSet(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();
		$idProductSector = $parameters->getInt(0);

		$productSectorControl = new ProductSectorControl($this->getMySQL());

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

	public function actionRemove(ArrayData $parameters):ApiResult
	{
		$idProductSector = $parameters->getInt(0);

		$productSectorControl = new ProductSectorControl($this->getMySQL());

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

	public function actionGet(ArrayData $parameters):ApiResult
	{
		$idProductSector = $parameters->getInt(0);
		$productSectorControl = new ProductSectorControl($this->getMySQL());

		if (($productSector = $productSectorControl->get($idProductSector)) === null)
			throw new ApiException('setor não encontrado');

		$result = new ApiResultCategory();
		$result->setProductCategory($productSector);

		return $result;
	}

	public function actionSearch(ArrayData $parameters):ApiResult
	{
		$name = $parameters->getString(0);
		$productSectorControl = new ProductSectorControl($this->getMySQL());
		$productCategories = $productSectorControl->search($name);

		$result = new ApiResultCategories();
		$result->setProductCategories($productCategories);

		return $result;
	}
}
