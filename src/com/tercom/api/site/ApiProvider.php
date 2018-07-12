<?php

namespace tercom\api\site;

use Exception;
use dProject\Primitive\StringUtil;
use dProject\Primitive\ArrayData;
use tercom\api\ApiActionInterface;
use tercom\api\ApiConnection;
use tercom\api\ApiResult;
use tercom\api\ApiUnauthorizedException;
use tercom\api\ApiMissParam;
use tercom\control\ProviderControl;
use tercom\entities\Provider;
use tercom\api\ApiException;

class ApiProvider extends ApiActionInterface
{
	public function __construct(ApiConnection $apiConnection, string $apiname, array $vars)
	{
		parent::__contruct($apiConnection, $apiname, $vars);
	}

	public function execute(): ApiResult
	{
		// Se não tem HTTP_REFERER está sendo acessado diretamente pelo link
		if (!isset($_SERVER['HTTP_REFERER']))
		{
			// Se estiver em dev podemos permitir
			if (SYS_DEVELOP !== true)
				throw new ApiUnauthorizedException();
		}

		// Se tem HTTP_REFERER foi chamado de alguma página por AJAX por exemplo
		else
		{
			// No caso dessa API só será permitido o acesso do nosso site
			if (!StringUtil::startsWith($_SERVER['HTTP_REFERER'], DOMAIN) && !StringUtil::startsWith($_SERVER['HTTP_REFERER'], WWW_DOMAIN))
				throw new ApiUnauthorizedException();
		}

		return $this->defaultExecute();
	}

	public function actionAdd(ArrayData $parameters): ApiResultProvider
	{
		global $POST;

		try {

			$provider = new Provider();
			$provider->setCNPJ($POST->getString('cnpj'));
			$provider->setCompanyName($POST->getString('companyName'));
			$provider->setFantasyName($POST->getString('fantasyName'));
			$provider->setSpokesman($POST->getString('spokesman'));
			$provider->setSite($POST->getString('site'));
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

	public function actionSet(ArrayData $parameters): ApiResult
	{
		global $POST;

		$providerID = $parameters->getInt(0);
		$providerControl = new ProviderControl($this->getMySQL());
		$provider = $providerControl->get($providerID);

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

	public function actionGet(ArrayData $parameters):?ApiResult
	{
		$providerID = $parameters->getInt(0);
		$providerControl = new ProviderControl($this->getMySQL());

		if (($provider = $providerControl->get($providerID)) == null)
			throw new ApiException('fornecedor não encontrado');

		$result = new ApiResultProvider();
		$result->setProvider($provider);

		return $result;
	}
}

?>