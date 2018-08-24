<?php

namespace tercom\api\site;

use Exception;
use dProject\Primitive\ArrayDataException;
use dProject\restful\ApiContent;
use dProject\restful\ApiConnection;
use dProject\restful\ApiResult;
use dProject\restful\ApiServiceInterface;
use dProject\restful\exception\ApiException;
use dProject\restful\exception\ApiMissParam;
use tercom\control\PhoneControl;
use tercom\control\ProviderControl;
use tercom\control\ProviderContactControl;
use tercom\core\System;
use tercom\entities\Provider;
use tercom\api\site\results\ApiResultProviderSettings;
use tercom\api\site\results\ApiResultProvider;
use tercom\api\site\results\ApiResultProviders;
use tercom\api\site\results\ApiResultProviderPage;
use tercom\api\site\results\ApiResultSimpleValidation;

/**
 * <h1>Serviço de Fornecedor</h1>
 *
 * <p>Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de fornecedores.
 * Como serviço, oferece as possibilidades de acicionar fornecedor, atualizar fornecedor, definir telefones,
 * remover telefones, obter fornecedor, procurar por CNPJ e procurar por nome fantasia.</p>
 *
 * @see ApiServiceInterface
 * @see ApiConnection
 * @author Andrew
 */

class ProviderService extends ApiServiceInterface
{
	/**
	 * @var int código para remover telefone comercial.
	 */
	public const REMOVE_COMMERCIAL_PHONE = 1;
	/**
	 * @var int código para remover telefone secundário.
	 */
	public const REMOVE_OTHER_PHONE = 2;
	/**
	 * @var int código para remover ambos telefones (comercial e secundário).
	 */
	public const REMOVE_ALL_PHONES = 3;

	/**
	 * Cria uma nova instância de um serviço para gerenciamento de fornecedores no sistema.
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
	 * @return ApiResult aquisição do resultado com as configurações dos dados de fornecedores.
	 */

	public function actionSettings(ApiContent $content): ApiResult
	{
		return new ApiResultProviderSettings();
	}

	/**
	 * Adiciona um novo fornecedor sendo necessário informar os seguintes dados:
	 * CNPJ, razão social e nome fantasia; representante e site são opcionais.
	 * Por padrão forencedores serão adicionados como ativos.
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado contendo os dados do fornecedor adicionado.
	 */

	public function actionAdd(ApiContent $content): ApiResult
	{
		$POST = $content->getPost();

		try {

			$provider = new Provider();
			$provider->setCNPJ($POST->getString('cnpj'));
			$provider->setCompanyName($POST->getString('companyName'));
			$provider->setFantasyName($POST->getString('fantasyName'));
			if ($POST->isSetted('spokesman')) $provider->setSpokesman($POST->getString('spokesman'));
			if ($POST->isSetted('site')) $provider->setSite($POST->getString('site'));
			$provider->setInactive(false);

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$providerControl = new ProviderControl(System::getWebConnection());
		$providerControl->add($provider);

		$result = new ApiResultProvider();
		$result->setProvider($provider);

		return $result;
	}

	/**
	 * Atualiza os dados do fornecedor através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiAnnotation({"method":"post", "params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException fornecedor não encontrado.
	 * @return ApiResult aquisição do resultado com os dados do fornecedor atualizados.
	 */

	public function actionSet(ApiContent $content): ApiResult
	{
		$POST = $content->getPost();

		$providerID = $this->parseProviderID($content);
		$providerControl = new ProviderControl(System::getWebConnection());

		if (($provider = $providerControl->get($providerID)) == null)
			throw new ApiException('fornecedor não encontrado');

		$phoneControl = new PhoneControl(System::getWebConnection());
		$phoneControl->loadPhones($provider->getPhones());

		try {

			if ($POST->isSetted('cnpj')) $provider->setCNPJ($POST->getString('cnpj'));
			if ($POST->isSetted('companyName')) $provider->setCompanyName($POST->getString('companyName'));
			if ($POST->isSetted('fantasyName')) $provider->setFantasyName($POST->getString('fantasyName'));
			if ($POST->isSetted('spokesman')) $provider->setSpokesman($POST->getString('spokesman'));
			if ($POST->isSetted('site')) $provider->setSite($POST->getString('site'));
			if ($POST->isSetted('inactive')) $provider->setSite($POST->getBoolean('inactive'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$provider->setInactive(false);
		$providerControl->set($provider);

		$result = new ApiResultProvider();
		$result->setProvider($provider);

		return $result;
	}

	/**
	 * Obtém os dados de um fornecedor através do seu código de identificação.
	 * @ApiAnnotation({"params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException fornecedor não encontrado.
	 * @return ApiResult aquisição do resultado com os dados do fornecedor obtido.
	 */

	public function actionGet(ApiContent $content): ApiResult
	{
		$providerID = $this->parseProviderID($content);
		$providerControl = new ProviderControl(System::getWebConnection());
		$providerContactControl = new ProviderContactControl(System::getWebConnection());

		if (($provider = $providerControl->get($providerID)) == null)
			throw new ApiException('fornecedor não encontrado');

		$phoneControl = new PhoneControl(System::getWebConnection());
		$phoneControl->loadPhones($provider->getPhones());

		$providerContactControl->loadProviderContacts($provider);
		$result = new ApiResultProvider();
		$result->setProvider($provider);

		return $result;
	}

	/**
	 * Pesquisa por fornecedores através de um filtro e um valor de busca.
	 * Os filtros são <i>cnpj</i> (CNPJ) e <i>fantasyName</i> (nome fantasia).
	 * @ApiAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException método de pesquisa desconhecido.
	 * @return ApiResult aquisição do resultado com a lista de fornecedores encontrados.
	 */

	public function actionSearch(ApiContent $content): ApiResult
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'cnpj': return $this->actionSearchByCNPJ($content);
			case 'fantasyName': return $this->actionSearchByFantasyName($content);
		}

		throw new ApiException('método de pesquisa desconhecido');
	}

	/**
	 * Procedimento interno usado pela pesquisa de fornecedores através do CNPJ.
	 * A busca é feita mesmo que o CNPJ seja informado parcialmente.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição da lista de fornecedores com o CNPJ informado.
	 */

	private function actionSearchByCNPJ(ApiContent $content): ApiResult
	{
		$cnpj = $content->getParameters()->getString('value');
		$providerControl = new ProviderControl(System::getWebConnection());
		$providers = $providerControl->listByCNPJ($cnpj);

		$result = new ApiResultProviders();
		$result->setProviders($providers);

		return $result;
	}

	/**
	 * Procedimento interno usado pela pesquisa de fornecedores através do nome fantasia.
	 * A busca é feita mesmo que o nome fantasia seja informado parcialmente.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição da lista de fornecedores com o nome fantasia informado.
	 */

	private function actionSearchByFantasyName(ApiContent $content): ApiResult
	{
		$fantasyName = $content->getParameters()->getString('value');
		$providerControl = new ProviderControl(System::getWebConnection());
		$providers = $providerControl->listByFantasyName($fantasyName);

		$result = new ApiResultProviders();
		$result->setProviders($providers);

		return $result;
	}

	/**
	 * Lista todos os forenecedores registrados sendo selecionados por paginas.
	 * A página irá determinar quais fornecedores são necessários no retorno.
	 * @ApiAnnotation({"params":["page"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException método de pesquisa desconhecido.
	 * @return ApiResult aquisição do resultado com a lista de fornecedores encontrados.
	 */

	public function actionList(ApiContent $content): ApiResult
	{
		if ($content->getParameters()->getString('page') === 'all')
			$page = -1;
		else
			$page = $content->getParameters()->getInt('page');

		$providerControl = new ProviderControl(System::getWebConnection());
		$providers = $providerControl->listByPage($page);
		$pageCount = $providerControl->getPageCount();

		$result = new ApiResultProviderPage();
		$result->setProviders($providers);
		$result->setPageCount($pageCount);

		return $result;
	}

	/**
	 * Define quais os dados de telefone para contato com o fornecedor.
	 * Opcional definir tanto o telefone comercial quanto o secundário,
	 * porém necessário definir ao menos um dos dois telefones.
	 * @ApiAnnotation({"method":"post","params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException fornecedor não encontrado.
	 * @return ApiResult aquisição do resultado com os dados de fornecedor com telefone(s) atualizado(s).
	 */

	public function actionSetPhones(ApiContent $content): ApiResult
	{
		$POST = $content->getPost();

		$providerID = $this->parseProviderID($content);
		$providerControl = new ProviderControl(System::getWebConnection());

		if (($provider = $providerControl->get($providerID)) == null)
			throw new ApiException('fornecedor não encontrado');

		$phoneControl = new PhoneControl(System::getWebConnection());
		$phoneControl->loadPhones($provider->getPhones());

		try {

			if ($POST->isSetted('commercial'))
			{
				$commercialData = $POST->newArrayData('commercial');

				$provider->getCommercial()->setDDD($commercialData->getInt('ddd'));
				$provider->getCommercial()->setNumber($commercialData->getString('number'));
				$provider->getCommercial()->setType($commercialData->getString('type'));
			}

			if ($POST->isSetted('otherphone'))
			{
				$otherphoneData = $POST->newArrayData('otherphone');

				$provider->getOtherPhone()->setDDD($otherphoneData->getInt('ddd'));
				$provider->getOtherPhone()->setNumber($otherphoneData->getString('number'));
				$provider->getOtherPhone()->setType($otherphoneData->getString('type'));
			}

		} catch (Exception $e) {
			return new ApiMissParam($e->getMessage());
		}

		$providerControl->setPhones($provider);

		$result = new ApiResultProvider();
		$result->setProvider($provider);

		return $result;
	}

	/**
	 * Excluí os dados de telefone de um dos fornecedores através do seu código identificação.
	 * Necessário também informar por parâmetro qual o telefonse será excluído, <code>REMOVE_*_PHONE</code>
	 * @ApiAnnotation({"params":["idProvider", "phone"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException fornecedor não encontrado; telefone não definido; telefone inválido.
	 * @return ApiResult aquisição dos dados do fornecedor com o(s) telefone(s) removido(s).
	 */

	public function actionRemovePhone(ApiContent $content): ApiResult
	{
		$providerID = $this->parseProviderID($content);
		$providerControl = new ProviderControl(System::getWebConnection());

		if (($provider = $providerControl->get($providerID)) == null)
			throw new ApiException('fornecedor não encontrado');

		$phoneControl = new PhoneControl(System::getWebConnection());
		$phoneControl->loadPhones($provider->getPhones());

		if (!$content->getParameters()->isSetted('phone'))
			throw new ApiException('telefone não definido');

		$result = new ApiResultProvider();

		switch ($content->getParameters()->getString('phone'))
		{
			case 'commercial':
				if (!$providerControl->removeCommercial($provider))
					$result->setMessage('telefone comercial não definido');
				else
					$result->setMessage('telefone comercial excluído');
				break;

			case 'otherphone':
				if (!$providerControl->removeOtherphone($provider))
					$result->setMessage('telefone secundário não definido');
				else
					$result->setMessage('telefone secundário excluído');
				break;

			default:
				throw new ApiException('telefone inválido');
		}

		$result->setProvider($provider);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["attribute","value","idProvider"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionValidate(ApiContent $content): ApiResult
	{
		$attribute = $content->getParameters()->getString('attribute');

		switch ($attribute)
		{
			case 'cnpj': return $this->validateCNPJ($content);
		}

		throw new ApiException('tipo de validação desconhecida');
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	private function validateCNPJ(ApiContent $content): ApiResult
	{
		$cnpj = $content->getParameters()->getString('value');
		$idProvider = $content->getParameters()->isSetted('idProvider') ? $content->getParameters()->getInt('idProvider') : 0;
		$providerControl = new ProviderControl(System::getWebConnection());
		$result = new ApiResultSimpleValidation();

		try {

			if (!$providerControl->avaiableCNPJ($cnpj, $idProvider))
				$result->setOkMessage(false, 'CNPJ indisponível');
			else
				$result->setOkMessage(true, 'CNPJ disponível');

		} catch (Exception $e) {
			$result->setOkMessage(false, $e->getMessage());
		}

		return $result;
	}

	/**
	 * Procedimento interno que realiza a validação de uma ação do serviço que
	 * seja necessário ser informado o código de identificação do fornecedor.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException fornecedor não identificado; indentificação inválida.
	 * @return int aquisição do código de identificação do fornecedor informado.
	 */

	private function parseProviderID(ApiContent $content): int
	{
		try {
			return $content->getParameters()->getInt('idProvider');
		} catch (ArrayDataException $e) {
			if ($e->getCode() === ArrayDataException::MISS_PARAM)
				throw new ApiException('fornecedor não identificado');
			if ($e->getCode() === ArrayDataException::PARSE_TYPE)
				throw new ApiException('identificação inválida');
			throw new ApiException($e->getMessage());
		}
	}
}

?>