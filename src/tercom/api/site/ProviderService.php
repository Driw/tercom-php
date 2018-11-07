<?php

namespace tercom\api\site;

use Exception;
use dProject\Primitive\ArrayDataException;
use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultProviderSettings;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\api\site\results\ProvidersPageResult;
use tercom\api\exceptions\ProviderException;
use tercom\api\exceptions\ParameterException;
use tercom\api\exceptions\FilterException;
use tercom\control\ControlValidationException;
use tercom\entities\Provider;

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

class ProviderService extends DefaultSiteService
{
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
	 * @throws ParameterException ocorre apenas se houver parâmetros faltando.
	 * @return ApiResultObject aquisição do resultado contendo os dados do fornecedor adicionado.
	 */

	public function actionAdd(ApiContent $content): ApiResultObject
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
			throw new ParameterException($e);
		}

		$providerControl = $this->newProviderControl();
		$providerControl->add($provider);

		$result = new ApiResultObject();
		$result->setResult($provider, 'fornecedor adicionado com êxito');

		return $result;
	}

	/**
	 * Atualiza os dados do fornecedor através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiAnnotation({"method":"post", "params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ParameterException código do fornecedor não informado ou inválido.
	 * @throws ProviderException fornecedor não encontrado.
	 * @return ApiResultObject aquisição do resultado com os dados do fornecedor atualizados.
	 */

	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();

		try {

			$providerID = $content->getParameters()->getInt('idProvider');
			$providerControl = $this->newProviderControl();

			if (($provider = $providerControl->get($providerID)) == null)
				throw ProviderException::newNotFound();

			$this->getPhoneControl()->loadPhones($provider->getPhones());

			if ($post->isSetted('cnpj')) $provider->setCNPJ($post->getString('cnpj'));
			if ($post->isSetted('companyName')) $provider->setCompanyName($post->getString('companyName'));
			if ($post->isSetted('fantasyName')) $provider->setFantasyName($post->getString('fantasyName'));
			if ($post->isSetted('spokesman')) $provider->setSpokesman($post->getString('spokesman'));
			if ($post->isSetted('site')) $provider->setSite($post->getString('site'));
			if ($post->isSetted('inactive')) $provider->setSite($post->getBoolean('inactive'));

		} catch (ArrayDataException $e) {
			throw new ParameterException($e);
		}

		$result = new ApiResultObject();

		if ($providerControl->set($provider))
			$result->setResult($provider, 'dados do fornecedor atualizado com êxito');
		else
			$result->setResult($provider, 'nnehum dado alterado no fornecedor');

		return $result;
	}

	/**
	 * Obtém os dados de um fornecedor através do seu código de identificação.
	 * @ApiAnnotation({"params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ParameterException código do fornecedor não informado ou inválido.
	 * @throws ProviderException fornecedor não encontrado.
	 * @return ApiResultObject aquisição do resultado com os dados do fornecedor obtido.
	 */

	public function actionGet(ApiContent $content): ApiResultObject
	{
		try {

			$providerID = $content->getParameters()->getInt('idProvider');
			$providerControl = $this->newProviderControl();

		} catch (ArrayDataException $e) {
			throw new ParameterException($e);
		}

		if (($provider = $providerControl->get($providerID)) == null)
			throw ProviderException::newNotFound();

		$this->getPhoneControl()->loadPhones($provider->getPhones());

		$providerContactControl = $this->newProviderContactControl();
		$providerContactControl->loadProviderContacts($provider);

		$result = new ApiResultObject();
		$result->setResult($provider, 'fornecedor obtido com êxito');

		return $result;
	}

	/**
	 * Obtém uma lista contendo todos os fornecedores registrados no sistema.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado com a lista de fornecedores encontrados.
	 */

	public function actionGetAll(ApiContent $content): ApiResult
	{
		$providerControl = $this->newProviderControl();
		$providers = $providerControl->getAll();

		$result = new ApiResultObject();
		$result->setResult($providers, 'carregado %d fornecedores', $providers->size());

		return $result;
	}

	/**
	 * Lista todos os forenecedores registrados sendo selecionados por paginas.
	 * A página irá determinar quais fornecedores são necessários no retorno.
	 * @ApiAnnotation({"params":["page"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado com a lista de fornecedores encontrados.
	 */

	public function actionList(ApiContent $content): ApiResult
	{
		$page = $content->getParameters()->getInt('page');

		$providerControl = $this->newProviderControl();
		$providers = $providerControl->getByPage($page);
		$pageCount = $providerControl->getPageCount();

		$providersPage = new ProvidersPageResult();
		$providersPage->setProviders($providers);
		$providersPage->setPageCount($pageCount);

		$result = new ApiResultObject();
		$result->setResult($providersPage, 'carregado %d fornecedores e encontrado %d páginas', $providers->size(), $pageCount);

		return $result;
	}

	/**
	 * Pesquisa por fornecedores através de um filtro e um valor de busca.
	 * Os filtros são <i>cnpj</i> (CNPJ) e <i>fantasyName</i> (nome fantasia).
	 * @ApiAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ProviderException filtro de pesquisa desconhecido.
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

		throw new FilterException($filter);
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
		$providerControl = $this->newProviderControl();
		$providers = $providerControl->filterByCNPJ($cnpj);

		$result = new ApiResultObject();
		$result->setResult($providers, 'encontrado %d fornecedores', $providers->size());

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
		$providerControl = $this->newProviderControl();
		$providers = $providerControl->filterByFantasyName($fantasyName);

		$result = new ApiResultObject();
		$result->setResult($providers, 'encontrado %d fornecedores', $providers->size());

		return $result;
	}

	/**
	 * Define quais os dados de telefone para contato com o fornecedor.
	 * Opcional definir tanto o telefone comercial quanto o secundário,
	 * porém necessário definir ao menos um dos dois telefones.
	 * @ApiAnnotation({"method":"post","params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ProviderException fornecedor não encontrado.
	 * @return ApiResult aquisição do resultado com os dados de fornecedor com telefone(s) atualizado(s).
	 */

	public function actionSetPhones(ApiContent $content): ApiResult
	{
		try {

			$post = $content->getPost();
			$providerID = $content->getParameters()->getInt('idProvider');
			$providerControl = $this->newProviderControl();

			if (($provider = $providerControl->get($providerID)) == null)
				throw ProviderException::newNotFound();

				$this->getPhoneControl()->loadPhones($provider->getPhones());

			if ($post->isSetted('commercial'))
			{
				$commercialData = $post->newArrayData('commercial');

				$provider->getCommercial()->setDDD($commercialData->getInt('ddd'));
				$provider->getCommercial()->setNumber($commercialData->getString('number'));
				$provider->getCommercial()->setType($commercialData->getString('type'));
			}

			if ($post->isSetted('otherphone'))
			{
				$otherphoneData = $post->newArrayData('otherphone');

				$provider->getOtherPhone()->setDDD($otherphoneData->getInt('ddd'));
				$provider->getOtherPhone()->setNumber($otherphoneData->getString('number'));
				$provider->getOtherPhone()->setType($otherphoneData->getString('type'));
			}

		} catch (Exception $e) {
			throw new ParameterException($e);
		}

		$result = new ApiResultObject();

		if ($providerControl->setPhones($provider))
			$result->setResult($provider, 'telefone(s) do fornecedor atualizado(s) com êxito');
		else
			$result->setResult($provider, 'nenhum dado alterado no(s) telefone(s) do fornecedor');

		return $result;
	}

	/**
	 * Excluí o telefone comercial de um dos fornecedores através do seu código identificação.
	 * @ApiAnnotation({"params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ParameterException ocorre apenas se houver parâmetros faltando.
	 * @return ApiResult aquisição dos dados do fornecedor atualizados com o telefone excluído.
	 */

	public function actionRemoveCommercial(ApiContent $content): ApiResultObject
	{
		try {

			$providerID = $this->parseProviderID($content);
			$providerControl = $this->newProviderControl();

			if (($provider = $providerControl->get($providerID)) == null)
				throw ProviderException::newNotFound();

				$this->getPhoneControl()->loadPhones($provider->getPhones());

		} catch (ArrayDataException $e) {
			throw new ParameterException($e);
		}

		$result = new ApiResultObject();

		if (!$providerControl->removeCommercial($provider))
			$result->setResult($provider, 'telefone comercial não definido');
		else
			$result->setResult($provider, 'telefone comercial excluído');

		return $result;
	}

	/**
	 * Excluí o telefone secundário de um dos fornecedores através do seu código identificação.
	 * @ApiAnnotation({"params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ParameterException ocorre apenas se houver parâmetros faltando.
	 * @return ApiResult aquisição dos dados do fornecedor atualizados com o telefone excluído.
	 */

	public function actionRemoveOtherphone(ApiContent $content): ApiResultObject
	{
		try {

			$providerID = $this->parseProviderID($content);
			$providerControl = $this->newProviderControl();

			if (($provider = $providerControl->get($providerID)) == null)
				throw ProviderException::newNotFound();

			$this->getPhoneControl()->loadPhones($provider->getPhones());

		} catch (ArrayDataException $e) {
			throw new ParameterException($e);
		}

		$result = new ApiResultObject();

		if (!$providerControl->removeOtherphone($provider))
			$result->setResult($provider, 'telefone secundário não definido');
		else
			$result->setResult($provider, 'telefone secundário excluído');

		$result->setProvider($provider);

		return $result;
	}

	/**
	 * Verifica a disponibilidade de um dado para fornecedor conforme especificações.
	 * Por obrigatoriedade será necessário informar um filtro e valor.
	 * Opcionalmente pode ser informado o código de identificação do fornecedor,
	 * caso seja informado irá desconsiderar a ideia de disponível se for do mesmo.
	 * @ApiAnnotation({"params":["filter","value","idProvider"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	public function actionAvaiable(ApiContent $content): ApiResult
	{
		try {

			$filter = $content->getParameters()->getString('filter');

			switch ($filter)
			{
				case 'cnpj': return $this->avaiableCNPJ($content);
			}

		} catch (ArrayDataException $e) {
			throw new ParameterException($e);
		}

		throw new FilterException($filter);
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResult
	 */

	private function avaiableCNPJ(ApiContent $content): ApiResult
	{
		$cnpj = $content->getParameters()->getString('value');
		$idProvider = $content->getParameters()->isSetted('idProvider') ? $content->getParameters()->getInt('idProvider') : 0;
		$providerControl = $this->newProviderControl();
		$result = new ApiResultSimpleValidation();

		try {

			if (!$providerControl->hasAvaiableCNPJ($cnpj, $idProvider))
				$result->setOkMessage(false, 'CNPJ indisponível');
			else
				$result->setOkMessage(true, 'CNPJ disponível');

		} catch (ControlValidationException $e) {
			$result->setOkMessage(false, $e->getMessage());
		}

		return $result;
	}
}

?>