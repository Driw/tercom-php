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
use tercom\api\site\results\ApiResultCategorySettings;

/**
 * <h1>Serviço de Subgrupo de Produtos</h1>
 *
 * <p>Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de subgrupo dos produtos.
 * Como serviço, oferece as possibilidades de acicionar subgrupo, atualizar subgrupo, obter subgrupo,
 * obter subgrupos de um grupo, remover subgrupo e procurar por subgrupo.</p>
 *
 * @see ApiServiceInterface
 * @see ApiConnection
 * @author Andrew
 */

class ProductSubGroupService extends ApiServiceInterface
{
	/**
	 * Cria uma nova instância de um serviço para gerenciamento de subgrupos dos produtos no sistema.
	 * @param ApiConnection $apiConnection conexão do sistema que realiza o chamado do serviço.
	 * @param string $apiname nome do serviço que está sendo informado através da conexão.
	 * @param ApiServiceInterface $parent serviço do qual solicitou o chamado.
	 */

	public function __construct(ApiConnection $apiConnection, string $apiname, ApiServiceInterface $parent)
	{
		parent::__construct($apiConnection, $apiname, $parent);
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::execute()
	 */

	public function execute(): ApiResult
	{
		return $this->defaultExecute();
	}

	/**
	 * Ação para se obter as configurações de limites de cada atributo referente aos subgrupos dos produtos.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado com as configurações dos dados de subgrupos dos produtos.
	 */

	public function actionSettings(ApiContent $content): ApiResult
	{
		return new ApiResultCategorySettings();
	}

	/**
	 * Adiciona um novo subgrupo dos produtos sendo necessário informar somente o nome.
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado contendo os dados do subgrupo dos produtos adicionado.
	 */

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

	/**
	 * Atualiza os dados de um subgrupo dos produtos através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiAnnotation({"method":"post", "params":["idProductSubGroup"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException grupo não encontrada.
	 * @return ApiResult aquisição do resultado com os dados do subgrupo dos produtos atualizado.
	 */

	public function actionSet(ApiContent $content): ApiResult
	{
		$POST = PostService::getInstance();
		$idProductSubGroup = $content->getParameters()->getInt('idProductSubGroup');

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

	/**
	 * Excluí os dados de um subgrupo dos produtos através do seu código de identificação.
	 * @ApiAnnotation({"params":["idProductSubGroup"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException subgrupo não encontrada;
	 * @return ApiResult aquisição dos dados do subgrupo que foi excluída.
	 */

	public function actionRemove(ApiContent $content): ApiResult
	{
		$idProductSubGroup = $content->getParameters()->getInt('idProductSubGroup');

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

	/**
	 * Obtém os dados de um subgrupo dos produtos através do seu código de identificação.
	 * @ApiAnnotation({"params":["idProductSubGroup"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException subgrupo não encontrado.
	 * @return ApiResult aquisição do resultado com os dados do subgrupo dos produtos obtido.
	 */

	public function actionGet(ApiContent $content): ApiResult
	{
		$idProductSubGroup = $content->getParameters()->getInt('idProductSubGroup');
		$productSubGroupControl = new ProductSubGroupControl(System::getWebConnection());

		if (($productSubGroup = $productSubGroupControl->get($idProductSubGroup)) === null)
			throw new ApiException('subgrupo não encontrada');

			$result = new ApiResultCategory();
			$result->setProductCategory($productSubGroup);

			return $result;
	}

	/**
	 * Obtém os dados dos setores dos produtos vinculados a um subgrupo dos produtos especificado.
	 * @ApiAnnotation({"params":["idProductSubGroup"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException subgrupo não encontrado.
	 * @return ApiResult aquisição do resultado com os dados dos setores dos produtos encontrados.
	 */

	public function actionGetSectores(ApiContent $content):ApiResult
	{
		$idProductSubGroup = $content->getParameters()->getInt('idProductSubGroup');
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

	/**
	 * Pesquisa por subgrupos dos produtos através de um filtro e um valor de busca.
	 * @ApiAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException método de pesquisa desconhecido.
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
	 * @return ApiResult aquisição do resultado com a lista dos subgrupos dos produtos encontrados.
	 */

	private function actionSearchByName(string $name): ApiResult
	{
		$productSubGroupControl = new ProductSubGroupControl(System::getWebConnection());
		$productCategories = $productSubGroupControl->search($name);

		$result = new ApiResultCategories();
		$result->setProductCategories($productCategories);

		return $result;
	}
}
