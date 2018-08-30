<?php

namespace tercom\api\site;

use dProject\Primitive\ArrayDataException;
use dProject\Primitive\PostService;
use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use dProject\restful\exception\ApiException;
use dProject\restful\exception\ApiMissParam;
use tercom\control\ProductGroupControl;
use tercom\control\ProductSubGroupControl;
use tercom\core\System;
use tercom\entities\ProductGroup;
use tercom\api\site\results\ApiResultCategory;
use tercom\api\site\results\ApiResultCategories;
use tercom\api\site\results\ApiResultCategorySettings;

/**
 * <h1>Serviço de Grupo dos Produtos</h1>
 *
 * <p>Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de grupo dos produtos.
 * Como serviço, oferece as possibilidades de acicionar grupo, atualizar grupo, obter grupo,
 * obter grupos de uma família, remover grupo e procurar por grupo.</p>
 *
 * @see ApiServiceInterface
 * @see ApiConnection
 * @author Andrew
 */

class ProductGroupService extends DefaultSiteService
{
	/**
	 * Ação para se obter as configurações de limites de cada atributo referente aos grupos de produtos.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado com as configurações dos dados de grupo dos produtos.
	 */

	public function actionSettings(ApiContent $content): ApiResult
	{
		return new ApiResultCategorySettings();
	}

	/**
	 * Adiciona um novo grupo dos produtos sendo necessário informar somente o nome.
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado contendo os dados do grupo dos produtos adicionado.
	 */

	public function actionAdd(ApiContent $content): ApiResult
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

	/**
	 * Atualiza os dados de um grupo dos produtos através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiAnnotation({"method":"post", "params":["idProductGroup"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException grupo não encontrada.
	 * @return ApiResult aquisição do resultado com os dados do grupo dos produtos atualizado.
	 */

	public function actionSet(ApiContent $content): ApiResult
	{
		$POST = PostService::getInstance();
		$idProductGroup = $content->getParameters()->getInt('idProductGroup');

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

	/**
	 * Excluí os dados de um grupo dos produtos através do seu código de identificação.
	 * @ApiAnnotation({"params":["idProductGroup"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException grupo não encontrada;
	 * @return ApiResult aquisição dos dados do grupo que foi excluída.
	 */

	public function actionRemove(ApiContent $content): ApiResult
	{
		$idProductGroup = $content->getParameters()->getInt('idProductGroup');

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

	/**
	 * Obtém os dados de um grupo dos produtos através do seu código de identificação.
	 * @ApiAnnotation({"params":["idProductGroup"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException grupo não encontrado.
	 * @return ApiResult aquisição do resultado com os dados do grupo dos produtos obtido.
	 */

	public function actionGet(ApiContent $content):ApiResult
	{
		$idProductGroup = $content->getParameters()->getInt('idProductGroup');
		$productGroupControl = new ProductGroupControl(System::getWebConnection());

		if (($productGroup = $productGroupControl->get($idProductGroup)) === null)
			throw new ApiException('grupo não encontrada');

		$result = new ApiResultCategory();
		$result->setProductCategory($productGroup);

		return $result;
	}

	/**
	 * Obtém os dados dos subgrupos dos produtos vinculados a um grupo dos produtos especificado.
	 * @ApiAnnotation({"params":["idProductGroup"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException grupo não encontrado.
	 * @return ApiResult aquisição do resultado com os dados dos subgrupos dos produtos encontrados.
	 */

	public function actionGetSubGroups(ApiContent $content):ApiResult
	{
		$idProductGroup = $content->getParameters()->getInt('idProductGroup');
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

	/**
	 * Pesquisa por grupos dos produtos através de um filtro e um valor de busca.
	 * @ApiAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException método de pesquisa desconhecido.
	 * @return ApiResult aquisição do resultado com a lista dos grupos dos produtos encontrados.
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
	 * Procedimento interno utilizado para buscar por grupos dos produtos através do nome.
	 * @param string $name nome parcial ou completo do grupo dos produtos à ser procurada.
	 * @return ApiResult aquisição do resultado com a lista dos grupos dos produtos encontrados.
	 */

	private function actionSearchByName(string $name): ApiResult
	{
		$productGroupControl = new ProductGroupControl(System::getWebConnection());
		$productCategories = $productGroupControl->search($name);

		$result = new ApiResultCategories();
		$result->setProductCategories($productCategories);

		return $result;
	}
}
