<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\exceptions\FilterException;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultProductUnitSettings;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\entities\ProductUnit;

/**
 * Serviço de Unidade de Produto
 *
 * Este serviço realiza a comunicação do clietne para com o sistema relação aos dados de unidades de produto.
 * Como serviço, oferece as possibilidades de adicionar, atualizar, excluir, obter e procurar por unidades de produto,
 * além de ser possível verificar a disponibilidade de nome e abreviação para unidades de produto.
 *
 * @see DefaultSiteService
 * @see ApiResultObject
 * @see ApiResultProductUnitSettings
 *
 * @author Andrew
 */
class ProductUnitService extends DefaultSiteService
{
	/**
	 * Ação para se obter as configurações de limites de cada atributo referente a unidade de produto.
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultProductUnitSettings aquisição do resultado com as configurações.
	 */
	public function actionSettings(ApiContent $content): ApiResultProductUnitSettings
	{
		return new ApiResultProductUnitSettings();
	}

	/**
	 * Adiciona uma nova unidade de produto sendo necessário informar os seguintes dados:
	 * nome e abreviação sendo ambas obrigatórias e individualmente únicas.
	 * @ApiPermissionAnnotation({"method":"post"})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado contendo os dados da unidade de produto adicionada.
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();

		$productUnit = new ProductUnit();
		$productUnit->setName($post->getString('name'));
		$productUnit->setShortName($post->getString('shortName'));
		$this->getProductUnitControl()->add($productUnit);

		$result = new ApiResultObject();
		$result->setResult($productUnit, 'unidade de produto "%s" adicionada com êxito', $productUnit->getName());

		return $result;
	}

	/**
	 * Atualiza os dados da unidade de produto através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiPermissionAnnotation({"method":"post","params":["idProductUnit"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados da unidade de produto atualizada.
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProductUnit = $content->getParameters()->getInt('idProductUnit');
		$productUnit = $this->getProductUnitControl()->get($idProductUnit);

		if ($post->isSetted('name')) $productUnit->setName($post->getString('name'));
		if ($post->isSetted('shortName')) $productUnit->setShortName($post->getString('shortName'));

		$this->getProductUnitControl()->set($productUnit);

		$result = new ApiResultObject();
		$result->setResult($productUnit, 'unidade de produto "%s" atualizada com êxito', $productUnit->getName());

		return $result;
	}

	/**
	 * Exclui os dados de uma unidade de produto através do seu código de identificação.
	 * @ApiPermissionAnnotation({"params":["idProductUnit"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados da unidade de produto excluída.
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$idProductUnit = $content->getParameters()->getInt('idProductUnit');
		$productUnit = $this->getProductUnitControl()->get($idProductUnit);
		$this->getProductUnitControl()->remove($productUnit);

		$result = new ApiResultObject();
		$result->setResult($productUnit, 'unidade de produto "%s" excluída com êxito', $productUnit->getName());

		return $result;
	}

	/**
	 * Obtém os dados de uma unidade de produto através do seu código de identificação.
	 * @ApiPermissionAnnotation({"params":["idProductUnit"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados da unidade de produto obtido.
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idProductUnit = $content->getParameters()->getInt('idProductUnit');
		$productUnit = $this->getProductUnitControl()->get($idProductUnit);

		$result = new ApiResultObject();
		$result->setResult($productUnit, 'unidade de produto "%s" obtida com êxito', $productUnit->getName());

		return $result;
	}

	/**
	 * Obtém uma lista com todas as unidades de produtos registradas no sistema.
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados das unidades de produto.
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$productUnits = $this->getProductUnitControl()->getAll();

		$result = new ApiResultObject();
		$result->setResult($productUnits, 'há %d unidades de produto no banco de dados', $productUnits->size());

		return $result;
	}

	/**
	 * Obtém os dados as unidades de produtos filtradas a partir do seu nome.
	 * @ApiPermissionAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados das unidades de produto filtradas.
	 */
	public function actionSearch(ApiContent $content): ApiResultObject
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'name': return $this->searchByName($content);
		}

		throw new FilterException($filter);
	}

	/**
	 * Procedimento interno usado para realizar a busca por unidades de produto pelo nome.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados das unidades de produto encontradas.
	 */
	private function searchByName(ApiContent $content): ApiResultObject
	{
		$name = $content->getParameters()->getString('value');
		$productUnits = $this->getProductUnitControl()->searchByName($name);

		$result = new ApiResultObject();
		$result->setResult($productUnits, 'encontrado %d unidades de produto com o nome "%s"', $productUnits->size(), $name);

		return $result;
	}

	/**
	 * Verifica a disponibilidade de algum valor de atributo para unidades de produto.
	 * @ApiPermissionAnnotation({"params":["filter","value","idProductUnit"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultSimpleValidation aquisição do resultado com a validação da disponibilidade do dado.
	 */
	public function actionAvaiable(ApiContent $content): ApiResultSimpleValidation
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'name': return $this->avaiableName($content);
			case 'shortName': return $this->avaiableShortName($content);
		}

		throw new FilterException($filter);
	}

	/**
	 * Procedimento interno para realizar a disponibilidade de um nome para unidade de produto.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultSimpleValidation aquisição do resultado com a validação da disponibilidade.
	 */
	private function avaiableName(ApiContent $content): ApiResultSimpleValidation
	{
		$parameters = $content->getParameters();
		$name = $parameters->getString('value');
		$idProductUnit = $this->parseNullToInt($parameters->getInt('idProductUnit', false));
		$avaiable = !$this->getProductUnitControl()->hasName($name, $idProductUnit);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'nome "%s" %s', $name, $this->getMessageAvaiable($avaiable));

		return $result;
	}

	/**
	 * Procedimento interno para realizar a disponibilidade de uma abreviação para unidade de produto.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultSimpleValidation aquisição do resultado com a validação da disponibilidade.
	 */
	private function avaiableShortName(ApiContent $content): ApiResultSimpleValidation
	{
		$parameters = $content->getParameters();
		$shortName = $parameters->getString('value');
		$idProductUnit = $this->parseNullToInt($parameters->getInt('idProductUnit', false));
		$avaiable = !$this->getProductUnitControl()->hasShortName($shortName, $idProductUnit);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'abreviação "%s" %s', $shortName, $this->getMessageAvaiable($avaiable));

		return $result;
	}
}

