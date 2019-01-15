<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultServicePriceSettings;
use tercom\entities\ServicePrice;

/**
 *
 * @see DefaultSiteService
 * @see ApiResultObject
 * @see ApiResultObject
 * @see ApiResultServicePriceSettings
 * @author Andrew
 */
class ServicePriceService extends DefaultSiteService
{

	/**
	 *
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content
	 * @return ApiResultServicePriceSettings
	 */
	public function actionSettings(ApiContent $content): ApiResultServicePriceSettings
	{
		return new ApiResultServicePriceSettings();
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post"})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idService = $post->getInt('idService');
		$idProvider = $post->getInt('idProvider');
		$service = $this->getServiceControl()->get($idService);
		$provider = $this->getProviderControl()->get($idProvider);

		$servicePrice = new ServicePrice();
		$servicePrice->setService($service);
		$servicePrice->setProvider($provider);
		$servicePrice->setName($service->getName());
		$servicePrice->setPrice($post->getFloat('price'));
		if ($post->isSetted('name'))
			$servicePrice->setName($post->getString('name'));
		if ($post->isSetted('additionalDescription'))
			$servicePrice->setAdditionalDescription($post->getString('additionalDescription'));

		$this->getServicePriceControl()->add($servicePrice);

		$result = new ApiResultObject();
		$result->setResult($servicePrice, 'adicionado serviço "%s" por R$ %.2f em "%s"', $service->getName(), $servicePrice->getPrice(), $provider->getFantasyName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idServicePrice"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idServicePrice = $content->getParameters()->getInt('idServicePrice');
		$servicePrice = $this->getServicePriceControl()->get($idServicePrice);

		if ($post->isSetted('name'))
			$servicePrice->setName($post->getString('name'));
		if ($post->isSetted('price'))
			$servicePrice->setPrice($post->getFloat('price'));
		if ($post->isSetted('additionalDescription'))
			$servicePrice->setAdditionalDescription($post->getString('additionalDescription'));

		$this->getServicePriceControl()->set($servicePrice);

		$result = new ApiResultObject();
		$result->setResult($servicePrice, 'preço de serviço atualizado para R$ %.2f', $servicePrice->getPrice());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idServicePrice"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$idServicePrice = $content->getParameters()->getInt('idServicePrice');
		$servicePrice = $this->getServicePriceControl()->get($idServicePrice);
		$this->getServicePriceControl()->remove($servicePrice);

		$result = new ApiResultObject();
		$result->setResult($servicePrice, 'preço de serviço a R$ %.2f excluído com êxtio', $servicePrice->getPrice());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idServicePrice"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idServicePrice = $content->getParameters()->getInt('idServicePrice');
		$servicePrice = $this->getServicePriceControl()->get($idServicePrice);

		$result = new ApiResultObject();
		$result->setResult($servicePrice, 'preço de serviço "%s" obtido com êxtio', $servicePrice->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idService"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$idService = $content->getParameters()->getInt('idService');
		$service = $this->getServiceControl()->get($idService);
		$servicePrices = $this->getServicePriceControl()->searchByService($idService);

		$result = new ApiResultObject();
		$result->setResult($servicePrices, 'encontrado %d preços para o serviço "%s"', $servicePrices->size(), $service->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idService","idProvider"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetProvider(ApiContent $content): ApiResultObject
	{
		$idService = $content->getParameters()->getInt('idService');
		$idProvider = $content->getParameters()->getInt('idProvider');
		$service = $this->getServiceControl()->get($idService);
		$provider = $this->getProviderControl()->get($idProvider);
		$servicePrices = $this->getServicePriceControl()->searchByProvider($idProvider);

		$result = new ApiResultObject();
		$result->setResult($servicePrices, 'encontrado %d preços para o serviço "%s" em "%s"', $servicePrices->size(), $service->getName(), $provider->getFantasyName());

		return $result;
	}

}

