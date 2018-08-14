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
use tercom\api\site\results\ApiResultCategory;
use tercom\api\site\results\ApiResultCategories;
use tercom\control\ProductFamilyControl;
use tercom\control\ProductGroupControl;
use tercom\core\System;
use tercom\entities\ProductFamily;
use tercom\api\site\results\ApiResultCategorySettings;

/**
 * <h1>Serviço de Família de Produtos</h1>
 *
 * <p>Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de família de produtos.
 * Como serviço, oferece as possibilidades de acicionar família, atualizar família, obter família,
 * obter grupos da família, remover família e procurar por família.</p>
 *
 * @see ApiServiceInterface
 * @see ApiConnection
 * @author Andrew
 */

class ProductFamilyService extends ApiServiceInterface
{
	/**
	 * Cria uma nova instância de um serviço para gerenciamento de famílias para produtos no sistema.
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
	 * Ação para se obter as configurações de limites de cada atributo referente aos fornecedores.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado com as configurações dos dados de família de produtos.
	 */

	public function actionSettings(ApiContent $content): ApiResult
	{
		return new ApiResultCategorySettings();
	}

	/**
	 * Adiciona uma nova família de produtos sendo necessário informar somente o nome.
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado contendo os dados da família de produtos adicionada.
	 */

	public function actionAdd(ApiContent $content): ApiResult
	{
		$POST = PostService::getInstance();

		try {

			$productFamily = new ProductFamily();
			$productFamily->setName($POST->getString('name'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productFamilyControl = new ProductFamilyControl(System::getWebConnection());
		$productFamilyControl->add($productFamily);

		$result = new ApiResultCategory();
		$result->setProductCategory($productFamily);

		return $result;
	}

	/**
	 * Atualiza os dados de uma família de produto através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiAnnotation({"method":"post", "params":["idProductFamily"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException família não encontrada.
	 * @return ApiResult aquisição do resultado com os dados da família de produtos atualizada.
	 */

	public function actionSet(ApiContent $content): ApiResult
	{
		$POST = PostService::getInstance();
		$idProductFamily = $content->getParameters()->getInt('idProductFamily');

		$productFamilyControl = new ProductFamilyControl(System::getWebConnection());

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

	/**
	 * Excluí os dados de uma família de produtos através do seu código de identificação.
	 * @ApiAnnotation({"params":["idProductFamily"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException família não encontrada;
	 * @return ApiResult aquisição dos dados da família que foi excluída.
	 */

	public function actionRemove(ApiContent $content): ApiResult
	{
		$idProductFamily = $content->getParameters()->getInt('idProductFamily');

		$productFamilyControl = new ProductFamilyControl(System::getWebConnection());

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

	/**
	 * Obtém os dados de uma família de produtos através do seu código de identificação.
	 * @ApiAnnotation({"params":["idProductFamily"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException fornecedor não encontrado.
	 * @return ApiResult aquisição do resultado com os dados da família de produtos obtido.
	 */

	public function actionGet(ApiContent $content): ApiResult
	{
		$idProductFamily = $content->getParameters()->getInt('idProductFamily');
		$productFamilyControl = new ProductFamilyControl(System::getWebConnection());

		if (($productFamily = $productFamilyControl->get($idProductFamily)) === null)
			throw new ApiException('família não encontrada');

		$result = new ApiResultCategory();
		$result->setProductCategory($productFamily);

		return $result;
	}

	/**
	 * Obtém os dados dos grupos de produtos vinculados a uma família de produtos especificado..
	 * @ApiAnnotation({"params":["idProductFamily"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException fornecedor não encontrado.
	 * @return ApiResult aquisição do resultado com os dados dos grupos de produtos encontrados.
	 */

	public function actionGetGroups(ApiContent $content): ApiResult
	{
		$idProductFamily = $content->getParameters()->getInt('idProductFamily');
		$productFamilyControl = new ProductFamilyControl(System::getWebConnection());

		if (($productFamily = $productFamilyControl->get($idProductFamily)) === null)
			throw new ApiException('família não encontrada');

		$productGroupControl = new ProductGroupControl(System::getWebConnection());
		$productGroups = $productGroupControl->getByFamily($idProductFamily);
		$productFamily->setProductGroups($productGroups);

		$result = new ApiResultCategory();
		$result->setProductCategory($productFamily);

		return $result;
	}

	/**
	 * Pesquisa por famílias de produtos através de um filtro e um valor de busca.
	 * @ApiAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException método de pesquisa desconhecido.
	 * @return ApiResult aquisição do resultado com a lista de famílias de produtos encontrados.
	 */

	public function actionSearch(ApiContent $content): ApiResult
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
	 * Procedimento interno utilizado para buscar por famílias de produtos através do nome.
	 * @param string $name nome parcial ou completo da família de produtos à ser procurada.
	 * @return ApiResult aquisição do resultado com a lista de famílias de produtos encontrados.
	 */

	private function actionSearchByName(string $name): ApiResult
	{
		$productFamilyControl = new ProductFamilyControl(System::getWebConnection());
		$productCategories = $productFamilyControl->search($name);

		$result = new ApiResultCategories();
		$result->setProductCategories($productCategories);

		return $result;
	}
}
