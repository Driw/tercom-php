<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultProductSettings;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\entities\Product;
use tercom\api\exceptions\FilterException;
use tercom\entities\ProductCategory;

/**
 * @see DefaultSiteService
 * @see ApiResultProductSettings
 * @see ApiResultObject
 * @author Andrew
 */

class ProductService extends DefaultSiteService
{
	/**
	 * @param ApiContent $content
	 * @return ApiResultProductSettings
	 */
	public function actionSettings(ApiContent $content): ApiResultProductSettings
	{
		return new ApiResultProductSettings();
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProductUnit = $post->getInt('idProductUnit');
		$productUnit = $this->getProductUnitControl()->get($idProductUnit);

		$product = new Product();
		$product->setName($post->getString('name'));
		$product->setDescription($post->getString('description'));
		$product->setInactive(false);
		$product->setProductUnit($productUnit);

		if ($post->isSetted('utitlity')) $product->setUtility($post->getString('utility'));
		if ($post->isSetted('idProductCategory'))
		{
			$idProductCategory = $post->getInt('idProductCategory');
			$idProductCategoryType = $this->parseNullToInt($post->getInt('idProductCategoryType', false));
			$productCategory = $this->getProductCategoryControl()->get($idProductCategory, $idProductCategoryType);
			$product->setProductCategory($productCategory);
		}

		$this->getProductControl()->add($product);

		$result = new ApiResultObject();
		$result->setResult($product, 'produto "%s" adicionado com êxito', $product->getName());

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProduct = $content->getParameters()->getInt('idProduct');
		$product = $this->getProductControl()->get($idProduct);

		if ($post->isSetted('name')) $product->setName($post->getString('name'));
		if ($post->isSetted('description')) $product->setDescription($post->getString('description'));
		if ($post->isSetted('utility')) $product->setUtility($post->getString('utility'));
		if ($post->isSetted('inactive')) $product->setInactive($post->getBoolean('inactive'));

		if ($post->isSetted('idProductUnit'))
		{
			$idProductUnit = $post->getInt('idProductUnit');
			$productUnit = $this->getProductUnitControl()->get($idProductUnit);
			$product->setProductUnit($productUnit);
		}
		if ($post->isSetted('idProductCategory'))
		{
			$idProductCategory = $post->getInt('idProductCategory');
			$idProductCategoryType = $this->parseNullToInt($post->getInt('idProductCategoryType', false));
			$productCategory = $this->getProductCategoryControl()->get($idProductCategory, $idProductCategoryType);
			$product->setProductCategory($productCategory);
		}

		$this->getProductControl()->set($product);

		$result = new ApiResultObject();
		$result->setResult($product, 'produto "%s" atualizado com êxito', $product->getName());

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProduct","inactive"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSetInactive(ApiContent $content): ApiResultObject
	{
		$idProduct = $content->getParameters()->getInt('idProduct');
		$product = $this->getProductControl()->get($idProduct);
		$product->setInactive($content->getParameters()->getBoolean('inactive'));
		$this->getProductControl()->set($product);
		$inactiveStatus = $product->isInactive() ? 'desativado' : 'ativado';

		$result = new ApiResultObject();
		$result->setResult($product, 'produto "%s" %s com êxito', $product->getName(), $inactiveStatus);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idProduct = $content->getParameters()->getInt('idProduct');
		$product = $this->getProductControl()->get($idProduct);

		$result = new ApiResultObject();
		$result->setResult($product, 'produto "%s" obtido com êxito', $product->getName());

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$products = $this->getProductControl()->getAll();

		$result = new ApiResultObject();
		$result->setResult($products, 'há %d produtos no banco de dados', $products->size());

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSearch(ApiContent $content): ApiResultObject
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'name': return $this->searchByName($content);
			case 'category': return $this->searchByCategory($content);
			case 'family': return $this->searchByFamily($content);
			case 'group': return $this->searchByGroup($content);
			case 'subgroup': return $this->searchBySubGroup($content);
			case 'sector': return $this->searchBySector($content);
		}

		throw new FilterException($filter);
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function searchByName(ApiContent $content): ApiResultObject
	{
		$name = $content->getParameters()->getString('value');
		$products = $this->getProductControl()->searchByName($name);

		$result = new ApiResultObject();
		$result->setResult($products, 'encontrado %d produtos com o nome "%s"', $products->size(), $name);

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function searchByCategory(ApiContent $content): ApiResultObject
	{
		$idProductCategory = $content->getParameters()->getString('value');
		$productCategory = $this->getProductCategoryControl()->get($idProductCategory);
		$products = $this->getProductControl()->searchByProductCategory($idProductCategory);

		$result = new ApiResultObject();
		$result->setResult($products, 'encontrado %d produtos na categoria "%s"', $products->size(), $productCategory->getName());

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function searchByFamily(ApiContent $content): ApiResultObject
	{
		$idProductFamily = $content->getParameters()->getString('value');
		$productCategory = $this->getProductCategoryControl()->get($idProductFamily, ProductCategory::CATEGORY_FAMILY);
		$products = $this->getProductControl()->searchByProductFamily($idProductFamily);

		$result = new ApiResultObject();
		$result->setResult($products, 'encontrado %d produtos na categoria "%s"', $products->size(), $productCategory->getName());

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function searchByGroup(ApiContent $content): ApiResultObject
	{
		$idProductGroup = $content->getParameters()->getString('value');
		$productCategory = $this->getProductCategoryControl()->get($idProductGroup, ProductCategory::CATEGORY_GROUP);
		$products = $this->getProductControl()->searchByProductGroup($idProductGroup);

		$result = new ApiResultObject();
		$result->setResult($products, 'encontrado %d produtos na categoria "%s"', $products->size(), $productCategory->getName());

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function searchBySubgroup(ApiContent $content): ApiResultObject
	{
		$idProductSubgroup = $content->getParameters()->getString('value');
		$productCategory = $this->getProductCategoryControl()->get($idProductSubgroup, ProductCategory::CATEGORY_SUBGROUP);
		$products = $this->getProductControl()->searchByProductSubGroup($idProductSubgroup);

		$result = new ApiResultObject();
		$result->setResult($products, 'encontrado %d produtos na categoria "%s"', $products->size(), $productCategory->getName());

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function searchBySector(ApiContent $content): ApiResultObject
	{
		$idProductSector = $content->getParameters()->getString('value');
		$productCategory = $this->getProductCategoryControl()->get($idProductSector, ProductCategory::CATEGORY_SECTOR);
		$products = $this->getProductControl()->searchByProductSector($idProductSector);

		$result = new ApiResultObject();
		$result->setResult($products, 'encontrado %d produtos na categoria "%s"', $products->size(), $productCategory->getName());

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["filter","value","idProduct"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAvaiable(ApiContent $content): ApiResultObject
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'name': return $this->avaiableName($content);
		}

		throw new FilterException($filter);
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultSimpleValidation
	 */
	public function avaiableName(ApiContent $content): ApiResultSimpleValidation
	{
		$parameters = $content->getParameters();
		$name = $parameters->getString('value');
		$idProduct = $this->parseNullToInt($parameters->getInt('idProduct', false));
		$avaiable = $this->getProductControl()->hasName($name, $idProduct) ? 'disponível' : 'indisponível';

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'nome de produto "%s" %s', $name, $avaiable);

		return $result;
	}
}

