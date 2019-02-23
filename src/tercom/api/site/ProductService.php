<?php

namespace tercom\api\site;

use dProject\Primitive\StringUtil;
use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultProductSettings;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\entities\Product;
use tercom\api\exceptions\FilterException;
use tercom\entities\ProductCategory;

/**
 * Serviço de Produto
 *
 * Este serviço realiza a comunicação do clietne para com o sistema relação aos dados de produtos.
 * Como serviço, oferece as possibilidades de adicionar, atualizar, ober e procurar produtos,
 * além de ser possível verificar a disponibilidade de um nome para o produto.
 *
 * @see DefaultSiteService
 * @see ApiResultObject
 * @see ApiResultProductSettings
 * @see ApiResultSimpleValidation
 *
 * @author Andrew
 */

class ProductService extends DefaultSiteService
{
	/**
	 * Ação para se obter as configurações de limites de cada atributo referente ao produto.
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultProductSettings
	 */
	public function actionSettings(ApiContent $content): ApiResultProductSettings
	{
		return new ApiResultProductSettings();
	}

	/**
	 * Adiciona um novo produto sendo necessário informar os seguintes dados:
	 * nome, descrição e unidade de produto; opcionalmente utilidade e categoria de produto.
	 * @ApiPermissionAnnotation({"method":"post"})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado contendo os dados do produto adicionado.
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
		if ($post->isSetted('idProductCategory') && !StringUtil::isEmpty($post->getString('idProductCategory')))
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
	 * Atualiza os dados de produto através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiPermissionAnnotation({"params":["idProduct"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados do produto atualizado.
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

		if ($post->isSetted('idProductUnit') && ($idProductUnit = $post->getInt('idProductUnit')) !== $product->getProductUnitId() && $idProductUnit !== 0)
		{
			$productUnit = $this->getProductUnitControl()->get($idProductUnit);
			$product->setProductUnit($productUnit);
		}

		if ($post->isSetted('idProductCategory') && ($idProductCategory = $post->getInt('idProductCategory')) !== $product->getProductCategoryId() && $idProductCategory !== 0)
		{
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
	 * Atualiza o estado de inatividade de produto entre inativo e ativo.
	 * @ApiPermissionAnnotation({"params":["idProduct","inactive"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados do produto atualizado.
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
	 * Define um código de identificação personalizado exclusivo para um cliente.
	 * Considera o cliente em acesso, portanto somente clientes podem usar.
	 * @ApiPermissionAnnotation({"params":["idProduct","idProductCustomer"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados definidos.
	 */
	public function actionSetCustomerId(ApiContent $content): ApiResultObject
	{
		$idProduct = $content->getParameters()->getInt('idProduct');
		$idProductCustomer = $content->getParameters()->getString('idProductCustomer');
		$product = $this->getProductControl()->get($idProduct);
		$product->setIdProductCustomer($idProductCustomer);
		$this->getProductControl()->setCustomerId($product);

		$result = new ApiResultObject();
		$result->setResult($product, 'código "%s" definido para o cliente no produto "%s"', $idProductCustomer, $product->getName());

		return $result;
	}

	/**
	 * Obtém os dados de um determinado produto através do seu código de identificação.
	 * @ApiPermissionAnnotation({"params":["idProduct"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados do produto obitdo.
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
	 * Obtém uma lista com os dados de todos os produtos registrados no sistema.
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado da lista com os dados dos produtos.
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$products = $this->getProductControl()->getAll();

		$result = new ApiResultObject();
		$result->setResult($products, 'há %d produtos no banco de dados', $products->size());

		return $result;
	}

	/**
	 * Realiza uma busca por pordutos usando um único filtro.
	 * @ApiPermissionAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados dos produtos filtrados.
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
	 * Procedimento interno usado para especificar a procura por produtos através do nome do produto.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados dos produtos filtrados.
	 */
	private function searchByName(ApiContent $content): ApiResultObject
	{
		$name = $content->getParameters()->getString('value');
		$products = $this->getProductControl()->searchByName($name);

		$result = new ApiResultObject();
		$result->setResult($products, 'encontrado %d produtos com o nome "%s"', $products->size(), $name);

		return $result;
	}

	/**
	 * Procedimento interno usado para especificar a procura por produtos através da categoria do produto.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados dos produtos filtrados.
	 */
	private function searchByCategory(ApiContent $content): ApiResultObject
	{
		$idProductCategory = $content->getParameters()->getString('value');
		$productCategory = $this->getProductCategoryControl()->get($idProductCategory);
		$products = $this->getProductControl()->searchByProductCategory($idProductCategory);

		$result = new ApiResultObject();
		$result->setResult($products, 'encontrado %d produtos na categoria "%s"', $products->size(), $productCategory->getName());

		return $result;
	}

	/**
	 * Procedimento interno usado para especificar a procura por produtos através da categoria de família do produto.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados dos produtos filtrados.
	 */
	private function searchByFamily(ApiContent $content): ApiResultObject
	{
		$idProductFamily = $content->getParameters()->getString('value');
		$productCategory = $this->getProductCategoryControl()->get($idProductFamily, ProductCategory::CATEGORY_FAMILY);
		$products = $this->getProductControl()->searchByProductFamily($idProductFamily);

		$result = new ApiResultObject();
		$result->setResult($products, 'encontrado %d produtos na categoria "%s"', $products->size(), $productCategory->getName());

		return $result;
	}

	/**
	 * Procedimento interno usado para especificar a procura por produtos através da categoria de grupo do produto.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados dos produtos filtrados.
	 */
	private function searchByGroup(ApiContent $content): ApiResultObject
	{
		$idProductGroup = $content->getParameters()->getString('value');
		$productCategory = $this->getProductCategoryControl()->get($idProductGroup, ProductCategory::CATEGORY_GROUP);
		$products = $this->getProductControl()->searchByProductGroup($idProductGroup);

		$result = new ApiResultObject();
		$result->setResult($products, 'encontrado %d produtos na categoria "%s"', $products->size(), $productCategory->getName());

		return $result;
	}

	/**
	 * Procedimento interno usado para especificar a procura por produtos através da categoria de subgrupo do produto.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados dos produtos filtrados.
	 */
	private function searchBySubgroup(ApiContent $content): ApiResultObject
	{
		$idProductSubgroup = $content->getParameters()->getString('value');
		$productCategory = $this->getProductCategoryControl()->get($idProductSubgroup, ProductCategory::CATEGORY_SUBGROUP);
		$products = $this->getProductControl()->searchByProductSubGroup($idProductSubgroup);

		$result = new ApiResultObject();
		$result->setResult($products, 'encontrado %d produtos na categoria "%s"', $products->size(), $productCategory->getName());

		return $result;
	}

	/**
	 * Procedimento interno usado para especificar a procura por produtos através da categoria de setor do produto.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados dos produtos filtrados.
	 */
	private function searchBySector(ApiContent $content): ApiResultObject
	{
		$idProductSector = $content->getParameters()->getString('value');
		$productCategory = $this->getProductCategoryControl()->get($idProductSector, ProductCategory::CATEGORY_SECTOR);
		$products = $this->getProductControl()->searchByProductSector($idProductSector);

		$result = new ApiResultObject();
		$result->setResult($products, 'encontrado %d produtos na categoria "%s"', $products->size(), $productCategory->getName());

		return $result;
	}

	/**
	 * Verifica a disponibilidade de um valor para um determinado campo de produto.
	 * @ApiPermissionAnnotation({"params":["filter","value","idProduct"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultSimpleValidation aquisição do resultado com a validação da disponibilidade do dado.
	 */
	public function actionAvaiable(ApiContent $content): ApiResultSimpleValidation
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'name': return $this->avaiableName($content);
		}

		throw new FilterException($filter);
	}

	/**
	 * Procedimento interno usado para verificar a disponibilidade de um nome de produto.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultSimpleValidation aquisição do resultado com a validação da disponibilidade do dado.
	 */
	private function avaiableName(ApiContent $content): ApiResultSimpleValidation
	{
		$parameters = $content->getParameters();
		$name = $parameters->getString('value');
		$idProduct = $this->parseNullToInt($parameters->getInt('idProduct', false));
		$avaiable = !$this->getProductControl()->hasName($name, $idProduct);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'nome de produto "%s" %s', $name, $this->getMessageAvaiable($avaiable));

		return $result;
	}
}

