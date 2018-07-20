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
		global $POST;

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
		global $POST;

		$providerID = $parameters->getInt(0);
		$providerContactID = $POST->getInt('id');
		$providerContactControl = new ProviderContactControl($this->getMySQL());
		$providerContact = $providerContactControl->getProvideContact($providerID, $providerContactID);

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
		global $POST;

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
		global $POST;

		$providerID = $parameters->getInt(0);
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl($this->getMySQL());
		$providerContact = $providerContactControl->getProvideContact($providerID, $providerContactID);
		$providerContactControl->removeCommercial($providerContact);

		$phoneControl = new PhoneControl($this->getMySQL());
		$phoneControl->loadPhone($providerContact->getCommercial());
		$phoneControl->loadPhone($providerContact->getOtherPhone());

		$result = new ApiResultProviderContact();
		$result->setProviderContact($providerContact);

		return $result;
	}

	public function actionRemoveOtherphone(ArrayData $parameters):ApiResult
	{
		global $POST;

		$providerID = $parameters->getInt(0);
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl($this->getMySQL());
		$providerContact = $providerContactControl->getProvideContact($providerID, $providerContactID);
		$providerContactControl->removeOtherphone($providerContact);

		$phoneControl = new PhoneControl($this->getMySQL());
		$phoneControl->loadPhone($providerContact->getCommercial());
		$phoneControl->loadPhone($providerContact->getOtherPhone());

		$result = new ApiResultProviderContact();
		$result->setProviderContact($providerContact);

		return $result;
	}

	public function actionRemoveContact(ArrayData $parameters):ApiResult
	{
		global $POST;

		$provider = $this->parseProvider($parameters);
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl($this->getMySQL());
		$providerContactControl->loadProviderContacts($provider);

		if (($providerContact = $provider->getContacs()->getContactByID($providerContactID)) === null)
			throw new ApiException('contato de fornecedor não vinculado ao fornecedor');

		$providerContact = $providerContactControl->getProvideContact($provider->getID(), $providerContactID);

		if ($providerContactControl->removeProviderContact($providerContact));
			$provider->getContacs()->removeContact($providerContact);

		$result = new ApiResultProviderContacts();
		$result->setProviderContacts($provider->getContacs());

		return $result;
	}

	public function actionGetContact(ArrayData $parameters):ApiResult
	{
		global $POST;

		$providerID = $parameters->getInt(0);
		$providerContactID = $POST->getInt('id');

		$providerContactControl = new ProviderContactControl($this->getMySQL());

		if (($providerContact = $providerContactControl->getProvideContact($providerID, $providerContactID)) === null)
			throw new ApiException('contato de fornecedor não encontrado');

		$phoneControl = new PhoneControl($this->getMySQL());
		$phoneControl->loadPhone($providerContact->getCommercial());
		$phoneControl->loadPhone($providerContact->getOtherPhone());

		$result = new ApiResultProviderContact();
		$result->setProviderContact($providerContact);

		return $result;
	}

	public function actionGetContacts(ArrayData $parameters):ApiResult
	{
		$providerID = $parameters->getInt(0);

		$providerContactControl = new ProviderContactControl($this->getMySQL());
		$providerContacts = $providerContactControl->getProvideContacts($providerID);

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