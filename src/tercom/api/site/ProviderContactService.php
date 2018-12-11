<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultProviderContactSettings;
use tercom\api\site\results\ApiResultObject;
use tercom\entities\ProviderContact;

/**
 * Serviço de Contatos do Fornecedor
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

class ProviderContactService extends DefaultSiteService
{
	/**
	 * Ação para se obter as configurações de limites de cada atributo referente aos fornecedores.
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultProviderContactSettings aquisição do resultado com as configurações dos dados de fornecedores.
	 */
	public function actionSettings(ApiContent $content): ApiResultProviderContactSettings
	{
		return new ApiResultProviderContactSettings();
	}

	/**
	 * Adiciona um novo contato do fornecedor sendo necessário informar os seguintes dados:
	 * nome; endereço de e-mail (opicional) e cargo (opcional).
	 * @ApiAnnotation({"method":"post","params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado contendo os dados do contato do fornecedor adicionado.
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProvider = $content->getParameters()->getInt('idProvider');
		$provider = $this->getProviderControl()->get($idProvider);

		$providerContact = new ProviderContact();
		$providerContact->setName($post->getString('name'));

		if ($post->isSetted('email')) $providerContact->setEmail($post->getString('email'));
		if ($post->isSetted('position')) $providerContact->setPosition($post->getString('position'));

		$this->getProviderContactControl()->add($provider, $providerContact);

		$result = new ApiResultObject();
		$result->setResult($providerContact, 'contato de fornecedor "%s" adicionado com êxito', $providerContact->getName());

		return $result;
	}

	/**
	 * Atualiza os dados do contato do fornecedor através do seu código de identificação.
	 * Nenhum dado é obrigatório ser atualizado, porém se informado será considerado.
	 * @ApiAnnotation({"method":"post", "params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados do contato do fornecedor atualizados.
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProvider = $content->getParameters()->getInt('idProvider');
		$idProviderContact = $post->getInt('id'); // FIXME deveria ser passado como parâmetro
		$providerContact = $this->getProviderContactControl()->get($idProvider, $idProviderContact);

		if ($post->isSetted('name')) $providerContact->setName($post->getString('name'));
		if ($post->isSetted('email')) $providerContact->setEmail($post->getString('email'));
		if ($post->isSetted('position')) $providerContact->setPosition($post->getString('position'));

		$this->getProviderContactControl()->set($providerContact);

		$result = new ApiResultObject();
		$result->setResult($providerContact, 'dados do contato de fornecedor "%s" atualizado', $providerContact->getName());

		return $result;
	}

	/**
	 * Define quais os dados de telefone do contato do fornecedor.
	 * Opcional definir tanto o telefone comercial quanto o secundário,
	 * porém necessário definir ao menos um dos dois telefones.
	 * @ApiAnnotation({"method":"post","params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados de contato do fornecedor com telefone(s) atualizado(s).
	 */
	public function actionSetPhones(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProvider = $content->getParameters()->getInt('idProvider');
		$idProviderContact = $post->getInt('id'); // FIXME deveria ser passado como parâmetro
		$providerContact = $this->getProviderContactControl()->get($idProvider, $idProviderContact);
		$this->getPhoneControl()->loadPhones($providerContact->getPhones());

		if ($post->isSetted('commercial'))
		{
			$cellphoneData = $post->newArrayData('commercial');

			if ($cellphoneData->isSetted('ddd')) $providerContact->getCommercial()->setDDD($cellphoneData->getInt('ddd'));
			if ($cellphoneData->isSetted('number')) $providerContact->getCommercial()->setNumber($cellphoneData->getString('number'));
			if ($cellphoneData->isSetted('type')) $providerContact->getCommercial()->setType($cellphoneData->getString('type'));
		}

		if ($post->isSetted('otherphone'))
		{
			$otherphoneData = $post->newArrayData('otherphone');

			if ($otherphoneData->isSetted('ddd')) $providerContact->getOtherPhone()->setDDD($otherphoneData->getInt('ddd'));
			if ($otherphoneData->isSetted('number')) $providerContact->getOtherPhone()->setNumber($otherphoneData->getString('number'));
			if ($otherphoneData->isSetted('type')) $providerContact->getOtherPhone()->setType($otherphoneData->getString('type'));
		}

		$this->getProviderContactControl()->setPhones($providerContact);

		$result = new ApiResultObject();
		$result->setResult($providerContact, 'telefones do contato de fornecedor "%s" atualizados', $providerContact->getName());

		return $result;
	}

	/**
	 * Exclui os dados do telefone comercial vinculado ao contato do fornecedor se houver.
	 * @ApiAnnotation({"params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição dos dados do contato do fornecedor com o telefone secundário excluído.
	 */
	public function actionRemoveCommercial(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProvider = $content->getParameters()->getInt('idProvider');
		$idProviderContact = $post->getInt('id');
		$providerContact = $this->getProviderContactControl()->get($idProvider, $idProviderContact);
		$removed = $this->getProviderContactControl()->removeCommercial($providerContact);

		$result = new ApiResultObject();
		$result->setResult($providerContact, $removed ? 'telefone comercial excluído' : 'telefone comercial não definido');

		return $result;
	}

	/**
	 * Exclui os dados do telefone secundário vinculado ao contato do fornecedor se houver.
	 * @ApiAnnotation({"params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição dos dados do contato do fornecedor com o telefone comercial excluído.
	 */
	public function actionRemoveOtherphone(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProvider = $content->getParameters()->getInt('idProvider');
		$idProviderContact = $post->getInt('id');
		$providerContact = $this->getProviderContactControl()->get($idProvider, $idProviderContact);
		$removed = $this->getProviderContactControl()->removeOtherphone($providerContact);

		$result = new ApiResultObject();
		$result->setResult($providerContact, $removed ? 'telefone secundário excluído' : 'telefone secundário não definido');

		return $result;
	}

	/**
	 * Exclui os dados do contato de fornecedor e seus telefones se assim for encontrado.
	 * @ApiAnnotation({"method":"post","params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição dos dados do contato do fornecedor que foi excluído.
	 */
	public function actionRemoveContact(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProvider = $content->getParameters()->getInt('idProvider');
		$idProviderContact = $post->getInt('id');
		$providerContact = $this->getProviderContactControl()->get($idProvider, $idProviderContact);
		$this->getPhoneControl()->loadPhones($providerContact->getPhones());

		$result = new ApiResultObject();

		if ($this->getProviderContactControl()->remove($providerContact))
			$result->setResult($providerContact, 'contato de fornecedor "%s" excluído com êxito', $providerContact->getName());
		else
			$result->setResult($providerContact, 'não foi possível remover o contato de fornecedor "%s"', $providerContact->getName());

		return $result;
	}

	/**
	 * Obtém os dados de um contato do fornecedor através do seu código de identificação.
	 * @ApiAnnotation({"method":"post","params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com os dados do contato do fornecedor obtido.
	 */
	public function actionGetContact(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProvider = $content->getParameters()->getInt('idProvider');
		$idProviderContact = $post->getInt('id');
		$providerContact = $this->getProviderContactControl()->get($idProvider, $idProviderContact);
		$this->getPhoneControl()->loadPhones($providerContact->getPhones());

		$result = new ApiResultObject();
		$result->setResult($providerContact, 'contato de fornecedor "%s" obtido com êxito', $providerContact->getName());

		return $result;
	}

	/**
	 * Obtém uma lista de contatos do fornecedor através do código de identificação do fornecedor.
	 * @ApiAnnotation({"params":["idProvider"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado com a lista de contatos do fornecedor obtida.
	 */
	public function actionGetContacts(ApiContent $content): ApiResultObject
	{
		$idProvider = $content->getParameters()->getInt('idProvider');
		$provider = $this->getProviderControl()->get($idProvider);
		$providerContacts = $this->getProviderContactControl()->getByProvider($idProvider);

		$result = new ApiResultObject();
		$result->setResult($providerContacts, 'há %d contatos para o fornecedor "%s"', $providerContacts->size(), $provider->getFantasyName());

		return $result;
	}
}

