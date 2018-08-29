<?php

namespace tercom\api\site;

use Exception;
use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use dProject\restful\exception\ApiException;
use dProject\restful\exception\ApiMissParam;
use tercom\control\PhoneControl;
use tercom\control\ProviderContactControl;
use tercom\control\ProviderControl;
use tercom\core\System;
use tercom\entities\Provider;
use tercom\entities\ProviderContact;
use dProject\Primitive\ArrayDataException;
use tercom\api\site\results\ApiResultProviderContactSettings;
use tercom\api\site\results\ApiResultProviderContact;
use tercom\api\site\results\ApiResultProviderContacts;

/**
 * <h1>Serviço de Contatos do Fornecedor</h1>
 *
 * <p>Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de fornecedores.
 * Como serviço, oferece as possibilidades de acicionar fornecedor, atualizar fornecedor, definir telefones,
 * remover telefones, obter fornecedor, procurar por CNPJ e procurar por nome fantasia.</p>
 *
 * @see ApiServiceInterface
 * @see ApiConnection
 * @author Andrew
 */

class ProviderContactService extends DefaultSiteService
{
	/**
	 * Ação para se obter as configurações de limites de cada atributo referente aos fornecedores.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado com as configurações dos dados de fornecedores.
	 */

	public function actionSettings(ApiContent $content): ApiResult
	{
		return new ApiResultProviderContactSettings();
	}

	/**
	 * Adiciona um novo contato do fornecedor sendo necessário informar os seguintes dados:
	 * nome; endereço de e-mail (opicional) e cargo (opcional).
	 * @ApiAnnotation({"method":"post","params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado contendo os dados do contato do fornecedor adicionado.
	 */

	public function actionAdd(ApiContent $content): ApiResult
	{
		$POST = $content->getPost();
		$provider = $this->parseProvider($content);

		try {

			$providerContact = new ProviderContact();
			$providerContact->setName($POST->getString('name'));

			if ($POST->isSetted('email')) $providerContact->setEmail($POST->getString('email'));
			if ($POST->isSetted('position')) $providerContact->setPosition($POST->getString('position'));

		} catch (Exception $e) {
			return new ApiMissParam($e->getMessage());
		}

		$providerContactControl = new ProviderContactControl(System::getWebConnection());
		$providerContactControl->addProviderContact($provider, $providerContact);

		$result = new ApiResultProviderContact();
		$result->setProviderContact($providerContact);

		return $result;
	}

	/**
	 * Atualiza os dados do contato do fornecedor através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiAnnotation({"method":"post", "params":["idContactProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException contato do fornecedor não encontrado.
	 * @return ApiResult aquisição do resultado com os dados do contato do fornecedor atualizados.
	 */

	public function actionSet(ApiContent $content):ApiResult
	{
		$POST = $content->getPost();

		$providerID = $content->getParameters()->getInt('idContactProvider');
		$providerContactID = $POST->getInt('id');
		$providerContactControl = new ProviderContactControl(System::getWebConnection());

		if (($providerContact = $providerContactControl->getProvideContact($providerID, $providerContactID)) === null)
			throw new ApiException();

		$phoneControl = new PhoneControl(System::getWebConnection());
		$phoneControl->loadPhones($providerContact->getPhones());

		try {

			if ($POST->isSetted('name')) $providerContact->setName($POST->getString('name'));
			if ($POST->isSetted('email')) $providerContact->setEmail($POST->getString('email'));
			if ($POST->isSetted('position')) $providerContact->setPosition($POST->getString('position'));

		} catch (Exception $e) {
			return new ApiMissParam($e->getMessage());
		}

		$providerContactControl->setProviderContact($providerContact);

		$result = new ApiResultProviderContact();
		$result->setProviderContact($providerContact);

		return $result;
	}

	/**
	 * Define quais os dados de telefone do contato do fornecedor.
	 * Opcional definir tanto o telefone comercial quanto o secundário,
	 * porém necessário definir ao menos um dos dois telefones.
	 * @ApiAnnotation({"method":"post","params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException contato do fornecedor não encontrado.
	 * @return ApiResult aquisição do resultado com os dados de contato do fornecedor com telefone(s) atualizado(s).
	 */

	public function actionSetPhones(ApiContent $content):ApiResult
	{
		$POST = $content->getPost();
		$provider = $this->parseProvider($content);
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl(System::getWebConnection());

		if (($providerContact = $providerContactControl->getProvideContact($provider->getID(), $providerContactID)) === null)
			throw new ApiException('contato de fornecedor não encontrado');

		$phoneControl = new PhoneControl(System::getWebConnection());
		$phoneControl->loadPhones($providerContact->getPhones());

		try {

			if ($POST->isSetted('commercial'))
			{
				$cellphoneData = $POST->newArrayData('commercial');

				if ($cellphoneData->isSetted('ddd')) $providerContact->getCommercial()->setDDD($cellphoneData->getInt('ddd'));
				if ($cellphoneData->isSetted('number')) $providerContact->getCommercial()->setNumber($cellphoneData->getString('number'));
				if ($cellphoneData->isSetted('type')) $providerContact->getCommercial()->setType($cellphoneData->getString('type'));
			}

			if ($POST->isSetted('otherphone'))
			{
				$otherphoneData = $POST->newArrayData('otherphone');

				if ($otherphoneData->isSetted('ddd')) $providerContact->getOtherPhone()->setDDD($otherphoneData->getInt('ddd'));
				if ($otherphoneData->isSetted('number')) $providerContact->getOtherPhone()->setNumber($otherphoneData->getString('number'));
				if ($otherphoneData->isSetted('type')) $providerContact->getOtherPhone()->setType($otherphoneData->getString('type'));
			}

		} catch (Exception $e) {
			return new ApiMissParam($e->getMessage());
		}

		$providerContactControl->setPhones($providerContact);

		$result = new ApiResultProviderContact();
		$result->setProviderContact($providerContact);

		return $result;
	}

	/**
	 * Exclui os dados do telefone comercial vinculado ao contato do fornecedor se houver.
	 * @ApiAnnotation({"params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException contato do fornecedor não encontrado; telefone não definido; telefone inválido.
	 * @return ApiResult aquisição dos dados do contato do fornecedor com o(s) telefone(s) removido(s).
	 */

	public function actionRemoveCommercial(ApiContent $content):ApiResult
	{
		$POST = $content->getPost();

		$providerID = $content->getParameters()->getInt('idProvider');
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl(System::getWebConnection());

		if (($providerContact = $providerContactControl->getProvideContact($providerID, $providerContactID)) === null)
			throw new ApiException();

		$result = new ApiResultProviderContact();
		$result->setProviderContact($providerContact);

		if ($providerContactControl->removeCommercial($providerContact))
			$result->setMessage('telefone comercial excluído');
		else
			$result->setMessage('telefone comercial não definido');

		$phoneControl = new PhoneControl(System::getWebConnection());
		$phoneControl->loadPhones($providerContact->getPhones());

		return $result;
	}

	/**
	 * Exclui os dados do telefone secundário vinculado ao contato do fornecedor se houver.
	 * @ApiAnnotation({"params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException contato do fornecedor não encontrado; telefone não definido; telefone inválido.
	 * @return ApiResult aquisição dos dados do contato do fornecedor com o(s) telefone(s) removido(s).
	 */

	public function actionRemoveOtherphone(ApiContent $content):ApiResult
	{
		$POST = $content->getPost();

		$providerID = $content->getParameters()->getInt('idProvider');
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl(System::getWebConnection());

		if (($providerContact = $providerContactControl->getProvideContact($providerID, $providerContactID)) === null)
			throw new ApiException();

		$result = new ApiResultProviderContact();
		$result->setProviderContact($providerContact);

		if ($providerContactControl->removeOtherphone($providerContact))
			$result->setMessage('telefone secundário excluído');
		else
			$result->setMessage('telefone secundário não definido');

		$phoneControl = new PhoneControl(System::getWebConnection());
		$phoneControl->loadPhones($providerContact->getPhones());

		return $result;
	}

	/**
	 * Exclui os dados do contato de fornecedor e seus telefones se assim for encontrado.
	 * @ApiAnnotation({"method":"post","params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException contato do fornecedor não vinculado ao fornecedor.
	 * @return ApiResult aquisição dos dados do contato do fornecedor que foi excluído.
	 */

	public function actionRemoveContact(ApiContent $content):ApiResult
	{
		$POST = $content->getPost();
		$provider = $this->parseProvider($content);
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl(System::getWebConnection());
		$providerContactControl->loadProviderContacts($provider);

		if (($providerContact = $provider->getContacs()->getContactByID($providerContactID)) === null)
			throw new ApiException('contato de fornecedor não vinculado ao fornecedor');

		if (($providerContact = $providerContactControl->getProvideContact($provider->getID(), $providerContactID)) === null)
			throw new ApiException();

		$phoneControl = new PhoneControl(System::getWebConnection());
		$phoneControl->loadPhones($providerContact->getPhones());

		if ($providerContactControl->removeProviderContact($providerContact));
			$provider->getContacs()->removeElement($providerContact);

		$result = new ApiResultProviderContacts();
		$result->setProviderContacts($provider->getContacs());

		return $result;
	}

	/**
	 * Obtém os dados de um contato do fornecedor através do seu código de identificação.
	 * @ApiAnnotation({"method":"post","params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException fornecedor não encontrado.
	 * @return ApiResult aquisição do resultado com os dados do contato do fornecedor obtido.
	 */

	public function actionGetContact(ApiContent $content):ApiResult
	{
		$POST = $content->getPost();

		$providerID = $content->getParameters()->getInt('idProvider');
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl(System::getWebConnection());

		if (($providerContact = $providerContactControl->getProvideContact($providerID, $providerContactID)) === null)
			throw new ApiException('contato de fornecedor não encontrado');

		$phoneControl = new PhoneControl(System::getWebConnection());
		$phoneControl->loadPhones($providerContact->getPhones());

		$result = new ApiResultProviderContact();
		$result->setProviderContact($providerContact);

		return $result;
	}

	/**
	 * Obtém uma lista de contatos do fornecedor através do código de identificação do fornecedor.
	 * @ApiAnnotation({"params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResult aquisição do resultado com a lista de contatos do fornecedor obtida.
	 */

	public function actionGetContacts(ApiContent $content):ApiResult
	{
		$provider = $this->parseProvider($content);

		$providerContactControl = new ProviderContactControl(System::getWebConnection());
		$providerContacts = $providerContactControl->getProvideContacts($provider->getID());

		$phoneControl = new PhoneControl(System::getWebConnection());

		foreach ($providerContacts as $providerContact)
			$phoneControl->loadPhones($providerContact->getPhones());

		$result = new ApiResultProviderContacts();
		$result->setProviderContacts($providerContacts);

		return $result;
	}

	/**
	 * Procedimento interno que realiza a validação de uma ação do serviço que
	 * seja necessário ser informado o código de identificação do fornecedor.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @throws ApiException fornecedor não identificado; fornecedor não encontrado,
	 * fornecedor com identificação inválida.
	 * @return int aquisição do código de identificação do fornecedor informado.
	 */

	private function parseProvider(ApiContent $content):Provider
	{
		try {

			$parameters = $content->getParameters();

			if (!$parameters->isSetted('idProvider'))
				throw new ApiException('fornecedor não identificado');

			$providerID = $parameters->getInt('idProvider');
			$providerControl = new ProviderControl(System::getWebConnection());

			if (($provider = $providerControl->get($providerID)) === null)
				throw new ApiException('fornecedor não encontrado');

			return $provider;

		} catch (ArrayDataException $e) {
			if ($e->getCode() === ArrayDataException::PARSE_TYPE)
				throw new ApiException('fornecedor com identificação inválida');
		}
	}
}

?>