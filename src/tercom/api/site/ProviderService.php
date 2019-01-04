<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultProviderSettings;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\api\site\results\ProvidersPageResult;
use tercom\api\exceptions\FilterException;
use tercom\entities\Provider;

/**
 * Serviço de Fornecedor
 *
 * Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de fornecedores.
 * Como serviço, oferece as possibilidades de acicionar fornecedor, atualizar fornecedor, definir telefones,
 * remover telefones, obter fornecedor, procurar por CNPJ e procurar por nome fantasia.
 *
 * @see DefaultSiteService
 * @see ApiResultProviderSettings
 * @see ApiResultObject
 *
 * @author Andrew
 */
class ProviderService extends DefaultSiteService
{
	/**
	 * Ação para se obter as configurações de limites de cada atributo referente ao fornecedor.
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultProviderSettings aquisição do resultado com as configurações.
	 */
	public function actionSettings(ApiContent $content): ApiResultProviderSettings
	{
		return new ApiResultProviderSettings();
	}

	/**
	 * Adiciona um novo fornecedor sendo necessário informar os seguintes dados:
	 * CNPJ, razão social e nome fantasia; representante e site são opcionais.
	 * Por padrão forencedores serão adicionados como ativos.
	 * @ApiPermissionAnnotation({"method":"post","level":1})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado contendo os dados do fornecedor adicionado.
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$POST = $content->getPost();

		$provider = new Provider();
		$provider->setCnpj($POST->getString('cnpj'));
		$provider->setCompanyName($POST->getString('companyName'));
		$provider->setFantasyName($POST->getString('fantasyName'));
		if ($POST->isSetted('spokesman')) $provider->setSpokesman($POST->getString('spokesman'));
		if ($POST->isSetted('site')) $provider->setSite($POST->getString('site'));
		$provider->setInactive(false);
		$this->getProviderControl()->add($provider);

		$result = new ApiResultObject();
		$result->setResult($provider, 'fornecedor "%s" adicionado com êxito', $provider->getFantasyName());

		return $result;
	}

	/**
	 * Atualiza os dados do fornecedor através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiPermissionAnnotation({"method":"post", "params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados do fornecedor atualizados.
	 */

	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$providerID = $content->getParameters()->getInt('idProvider');
		$provider = $this->getProviderControl()->get($providerID);

		if ($post->isSetted('cnpj')) $provider->setCNPJ($post->getString('cnpj'));
		if ($post->isSetted('companyName')) $provider->setCompanyName($post->getString('companyName'));
		if ($post->isSetted('fantasyName')) $provider->setFantasyName($post->getString('fantasyName'));
		if ($post->isSetted('spokesman')) $provider->setSpokesman($post->getString('spokesman'));
		if ($post->isSetted('site')) $provider->setSite($post->getString('site'));
		if ($post->isSetted('inactive')) $provider->setSite($post->getBoolean('inactive'));

		$this->getProviderControl()->set($provider);

		$result = new ApiResultObject();
		$result->setResult($provider, 'dados do fornecedor "%s" atualizados com êxito', $provider->getFantasyName());

		return $result;
	}

	/**
	 * Obtém os dados de um fornecedor através do seu código de identificação.
	 * @ApiPermissionAnnotation({"params":["idProvider","loadContacts"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados do fornecedor obtido.
	 */

	public function actionGet(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$providerID = $parameters->getInt('idProvider');
		$provider = $this->getProviderControl()->get($providerID);
		$this->getPhoneControl()->loadPhones($provider->getPhones());

		if ($parameters->getBoolean('loadContacts', false) === true)
			$this->getProviderContactControl()->loadProviderContacts($provider);

		$result = new ApiResultObject();
		$result->setResult($provider, 'fornecedor "%s" obtido com êxito', $provider->getFantasyName());

		return $result;
	}

	/**
	 * Obtém uma lista contendo todos os fornecedores registrados no sistema.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com a lista de fornecedores encontrados.
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$providers = $this->getProviderControl()->getAll();

		$result = new ApiResultObject();
		$result->setResult($providers, 'há %d fornecedores registrados', $providers->size());

		return $result;
	}

	/**
	 * Lista todos os forenecedores registrados sendo selecionados por paginas.
	 * A página irá determinar quais fornecedores são necessários no retorno.
	 * @ApiPermissionAnnotation({"params":["page"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com a lista de fornecedores encontrados.
	 */
	public function actionList(ApiContent $content): ApiResultObject
	{
		$page = $content->getParameters()->getInt('page');

		$providers = $this->getProviderControl()->getByPage($page);
		$pageCount = $this->getProviderControl()->getPageCount();

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
	 * @ApiPermissionAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com a lista de fornecedores encontrados.
	 */
	public function actionSearch(ApiContent $content): ApiResultObject
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
	 * @return ApiResultObject aquisição da lista de fornecedores com o CNPJ informado.
	 */
	private function actionSearchByCNPJ(ApiContent $content): ApiResultObject
	{
		$cnpj = $content->getParameters()->getString('value');
		$providers = $this->getProviderControl()->searchByCnpj($cnpj);

		$result = new ApiResultObject();
		$result->setResult($providers, 'encontrado %d fornecedores com CNPJ "%s"', $providers->size(), $cnpj);

		return $result;
	}

	/**
	 * Procedimento interno usado pela pesquisa de fornecedores através do nome fantasia.
	 * A busca é feita mesmo que o nome fantasia seja informado parcialmente.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição da lista de fornecedores com o nome fantasia informado.
	 */
	private function actionSearchByFantasyName(ApiContent $content): ApiResultObject
	{
		$fantasyName = $content->getParameters()->getString('value');
		$providers = $this->getProviderControl()->searchByFantasyName($fantasyName);

		$result = new ApiResultObject();
		$result->setResult($providers, 'encontrado %d fornecedores com nome fantasia "%s"', $providers->size(), $fantasyName);

		return $result;
	}

	/**
	 * Define quais os dados de telefone para contato com o fornecedor.
	 * Opcional definir tanto o telefone comercial quanto o secundário,
	 * porém necessário definir ao menos um dos dois telefones.
	 * @ApiPermissionAnnotation({"method":"post","params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado com os dados de fornecedor com telefone(s) atualizado(s).
	 */
	public function actionSetPhones(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$providerID = $content->getParameters()->getInt('idProvider');
		$provider = $this->getProviderControl()->get($providerID);
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

		$this->getProviderControl()->setPhones($provider);

		$result = new ApiResultObject();
		$result->setResult($provider, 'telefone(s) do fornecedor atualizado(s) com êxito');

		return $result;
	}

	/**
	 * Excluí o telefone comercial de um dos fornecedores através do seu código identificação.
	 * @ApiPermissionAnnotation({"params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição dos dados do fornecedor atualizados com o telefone excluído.
	 */
	public function actionRemoveCommercial(ApiContent $content): ApiResultObject
	{
		$providerID = $content->getParameters()->getInt('idProvider');
		$provider = $this->getProviderControl()->get($providerID);
		$this->getPhoneControl()->loadPhones($provider->getPhones());

		$result = new ApiResultObject();

		if (!$this->getProviderControl()->removeCommercial($provider))
			$result->setResult($provider, 'telefone comercial não definido');
		else
			$result->setResult($provider, 'telefone comercial excluído');

		return $result;
	}

	/**
	 * Excluí o telefone secundário de um dos fornecedores através do seu código identificação.
	 * @ApiPermissionAnnotation({"params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição dos dados do fornecedor atualizados com o telefone excluído.
	 */
	public function actionRemoveOtherphone(ApiContent $content): ApiResultObject
	{
		$providerID = $content->getParameters()->getInt('idProvider');
		$provider = $this->getProviderControl()->get($providerID);
		$this->getPhoneControl()->loadPhones($provider->getPhones());

		$result = new ApiResultObject();

		if (!$this->getProviderControl()->removeOtherphone($provider))
			$result->setResult($provider, 'telefone secundário não definido');
		else
			$result->setResult($provider, 'telefone secundário excluído');

		return $result;
	}

	/**
	 * Verifica a disponibilidade de um dado para fornecedor conforme especificações.
	 * Por obrigatoriedade será necessário informar um filtro e valor.
	 * Opcionalmente pode ser informado o código de identificação do fornecedor,
	 * caso seja informado irá desconsiderar a ideia de disponível se for do mesmo.
	 * @ApiPermissionAnnotation({"params":["filter","value","idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultSimpleValidation resultado de uma validação simples (ok, não ok).
	 */
	public function actionAvaiable(ApiContent $content): ApiResultSimpleValidation
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'cnpj': return $this->avaiableCNPJ($content);
		}

		throw new FilterException($filter);
	}

	/**
	 * Verifica a disponibilidade de um cadastro nacional de pessoa jurídica (CNPJ) no sistema.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultSimpleValidation resultado de uma validação simples (ok, não ok).
	 */
	private function avaiableCNPJ(ApiContent $content): ApiResultSimpleValidation
	{
		$cnpj = $content->getParameters()->getString('value');
		$idProvider = $content->getParameters()->isSetted('idProvider') ? $content->getParameters()->getInt('idProvider') : 0;
		$avaiable = !$this->getProviderControl()->hasCnpj($cnpj, $idProvider);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, $avaiable ? 'CNPJ disponível' : 'CNPJ indisponível');

		return $result;
	}
}

?>