<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultProductTypeSettings;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\entities\ProductType;
use tercom\api\exceptions\FilterException;

/**
 * Serviço de Tipos de Produto
 *
 * Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de tipo de produto.
 * Como serviço, oferece as possibilidades de acicionar tipos de produto, atualizar tipos de produto,
 * remover tipos de produto, obter dados dos tipos de produto, e buscar por nome de tipos de produto.
 *
 * @see DefaultSiteService
 * @see ApiResultObject
 * @see ApiResultObject
 * @see ApiResultObjectSettings
 *
 * @author Andrew
 */

class ProductTypeService extends DefaultSiteService
{
	/**
	 * Ação para obter as configurações de formulários para tipos de produto.
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultProductTypeSettings aquisição do resultado das configurações.
	 */

	public function actionSettings(ApiContent $content): ApiResultProductTypeSettings
	{
		return new ApiResultProductTypeSettings();
	}

	/**
	 * Ação para adicionar um novo tipo de produto a partir de dados em POST.
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados do tipo de produto adicionado.
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$productType = new ProductType();
		$productType->setName($post->getString('name'));
		$this->getProductTypeControl()->add($productType);

		$result = new ApiResultObject();
		$result->setResult($productType, 'tipo de produto "%s" adicionado com êxito', $productType->getName());

		return $result;
	}

	/**
	 * Ação para atualizar os dados de um tipo de produto a partir de dados em POST.
	 * @ApiAnnotation({"method":"post","params":["idProductType"]})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados do tipo de produto atualizados.
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProductType = $content->getParameters()->getInt('idProductType');
		$productType = $this->getProductTypeControl()->get($idProductType);

		if ($post->isSetted('name')) $productType->setName($post->getString('name'));

		$productType = $this->getProductTypeControl()->set($productType);

		$result = new ApiResultObject();
		$result->setResult($productType, 'tipo de produto "%s" atualizado com êxito', $productType->getName());

		return $result;
	}

	/**
	 * Ação para remover os dados de um tipo de produto a partir dos parâmetros.
	 * @ApiAnnotation({"params":["idProductType"]})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados da embalagem de produto removida.
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$idProductType = $content->getParameters()->getInt('idProductType');
		$productType = $this->getProductTypeControl()->get($idProductType);
		$this->getProductTypeControl()->remove($productType);

		$result = new ApiResultObject();
		$result->setResult($productType, 'tipo de produto "%s" excluído com êxito', $productType->getName());

		return $result;
	}

	/**
	 * Ação para obter os dados de um tipo de produto a partir dos parâmetros.
	 * @ApiAnnotation({"params":["idProductType"]})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados do tipo de produto obtida.
	 */

	public function actionGet(ApiContent $content): ApiResult
	{
		$idProductType = $content->getParameters()->getInt('idProductType');
		$productType = $this->getProductTypeControl()->get($idProductType);

		$result = new ApiResultObject();
		$result->setResult($productType, 'tipo de produto "%s" obtido com êxito', $productType->getName());

		return $result;
	}

	/**
	 * Ação para obter os dados dos tipos de produtos registradas no sistema.
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados de todos os tipos de produto.
	 */
	public function actionGetAll(ApiContent $content): ApiResult
	{
		$productTypes = $this->getProductPackageControl()->getAll();

		$result = new ApiResultObject();
		$result->setResult($productTypes, 'há "%d" tipos de produto registradas no sistema', $productTypes->size());

		return $result;
	}

	/**
	 * Ação para obter os dados dos tipos de produto filtradas conforme parâmetros.
	 * @ApiAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @throws FilterException somente se o filtro informado não existir na ação.
	 * @return ApiResultObject aquisição do resultado contendo os dados dos tipos de produto filtradas.
	 */
	public function actionSearch(ApiContent $content): ApiResultObject
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'name': return $this->serachByName($content);
		}

		throw new FilterException($filter);
	}

	/**
	 * Procedimento interno para ação de pesquisa por embalagens através do nome do tipo.
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados dos tipos de produto.
	 */
	private function serachByName(ApiContent $content): ApiResultObject
	{
		$value = $content->getParameters()->getString('value');
		$productTypes = $this->getProductTypeControl()->searchByName($value);

		$result = new ApiResultObject();
		$result->setResult($productTypes);

		return $result;
	}

	/**
	 * Ação para verificar a disponibilidade de algum tipo de dado para tipo de produto.
	 * @ApiAnnotation({"params":["filter","value","idProductType"]})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @throws FilterException somente se o filtro informado não existir na ação.
	 * @return ApiResultSimpleValidation aquisição do resultado informado a disponibilidade do dado.
	 */
	public function actionAvaiable(ApiContent $content):ApiResult
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'name': return $this->avaiableName($content);
		}

		throw new FilterException($filter);
	}

	/**
	 * Procedimento usado para consultar a disponibilidade de um nome para tipo de produto.
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultSimpleValidation aquisição do resultado informado a disponibilidade.
	 */
	private function avaiableName(ApiContent $content): ApiResult
	{
		$parameters = $content->getParameters();
		$name = $parameters->getString('value');
		$idProductType = $this->parseNullToInt($parameters->getInt('idProductType', false));
		$avaiable = !$this->getProductTypeControl()->hasName($name, $idProductType);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'nome "%s" %s', $name, $this->getMessageAvaiable($avaiable));

		return $result;
	}
}

?>