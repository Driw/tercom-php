<?php

namespace tercom\api\site;

use Exception;
use dProject\Primitive\ArrayData;
use tercom\api\ApiActionInterface;
use tercom\api\ApiConnection;
use tercom\api\ApiException;
use tercom\api\ApiResult;
use tercom\api\ApiMissParam;
use tercom\control\PhoneControl;
use tercom\control\ProviderControl;
use tercom\entities\Provider;
use tercom\control\ProviderContactControl;
use dProject\Primitive\PostService;

class ApiProvider extends ApiActionInterface
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

	public function actionSettings(ArrayData $parameters):ApiResult
	{
		return new ApiResultProviderSettings();
	}

	public function actionAdd(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();

		try {

			$provider = new Provider();
			$provider->setCNPJ($POST->getString('cnpj'));
			$provider->setCompanyName($POST->getString('companyName'));
			$provider->setFantasyName($POST->getString('fantasyName'));
			if ($POST->isSetted('spokesman')) $provider->setSpokesman($POST->getString('spokesman'));
			if ($POST->isSetted('site')) $provider->setSite($POST->getString('site'));
			$provider->setInactive(false);

		} catch (Exception $e) {
			return new ApiMissParam($e->getMessage());
		}

		$providerControl = new ProviderControl($this->getMySQL());
		$providerControl->add($provider);

		$result = new ApiResultProvider();
		$result->setProvider($provider);

		return $result;
	}

	public function actionSet(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();

		$providerID = $this->parseProviderID($parameters);
		$providerControl = new ProviderControl($this->getMySQL());

		if (($provider = $providerControl->get($providerID)) == null)
			throw new ApiException('fornecedor não encontrado');

		try {

			if ($POST->isSetted('cnpj')) $provider->setCNPJ($POST->getString('cnpj'));
			if ($POST->isSetted('companyName')) $provider->setCompanyName($POST->getString('companyName'));
			if ($POST->isSetted('fantasyName')) $provider->setFantasyName($POST->getString('fantasyName'));
			if ($POST->isSetted('spokesman')) $provider->setSpokesman($POST->getString('spokesman'));
			if ($POST->isSetted('site')) $provider->setSite($POST->getString('site'));
			if ($POST->isSetted('inactive')) $provider->setSite($POST->getBoolean('inactive'));

		} catch (Exception $e) {
			return new ApiMissParam($e->getMessage());
		}

		$provider->setInactive(false);
		$providerControl->set($provider);

		$result = new ApiResultProvider();
		$result->setProvider($provider);

		return $result;
	}

	public function actionGet(ArrayData $parameters):ApiResult
	{
		$providerID = $this->parseProviderID($parameters);
		$providerControl = new ProviderControl($this->getMySQL());
		$providerContactControl = new ProviderContactControl($this->getMySQL());

		if (($provider = $providerControl->get($providerID)) == null)
			throw new ApiException('fornecedor não encontrado');

		$phoneControl = new PhoneControl($this->getMySQL());
		$phoneControl->loadPhones($provider->getPhones());

		$providerContactControl->loadProviderContacts($provider);
		$result = new ApiResultProvider();
		$result->setProvider($provider);

		return $result;
	}

	public function actionSetPhones(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();;

		$providerID = $this->parseProviderID($parameters);
		$providerControl = new ProviderControl($this->getMySQL());

		if (($provider = $providerControl->get($providerID)) == null)
			throw new ApiException('fornecedor não encontrado');

		$phoneControl = new PhoneControl($this->getMySQL());
		$phoneControl->loadPhones($provider->getPhones());

		try {

			if ($POST->isSetted('commercial'))
			{
				$commercialData = $POST->newArrayData('commercial');

				if ($commercialData->isSetted('ddd')) $provider->getCommercial()->setDDD($commercialData->getInt('ddd'));
				if ($commercialData->isSetted('number')) $provider->getCommercial()->setNumber($commercialData->getString('number'));
				if ($commercialData->isSetted('type')) $provider->getCommercial()->setType($commercialData->getString('type'));
			}

			if ($POST->isSetted('otherphone'))
			{
				$otherphoneData = $POST->newArrayData('otherphone');

				if ($otherphoneData->isSetted('ddd')) $provider->getOtherPhone()->setDDD($otherphoneData->getInt('ddd'));
				if ($otherphoneData->isSetted('number')) $provider->getOtherPhone()->setNumber($otherphoneData->getString('number'));
				if ($otherphoneData->isSetted('type')) $provider->getOtherPhone()->setType($otherphoneData->getString('type'));
			}

		} catch (Exception $e) {
			return new ApiMissParam($e->getMessage());
		}

		$providerControl->setPhones($provider);

		$result = new ApiResultProvider();
		$result->setProvider($provider);

		return $result;
	}

	public function actionRemovePhone(ArrayData $parameters):ApiResult
	{
		$providerID = $this->parseProviderID($parameters);
		$providerControl = new ProviderControl($this->getMySQL());

		if (($provider = $providerControl->get($providerID)) == null)
			throw new ApiException('fornecedor não encontrado');

		$phoneControl = new PhoneControl($this->getMySQL());
		$phoneControl->loadPhones($provider->getPhones());

		if (!$parameters->isSetted(1))
			throw new ApiException('telefone não definido');

		$result = new ApiResultProvider();

		switch ($parameters->getString(1))
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

	private function parseProviderID(ArrayData $parameters):int
	{
		try {
			return $parameters->getInt(0);
		} catch (Exception $e) {
			throw new ApiException('fornecedor não identificado');
		}
	}
}

?>