<?php

namespace tercom\api\site;

use dProject\Primitive\ArrayDataException;
use dProject\Primitive\PostService;
use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use dProject\restful\exception\ApiException;
use dProject\restful\exception\ApiMissParam;
use tercom\api\site\results\ApiResultCategory;
use tercom\api\site\results\ApiResultCategories;
use tercom\control\ProductSectorControl;
use tercom\core\System;
use tercom\entities\ProductSector;
use tercom\api\site\results\ApiResultCategorySettings;

/**
 * <h1>Serviço de Setores dos Produtos</h1>
 *
 * <p>Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de setores dos produtos.
 * Como serviço, oferece as possibilidades de acicionar setor, atualizar setor, obter setor, remover setor e procurar por setor.</p>
 *
 * @see ApiServiceInterface
 * @see ApiConnection
 * @author Andrew
 */

class ProductSectorService extends DefaultSiteService
{
	/**
	 * Ação para se obter as configurações de limites de cada atributo referente aos setores dos produtos.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado com as configurações dos dados de setores dos produtos.
	 */

	public function actionSettings(ApiContent $content): ApiResult
	{
		return new ApiResultCategorySettings();
	}

	/**
	 * Adiciona um novo setor dos produtos sendo necessário informar somente o nome.
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado contendo os dados do setor dos produtos adicionado.
	 */

	public function actionAdd(ApiContent $content): ApiResult
	{
		$POST = PostService::getInstance();

		try {

			$productSector = new ProductSector();
			$productSector->setName($POST->getString('name'));
			$productSector->setProductSubGroupID($POST->getInt('idProductSubGroup'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$productSectorControl = new ProductSectorControl(System::getWebConnection());
		$productSectorControl->add($productSector);

		$result = new ApiResultCategory();
		$result->setProductCategory($productSector);

		return $result;
	}

	/**
	 * Atualiza os dados de um setor dos produtos através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiAnnotation({"method":"post", "params":["idProductSector"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException grupo não encontrada.
	 * @return ApiResult aquisição do resultado com os dados do setor dos produtos atualizado.
	 */

	public function actionSet(ApiContent $content): ApiResult
	{
		$POST = PostService::getInstance();
		$idProductSector = $content->getParameters()->getInt('idProductSector');

		$productSectorControl = new ProductSectorControl(System::getWebConnection());

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

	/**
	 * Excluí os dados de um setor dos produtos através do seu código de identificação.
	 * @ApiAnnotation({"params":["idProductSector"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException subgrupo não encontrada;
	 * @return ApiResult aquisição dos dados do setor que foi excluída.
	 */

	public function actionRemove(ApiContent $content): ApiResult
	{
		$idProductSector = $content->getParameters()->getInt('idProductSector');

		$productSectorControl = new ProductSectorControl(System::getWebConnection());

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

	/**
	 * Obtém os dados de um setor dos produtos através do seu código de identificação.
	 * @ApiAnnotation({"params":["idProductSector"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException subgrupo não encontrado.
	 * @return ApiResult aquisição do resultado com os dados do setor dos produtos obtido.
	 */

	public function actionGet(ApiContent $content): ApiResult
	{
		$idProductSector = $content->getParameters()->getInt('idProductSector');
		$productSectorControl = new ProductSectorControl(System::getWebConnection());

		if (($productSector = $productSectorControl->get($idProductSector)) === null)
			throw new ApiException('setor não encontrado');

		$result = new ApiResultCategory();
		$result->setProductCategory($productSector);

		return $result;
	}

	/**
	 * Pesquisa por setores dos produtos através de um filtro e um valor de busca.
	 * @ApiAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException método de pesquisa desconhecido.
	 * @return ApiResult aquisição do resultado com a lista dos setores dos produtos encontrados.
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
	 * Procedimento interno utilizado para buscar por setores dos produtos através do nome.
	 * @param string $name nome parcial ou completo da família dos produtos à ser procurada.
	 * @return ApiResult aquisição do resultado com a lista das famílias dos produtos encontrados.
	 */

	public function actionSearchByName(string $name): ApiResult
	{
		$productSectorControl = new ProductSectorControl(System::getWebConnection());
		$productCategories = $productSectorControl->search($name);

		$result = new ApiResultCategories();
		$result->setProductCategories($productCategories);

		return $result;
	}
}
