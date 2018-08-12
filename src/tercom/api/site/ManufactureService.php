<?php

namespace tercom\api\site;

use dProject\Primitive\ArrayDataException;
use dProject\restful\ApiContent;
use dProject\restful\ApiConnection;
use dProject\restful\ApiResult;
use dProject\restful\ApiServiceInterface;
use dProject\restful\exception\ApiException;
use dProject\restful\exception\ApiMissParam;
use tercom\control\ManufactureControl;
use tercom\entities\Manufacture;
use tercom\core\System;

/**
 * <h1>Serviço de Fabricantes</h1>
 *
 * <p>Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de fabricantes.
 * Como serviço, oferece as possibilidades de adicionar fabricante, atualizar fabrincante,
 * excluir fabricante, obter fabricante e procurar por fabricantes.<p>
 *
 * @see ApiServiceInterface
 * @see ApiConnection
 * @author Andrew
 */

class ManufactureService extends ApiServiceInterface
{
	/**
	 * Cria uma nova insterface de um serviço para gerenciamento de fabricantes no sistema.
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
	 * Ação para se obter as configurações de limites de cada atributo referente aos fabricantes.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiManufactureSettings
	 */

	public function actionSettings(ApiContent $content): ApiManufactureSettings
	{
		$settings = new ApiManufactureSettings();

		return $settings;
	}

	/**
	 * Adiciona um novo fabricante sendo necessário informar apenas o nome fantasia.
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado contendo os dados do fabricante adicionado.
	 */

	public function actionAdd(ApiContent $content): ApiResult
	{
		$POST = $content->getPost();
		$manufacture = new Manufacture();

		try {

			$manufacture->setFantasyName($POST->getString('fantasyName'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$manufactureControl = new ManufactureControl(System::getWebConnection());
		$manufactureControl->add($manufacture);

		$result = new ApiManufactureResult();
		$result->setManufacture($manufacture);

		return $result;
	}

	/**
	 * Atualiza os dados do fabricante através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiAnnotation({"method":"post", "params":["idManufacture"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException fabricante não encontrado.
	 * @return ApiResult aquisição do resultado com os dados do fabricante atualizados.
	 */

	public function actionSet(ApiContent $content):ApiResult
	{
		$POST = $content->getPost();

		$idManufacture = $content->getParameters()->getInt('idManufacture');
		$manufactureControl = new ManufactureControl(System::getWebConnection());

		if (($manufacture = $manufactureControl->get($idManufacture)) === null)
			throw new ApiException('fabricante não encontrado');

		try {

			if ($POST->isSetted('fantasyName')) $manufacture->setFantasyName($POST->getString('fantasyName'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$result = new ApiManufactureResult();
		$result->setManufacture($manufacture);

		if ($manufactureControl->set($manufacture))
			$result->setMessage('fabricante atualizado com êxtio');
		else
			$result->setMessage('nenhuma dado de fabricante modificado');

		return $result;
	}

	/**
	 * Exclui os dados de um fornecedor no sistema através do seu código de identificação.
	 * @ApiAnnotation({"params":["idManufacture"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException fabricante não encontrado.
	 * @return ApiResult aquisição do resultado com os dados do fabricante atualizados.
	 */

	public function actionRemove(ApiContent $content): ApiResult
	{
		$idManufacture = $content->getParameters()->getInt('idManufacture');
		$manufactureControl = new ManufactureControl(System::getWebConnection());

		if (($manufacture = $manufactureControl->get($idManufacture)) === null)
			throw new ApiException('fabricante não encontrado');

		$result = new ApiManufactureResult();
		$result->setManufacture($manufacture);

		if ($manufactureControl->remove($manufacture))
			$result->setMessage('fabricante excluído com êxtio');
		else
			$result->setMessage('fabricante já não existe mais');

		return $result;
	}

	/**
	 * Obtém os dados de um fabricante através do seu código de identificação.
	 * @ApiAnnotation({"params":["idManufacture"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException fabricante não encontrado.
	 * @return ApiResult aquisição do resultado com os dados do fabricante obtido.
	 */

	public function actionGet(ApiContent $content): ApiResult
	{
		$idManufacture = $content->getParameters()->getInt('idManufacture');
		$manufactureControl = new ManufactureControl(System::getWebConnection());

		if (($manufacture = $manufactureControl->get($idManufacture)) === null)
			throw new ApiException('fabricante não encontrado');

		$result = new ApiManufactureResult();
		$result->setManufacture($manufacture);

		return $result;
	}

	/**
	 * Pesquisa por fornecedores através de um filtro e um valor de busca.
	 * Os filtros são <i>cnpj</i> (CNPJ) e <i>fantasyName</i> (nome fantasia).
	 * @ApiAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException método de pesquisa desconhecido.
	 * @return ApiResult aquisição do resultado com a lista de fabricantes encontrados.
	 */

	public function actionSearch(ApiContent $content):ApiResult
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'fantasyName': return $this->actionSearchByFantasyName($content);
		}

		throw new ApiException('método de busca desconhecido');
	}

	/**
	 * Procedimento interno usado pela pesquisa de fabricantes através do nome fantasia.
	 * A busca é feita mesmo que o nome fantasia seja informado parcialmente.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição da lista de fabricantes com o nome fantasia informado.
	 */

	private function actionSearchByFantasyName(ApiContent $content): ApiResult
	{
		$fantasyName = $content->getParameters()->getString('value');
		$manufactureControl = new ManufactureControl(System::getWebConnection());

		if (($manufactures = $manufactureControl->listByFantasyName($fantasyName)) === null)
			throw new ApiException('fabricante não encontrado');

		$result = new ApiResultManufactures();
		$result->setManufactures($manufactures);

		return $result;
	}
}

?>