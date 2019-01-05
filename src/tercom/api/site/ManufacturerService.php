<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\exceptions\FilterException;
use tercom\api\site\results\ApiResultManufactureSettings;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\entities\Manufacturer;

/**
 * <h1>Serviço de Fabricantes</h1>
 *
 * <p>Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de fabricantes.
 * Como serviço, oferece as possibilidades de adicionar fabricante, atualizar fabrincante,
 * excluir fabricante, obter fabricante e procurar por fabricantes.<p>
 *
 * @see DefaultSiteService
 * @see ApiResultObject
 * @see ApiResultSimpleValidation
 *
 * @author Andrew
 */

class ManufacturerService extends DefaultSiteService
{
	/**
	 * Ação para se obter as configurações de limites de cada atributo referente aos fabricantes.
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultManufactureSettings
	 */
	public function actionSettings(ApiContent $content): ApiResultManufactureSettings
	{
		$settings = new ApiResultManufactureSettings();

		return $settings;
	}

	/**
	 * Adiciona um novo fabricante sendo necessário informar apenas o nome fantasia.
	 * @ApiPermissionAnnotation({"method":"post"})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado contendo os dados do fabricante adicionado.
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$manufacturer = new Manufacturer();
		$manufacturer->setFantasyName($post->getString('fantasyName'));
		$this->getManufacturerControl()->add($manufacturer);

		$result = new ApiResultObject();
		$result->setResult($manufacturer, 'fabricante "%s" adicionado com êxito', $manufacturer->getFantasyName());

		return $result;
	}

	/**
	 * Atualiza os dados do fabricante através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiPermissionAnnotation({"method":"post", "params":["idManufacture"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados do fabricante atualizados.
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idManufacture = $content->getParameters()->getInt('idManufacture');
		$manufacturer = $this->getManufacturerControl()->get($idManufacture);

		if ($post->isSetted('fantasyName')) $manufacturer->setFantasyName($post->getString('fantasyName'));

		$this->getManufacturerControl()->set($manufacturer);

		$result = new ApiResultObject();
		$result->setResult($manufacturer, 'fabricante "%s" atualizado com êxito', $manufacturer->getFantasyName());

		return $result;
	}

	/**
	 * Exclui os dados de um fornecedor no sistema através do seu código de identificação.
	 * @ApiPermissionAnnotation({"params":["idManufacture"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados do fabricante atualizados.
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$idManufacture = $content->getParameters()->getInt('idManufacture');
		$manufacturer = $this->getManufacturerControl()->get($idManufacture);
		$this->getManufacturerControl()->remove($manufacturer);

		$result = new ApiResultObject();
		$result->setObject($manufacturer, 'fabricate "%s" excluído com êxito', $manufacturer->getFantasyName());

		return $result;
	}

	/**
	 * Obtém os dados de um fabricante através do seu código de identificação.
	 * @ApiPermissionAnnotation({"params":["idManufacture"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados do fabricante obtido.
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idManufacture = $content->getParameters()->getInt('idManufacture');
		$manufacturer = $this->getManufacturerControl()->get($idManufacture);

		$result = new ApiResultObject();
		$result->setObject($manufacturer, 'fabricate "%s" obtido com êxito', $manufacturer->getFantasyName());

		return $result;
	}

	/**
	 * Obtém uma lista contendo todos os fabricantes existentes no sistema.
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados do fabricante obtido.
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$manufacturers = $this->getManufacturerControl()->getAll();

		$result = new ApiResultObject();
		$result->setResult($manufacturers, 'há %d fabricantes no banco de dados', $manufacturers->size());

		return $result;
	}

	/**
	 * Pesquisa por fornecedores através de um filtro e um valor de busca.
	 * Os filtros são <i>cnpj</i> (CNPJ) e <i>fantasyName</i> (nome fantasia).
	 * @ApiPermissionAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com a lista de fabricantes encontrados.
	 */
	public function actionSearch(ApiContent $content): ApiResultObject
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'fantasyName': return $this->searchByFantasyName($content);
		}

		throw new FilterException($filter);
	}

	/**
	 * Procedimento interno usado pela pesquisa de fabricantes através do nome fantasia.
	 * A busca é feita mesmo que o nome fantasia seja informado parcialmente.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição da lista de fabricantes com o nome fantasia informado.
	 */
	private function searchByFantasyName(ApiContent $content): ApiResultObject
	{
		$fantasyName = $content->getParameters()->getString('value');
		$manufacturers = $this->getManufacturerControl()->searchByFantasyName($fantasyName);

		$result = new ApiResultObject();
		$result->setResult($manufacturers, 'há %d fabricantes nome fantasia "%s"', $manufacturers->size(), $fantasyName);

		return $result;
	}

	/**
	 * @ApiPermissionAnnotation({"params":["filter","value","idManufacturer"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultSimpleValidation aquisição do resultado com a lista de fabricantes encontrados.
	 */
	public function actionAvaiable(ApiContent $content): ApiResultSimpleValidation
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'fantasyName': return $this->avaiableFantasyName($content);
		}

		throw new FilterException($filter);
	}

	/**
	 * Procedimento interno usado pela pesquisa de fabricantes através do nome fantasia.
	 * A busca é feita mesmo que o nome fantasia seja informado parcialmente.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultSimpleValidation aquisição da lista de fabricantes com o nome fantasia informado.
	 */
	private function avaiableFantasyName(ApiContent $content): ApiResultSimpleValidation
	{
		$parameters = $content->getParameters();
		$fantasyName = $parameters->getString('value');
		$idManufacturer = $this->parseNullToInt($parameters->getInt('idManufacturer', false));
		$avaiable = !$this->getManufacturerControl()->hasFantasyName($fantasyName, $idManufacturer);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'nome fantasia %s', $avaiable ? 'disponível' : 'indisponível');

		return $result;
	}
}

?>