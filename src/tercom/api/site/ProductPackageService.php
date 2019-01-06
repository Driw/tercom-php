<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\exceptions\FilterException;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultProductPackageSettings;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\entities\ProductPackage;

/**
 * Serviço de Embalagem de Produto
 *
 * Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de embalagens de produto.
 * Como serviço, oferece as possibilidades de acicionar embalagens de produto, atualizar embalagens de produto,
 * remover embalagens de produto, obter dados das embalagens de produto, e buscar por nome de embalagens de produto.
 *
 * @see DefaultSiteService
 * @see ApiResultObject
 * @see ApiResultProductPackageSettings
 *
 * @author Andrew
 */

class ProductPackageService extends DefaultSiteService
{
	/**
	 * Ação para obter as configurações de formulários para embalagens de produto.
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultProductPackageSettings aquisição do resultado das configurações.
	 */
	public function actionSettings(ApiContent $content): ApiResultProductPackageSettings
	{
		return new ApiResultProductPackageSettings();
	}

	/**
	 * Ação para adicionar uma nova embalagem de produto a partir de dados em POST.
	 * @ApiPermissionAnnotation({"method":"post"})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados da embalagem de produto adicionada.
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();

		$productPackage = new ProductPackage();
		$productPackage->setName($post->getString('name'));
		$this->getProductPackageControl()->add($productPackage);

		$result = new ApiResultObject();
		$result->setResult($productPackage, 'embalagem de produto "%s" adicionada com êxito', $productPackage->getName());

		return $result;
	}

	/**
	 * Ação para atualizar os dados de uma embalagem de produto a partir de dados em POST.
	 * @ApiPermissionAnnotation({"method":"post","params":["idProductPackage"]})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados da emblagem de produto atualizados.
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProductPackage = $content->getParameters()->getInt('idProductPackage');
		$productPackage = $this->getProductPackageControl()->get($idProductPackage);

		if ($post->isSetted('name')) $productPackage->setName($post->getString('name'));

		$this->getProductPackageControl()->set($productPackage);

		$result = new ApiResultObject();
		$result->setResult($productPackage, 'embalagem de produto "%s" atualizada com êxito', $productPackage->getName());

		return $result;
	}

	/**
	 * Ação para remover os dados de uma embalagem de produto a partir dos parâmetros.
	 * @ApiPermissionAnnotation({"params":["idProductPackage"]})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados da embalagem de produto removida.
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$idProductPackage = $content->getParameters()->getInt('idProductPackage');
		$productPackage = $this->getProductPackageControl()->get($idProductPackage);
		$this->getProductPackageControl()->remove($productPackage);

		$result = new ApiResultObject();
		$result->setResult($productPackage, 'embalagem de produto "%s" excluída com êxito', $productPackage->getName());

		return $result;
	}

	/**
	 * Ação para obter os dados de uma embalagem de produto a partir dos parâmetros.
	 * @ApiPermissionAnnotation({"params":["idProductPackage"]})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados da embalagem de produto obtida.
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idProductPackage = $content->getParameters()->getInt('idProductPackage');
		$productPackage = $this->getProductPackageControl()->get($idProductPackage);

		$result = new ApiResultObject();
		$result->setResult($productPackage, 'embalagem de produto "%s" obtida com êxito', $productPackage->getName());

		return $result;
	}

	/**
	 * Ação para obter os dados das embalagens de produtos registradas no sistema.
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados de todas as embalagens de produto.
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$productPackages = $this->getProductPackageControl()->getAll();

		$result = new ApiResultObject();
		$result->setResult($productPackages, 'há "%d" embalagens de produto registradas no sistema', $productPackages->size());

		return $result;
	}

	/**
	 * Ação para obter os dados das embalagens de produto filtradas conforme parâmetros.
	 * @ApiPermissionAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @throws FilterException somente se o filtro informado não existir na ação.
	 * @return ApiResultObject aquisição do resultado contendo os dados das embalagens de produto filtradas.
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
	 * Procedimento interno para ação de pesquisa por embalagens através do nome da embalagem.
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados das embalagens de produto.
	 */
	private function searchByName(ApiContent $content): ApiResultObject
	{
		$name = $content->getParameters()->getString('value');
		$productPackages = $this->getProductPackageControl()->searchByName($name);

		$result = new ApiResultObject();
		$result->setResult($productPackages, 'encontrado %d embalagens de produto pelo nome "%s"', $productPackages->size(), $name);

		return $result;
	}

	/**
	 * Ação para verificar a disponibilidade de algum tipo de dado para embalagens de produto.
	 * @ApiPermissionAnnotation({"params":["filter","value","idProductPackage"]})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @throws FilterException somente se o filtro informado não existir na ação.
	 * @return ApiResultSimpleValidation aquisição do resultado informado a disponibilidade do dado.
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
	 * Procedimento usado para consultar a disponibilidade de um nome para embalagem de produto.
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultSimpleValidation aquisição do resultado informado a disponibilidade.
	 */
	private function avaiableName(ApiContent $content): ApiResultSimpleValidation
	{
		$parameters = $content->getParameters();
		$name = $parameters->getString('value');
		$idProductPackage = $this->parseNullToInt($parameters->getInt('idProductPackage', false));
		$avaiable = !$this->getProductPackageControl()->hasName($name, $idProductPackage);

		$result = new ApiResultSimpleValidation();
		$result->setOk($avaiable, 'nome "%s" %s', $name, $this->getMessageAvaiable($avaiable));

		return $result;
	}
}

