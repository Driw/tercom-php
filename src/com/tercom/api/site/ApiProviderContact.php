<?php

namespace tercom\api\site;

use Exception;
use dProject\Primitive\ArrayData;
use tercom\api\ApiActionInterface;
use tercom\api\ApiConnection;
use tercom\api\ApiMissParam;
use tercom\api\ApiResult;
use tercom\entities\ProviderContact;
use tercom\control\ProviderContactControl;
use tercom\control\ProviderControl;
use tercom\entities\Provider;
use tercom\api\ApiException;
use tercom\control\PhoneControl;
use dProject\Primitive\PostService;

class ApiProviderContact extends ApiActionInterface
{
	const REMOVE_COMMERCIAL_PHONE = 1;
	const REMOVE_OTHER_PHONE = 2;
	const REMOVE_ALL_PHONES = 3;

	public function __construct(ApiConnection $apiConnection, string $apiname, array $vars)
	{
		parent::__contruct($apiConnection, $apiname, $vars);
	}

	public function execute():ApiResult
	{
		ApiConnection::validateInternalCall();

		return $this->defaultExecute();
	}

	public function actionAdd(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();;

		$provider = $this->parseProvider($parameters);

		try {

			$providerContact = new ProviderContact();
			$providerContact->setName($POST->getString('name'));

			if ($POST->isSetted('email')) $providerContact->setEmail($POST->getString('email'));
			if ($POST->isSetted('position')) $providerContact->setPosition($POST->getString('position'));

		} catch (Exception $e) {
			return new ApiMissParam($e->getMessage());
		}

		$providerContactControl = new ProviderContactControl($this->getMySQL());
		$providerContactControl->addProviderContact($provider, $providerContact);

		$result = new ApiResultProviderContact();
		$result->setProviderContact($providerContact);

		return $result;
	}

	public function actionSet(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();;

		$providerID = $parameters->getInt(0);
		$providerContactID = $POST->getInt('id');
		$providerContactControl = new ProviderContactControl($this->getMySQL());

		if (($providerContact = $providerContactControl->getProvideContact($providerID, $providerContactID)) === null)
			throw new ApiException();

		$phoneControl = new PhoneControl($this->getMySQL());
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

	public function actionSetPhones(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();;

		$providerID = $parameters->getInt(0);
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl($this->getMySQL());

		if (($providerContact = $providerContactControl->getProvideContact($providerID, $providerContactID)) === null)
			throw new ApiException('contato de fornecedor não encontrado');

		$phoneControl = new PhoneControl($this->getMySQL());
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

	public function actionRemoveCommercial(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();;

		$providerID = $parameters->getInt(0);
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl($this->getMySQL());

		if (($providerContact = $providerContactControl->getProvideContact($providerID, $providerContactID)) === null)
			throw new ApiException();

		$result = new ApiResultProviderContact();
		$result->setProviderContact($providerContact);

		if ($providerContactControl->removeCommercial($providerContact))
			$result->setMessage('telefone comercial excluído');
		else
			$result->setMessage('telefone comercial não definido');

		$phoneControl = new PhoneControl($this->getMySQL());
		$phoneControl->loadPhones($providerContact->getPhones());

		return $result;
	}

	public function actionRemoveOtherphone(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();;

		$providerID = $parameters->getInt(0);
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl($this->getMySQL());

		if (($providerContact = $providerContactControl->getProvideContact($providerID, $providerContactID)) === null)
			throw new ApiException();

		$result = new ApiResultProviderContact();
		$result->setProviderContact($providerContact);

		if ($providerContactControl->removeOtherphone($providerContact))
			$result->setMessage('telefone secundário excluído');
		else
			$result->setMessage('telefone secundário não definido');

		$phoneControl = new PhoneControl($this->getMySQL());
		$phoneControl->loadPhones($providerContact->getPhones());

		return $result;
	}

	public function actionRemoveContact(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();;

		$provider = $this->parseProvider($parameters);
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl($this->getMySQL());
		$providerContactControl->loadProviderContacts($provider);

		if (($providerContact = $provider->getContacs()->getContactByID($providerContactID)) === null)
			throw new ApiException('contato de fornecedor não vinculado ao fornecedor');

		if (($providerContact = $providerContactControl->getProvideContact($provider->getID(), $providerContactID)) === null)
			throw new ApiException();

		$phoneControl = new PhoneControl($this->getMySQL());
		$phoneControl->loadPhones($providerContact->getPhones());

		if ($providerContactControl->removeProviderContact($providerContact));
			$provider->getContacs()->removeElement($providerContact);

		$result = new ApiResultProviderContacts();
		$result->setProviderContacts($provider->getContacs());

		return $result;
	}

	public function actionGetContact(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();;

		$providerID = $parameters->getInt(0);
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl($this->getMySQL());

		if (($providerContact = $providerContactControl->getProvideContact($providerID, $providerContactID)) === null)
			throw new ApiException('contato de fornecedor não encontrado');

		$phoneControl = new PhoneControl($this->getMySQL());
		$phoneControl->loadPhones($providerContact->getPhones());

		$result = new ApiResultProviderContact();
		$result->setProviderContact($providerContact);

		return $result;
	}

	public function actionGetContacts(ArrayData $parameters):ApiResult
	{
		$providerID = $parameters->getInt(0);

		$providerContactControl = new ProviderContactControl($this->getMySQL());
		$providerContacts = $providerContactControl->getProvideContacts($providerID);

		$phoneControl = new PhoneControl($this->getMySQL());

		foreach ($providerContacts as $providerContact)
			$phoneControl->loadPhones($providerContact->getPhones());

		$result = new ApiResultProviderContacts();
		$result->setProviderContacts($providerContacts);

		return $result;
	}

	private function parseProvider(ArrayData $parameters):Provider
	{
		try {

			$providerID = $parameters->getInt(0);
			$providerControl = new ProviderControl($this->getMySQL());

			if (($provider = $providerControl->get($providerID)) === null)
				throw new ApiException('fornecedor não encontrado');

			return $provider;

		} catch (Exception $e) {
			throw new ApiException('fornecedor não informado');
		}
	}
}

?>