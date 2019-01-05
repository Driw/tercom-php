<?php

namespace tercom\api\site;

use dProject\Primitive\PostService;
use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use dProject\restful\exception\ApiException;
use tercom\api\site\results\ApiResultObject;
use tercom\entities\ProductCategory;
use tercom\control\ControlException;

/**
 * Serviço de Subgrupo de Produtos
 *
 * Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de subgrupo dos produtos.
 * Como serviço, oferece as possibilidades de acicionar subgrupo, atualizar subgrupo, obter subgrupo,
 * obter subgrupos de um grupo, remover subgrupo e procurar por subgrupo.
 *
 * @see DefaultSiteService
 * @author Andrew
 */
abstract class ProductCategoryService extends DefaultSiteService
{
	/**
	 *
	 * @return int
	 */
	public abstract function getProductCategoryType(): int;

	/**
	 *
	 * @return int
	 */
	public abstract function getProductCategoryParentType(): int;

	/**
	 *
	 * @return int
	 */
	public abstract function getProductSubCategoryType(): int;

	/**
	 *
	 * @return string
	 */
	public abstract function getIdFieldName(): string;

	/**
	 *
	 * @return string
	 */
	public abstract function getParentIdFieldName(): string;

	/**
	 * Ação para se obter as configurações de limites de cada atributo referente aos subgrupos dos produtos.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado com as configurações dos dados de subgrupos dos produtos.
	 */
	public abstract function actionSettings(ApiContent $content): ApiResult;

	/**
	 * Adiciona um novo subgrupo dos produtos sendo necessário informar somente o nome.
	 * @ApiPermissionAnnotation({"method":"post"})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado contendo os dados do subgrupo dos produtos adicionado.
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = PostService::getInstance();
		$productCategoryParent = null;

		if ($this->getProductCategoryParentType() !== ProductCategory::CATEGORY_NONE && $post->isSetted($this->getParentIdFieldName()))
		{
			$idProductCategoryParent = $post->getInt($this->getParentIdFieldName());
			$productCategoryParent = $this->getProductCategoryControl()->get($idProductCategoryParent, $this->getProductCategoryParentType());
			$productCategoryParent->setType($this->getProductCategoryParentType());
		}

		if (($productCategory = $this->getProductCategoryControl()->getByName(($name = $post->getString('name')))) === null)
			$productCategory = new ProductCategory();

		$productCategory->setName($name);
		$productCategory->setType($this->getProductCategoryType());

		if ($productCategoryParent === null)
			$this->getProductCategoryControl()->add($productCategory);
		else
		{
			if ($productCategory->getId() === 0)
				$this->getProductCategoryControl()->addRelationship($productCategoryParent, $productCategory);
			else
				$this->getProductCategoryControl()->setRelationship($productCategoryParent, $productCategory);
		}

		$result = new ApiResultObject();
		$result->setObject($productCategory);
		$result->setMessage('categoria de produto "%s" adicionada com êxito', $productCategory->getName());

		return $result;
	}

	/**
	 * Atualiza os dados de um subgrupo dos produtos através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiPermissionAnnotation({"method":"post", "params":["idProductCategory"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados do subgrupo dos produtos atualizado.
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = PostService::getInstance();
		$idProductCategory = $content->getParameters()->getInt('idProductCategory');
		$productCategory = $this->getProductCategoryControl()->get($idProductCategory, $this->getProductCategoryType());

		if ($post->isSetted('name')) $productCategory->setName($post->getString('name'));
		if ($this->getProductCategoryParentType() !== ProductCategory::CATEGORY_NONE && $post->isSetted($this->getParentIdFieldName()))
		{
			try {
				// Verificar se a categoria superior existe
				$idProductCategoryParent = $post->getInt($this->getParentIdFieldName());
				$this->getProductCategoryControl()->get($idProductCategoryParent, $this->getProductCategoryParentType());
			} catch (ControlException $e) {
				throw new ControlException('categoria de produto a vincular não encontrado');
			}
		}

		$this->getProductCategoryControl()->set($productCategory);

		$result = new ApiResultObject();
		$result->setObject($productCategory);
		$result->setMessage('categoria de produto "%s" atualizada com êxito', $productCategory->getName());

		return $result;
	}

	/**
	 * Excluí os dados de um subgrupo dos produtos através do seu código de identificação.
	 * @ApiPermissionAnnotation({"params":["idProductCategory"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição dos dados do subgrupo que foi excluída.
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$idProductCategory = $content->getParameters()->getInt('idProductCategory');
		$productCategory = $this->getProductCategoryControl()->get($idProductCategory, $this->getProductCategoryType());
		$productCategory->setType($this->getProductCategoryType());
		$this->getProductCategoryControl()->remove($productCategory, $this->getProductCategoryType());

		$result = new ApiResultObject();
		$result->setObject($productCategory);
		$result->setMessage('categoria de produto "%s" excluída com êxito', $productCategory->getName());

		return $result;
	}

	/**
	 * Obtém os dados de um subgrupo dos produtos através do seu código de identificação.
	 * @ApiPermissionAnnotation({"params":["idProductCategory"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado com os dados do subgrupo dos produtos obtido.
	 */
	public function actionGet(ApiContent $content): ApiResult
	{
		$idProductCategory = $content->getParameters()->getInt('idProductCategory');
		$productCategory = $this->getProductCategoryControl()->get($idProductCategory, $this->getProductCategoryType());
		$productCategory->setType(ProductCategory::CATEGORY_SUBGROUP);

		$result = new ApiResultObject();
		$result->setObject($productCategory);
		$result->setMessage('categoria de produto "%s" obtida com êxito', $productCategory->getName());

		return $result;
	}

	/**
	 * Obtém os dados dos setores dos produtos vinculados a um subgrupo dos produtos especificado.
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado com os dados dos setores dos produtos encontrados.
	 */
	public function actionGetAll(ApiContent $content):ApiResult
	{
		$productCategories = $this->getProductCategoryControl()->getAll();

		$result = new ApiResultObject();
		$result->setObject($productCategories);
		$result->setMessage('encontrado "%d" categorias de produto no banco de dados', $productCategories->size());

		return $result;
	}

	/**
	 * Obtém os dados dos setores dos produtos vinculados a um subgrupo dos produtos especificado.
	 * @ApiPermissionAnnotation({"params":["idProductCategory"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException subgrupo não encontrado.
	 * @return ApiResult aquisição do resultado com os dados dos setores dos produtos encontrados.
	 */
	public function actionGetCategories(ApiContent $content):ApiResult
	{
		$idProductCategory = $content->getParameters()->getInt('idProductCategory');
		$productCategory = $this->getProductCategoryControl()->get($idProductCategory, $this->getProductCategoryType());
		$productCategories = $this->getProductCategoryControl()->getCategories($productCategory, $this->getProductSubCategoryType());
		$productCategory->setProductCategories($productCategories);

		$result = new ApiResultObject();
		$result->setObject($productCategory);
		$result->setMessage('categorias de produto para "%s" obtida com êxito', $productCategory->getName());

		return $result;
	}

	/**
	 * Pesquisa por subgrupos dos produtos através de um filtro e um valor de busca.
	 * @ApiPermissionAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado com a lista dos subgrupos dos produtos encontrados.
	 */
	public function actionSearch(ApiContent $content):ApiResult
	{
		$filter = $content->getParameters()->getString('filter');
		$value = $content->getParameters()->getString('value');

		switch ($filter)
		{
			case 'name': return $this->actionSearchByName($value);
		}

		throw new ApiException('método de pesquisa desconhecido');
	}

	/**
	 * Procedimento interno utilizado para buscar por subgrupos dos produtos através do nome.
	 * @param string $name nome parcial ou completo do subgrupo dos produtos à ser procurada.
	 * @return ApiResultObject aquisição do resultado com a lista dos subgrupos dos produtos encontrados.
	 */
	private function actionSearchByName(string $name): ApiResultObject
	{
		$productCategories = $this->getProductCategoryControl()->searchByname($name, $this->getProductCategoryType());

		$result = new ApiResultObject();
		$result->setObject($productCategories);
		$result->setMessage('encontrado %d resultados para "%s"', $productCategories->size(), $name);

		return $result;
	}
}
