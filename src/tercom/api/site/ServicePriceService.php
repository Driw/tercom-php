<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\exceptions\ServicePriceException;
use tercom\api\site\results\ApiResultServicePrice;
use tercom\api\site\results\ApiResultServicePrices;
use tercom\api\site\results\ApiResultServicePriceSettings;
use tercom\entities\ServicePrice;

/**
 * @see DefaultSiteService
 * @see ApiResultServicePrice
 * @see ApiResultServicePrices
 * @see ApiResultServicePriceSettings
 * @author Andrew
 */
class ServicePriceService extends DefaultSiteService
{
	/**
	 * @param ApiContent $content
	 * @return ApiResultServicePriceSettings
	 */
	public function actionSettings(ApiContent $content): ApiResultServicePriceSettings
	{
		return new ApiResultServicePriceSettings();
	}

	/**
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content
	 * @return ApiResultServicePrice
	 */
	public function actionAdd(ApiContent $content): ApiResultServicePrice
	{
		$post = $content->getPost();
		$idService = $post->getInt('idService');
		$idProvider = $post->getInt('idProvider');

		if (($service = $this->getServiceControl()->get($idService)) === null)
			throw ServicePriceException::newServiceNotFound();

		if (($provider = $this->getProviderControl()->get($idProvider)) === null)
			throw ServicePriceException::newProviderNotFound();

		$servicePrice = new ServicePrice();
		$servicePrice->setService($service);
		$servicePrice->setProvider($provider);
		$servicePrice->setName($service->getName());
		$servicePrice->setPrice($post->getFloat('price'));
		if ($post->isSetted('name')) $servicePrice->setName($post->getString('name'));
		if ($post->isSetted('additionalDescription')) $servicePrice->setAdditionalDescription($post->getString('additionalDescription'));

		if (!$this->getServicePriceControl()->add($servicePrice))
			throw ServicePriceException::newNotAdd();

		$result = new ApiResultServicePrice();
		$result->setServicePrice($servicePrice);
		$result->setMessage('adicionado serviço "%s" por R$ %.2f em "%s"', $service->getName(), $servicePrice->getPrice(), $provider->getFantasyName());

		return $result;
	}

	/**
	 * @ApiAnnotation({"method":"post","params":["idServicePrice"]})
	 * @param ApiContent $content
	 * @return ApiResultServicePrice
	 */
	public function actionSet(ApiContent $content): ApiResultServicePrice
	{
		$post = $content->getPost();
		$idServicePrice = $content->getParameters()->getInt('idServicePrice');

		if (($servicePrice = $this->getServicePriceControl()->get($idServicePrice)) === nul)
			throw ServicePriceException::newNotFound();

		if ($post->isSetted('name')) $servicePrice->setName($post->getString('name'));
		if ($post->isSetted('price')) $servicePrice->setPrice($post->getFloat('price'));
		if ($post->isSetted('additionalDescription')) $servicePrice->setAdditionalDescription($post->getString('additionalDescription'));

		if (!$this->getServicePriceControl()->set($servicePrice))
			throw ServicePriceException::newNotAdd();

		$result = new ApiResultServicePrice();
		$result->setServicePrice($servicePrice);
		$result->setMessage('preço de serviço atualizado para R$ %.2f', $servicePrice->getPrice());

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idServicePrice"]})
	 * @param ApiContent $content
	 * @return ApiResultServicePrice
	 */
	public function actionRemove(ApiContent $content): ApiResultServicePrice
	{
		$idServicePrice = $content->getParameters()->getInt('idServicePrice');

		if (($servicePrice = $this->getServicePriceControl()->get($idServicePrice)) === null)
			throw ServicePriceException::newNotFound();

		if (!$this->getServicePriceControl()->remove($servicePrice))
			throw ServicePriceException::newNotAdd();

		$result = new ApiResultServicePrice();
		$result->setServicePrice($servicePrice);
		$result->setMessage('preço de serviço a R$ %.2f excluído com êxtio', $servicePrice->getPrice());

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idServicePrice"]})
	 * @param ApiContent $content
	 * @return ApiResultServicePrice
	 */
	public function actionGet(ApiContent $content): ApiResultServicePrice
	{
		$idServicePrice = $content->getParameters()->getInt('idServicePrice');

		if (($servicePrice = $this->getServicePriceControl()->get($idServicePrice)) === null)
			throw ServicePriceException::newNotFound();

		$result = new ApiResultServicePrice();
		$result->setServicePrice($servicePrice);
		$result->setMessage('preço de serviço obtido com êxtio');

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idService"]})
	 * @param ApiContent $content
	 * @return ApiResultServicePrices
	 */
	public function actionGetService(ApiContent $content): ApiResultServicePrices
	{
		$idService = $content->getParameters()->getInt('idService');

		if (($service = $this->getServiceControl()->get($idService)) === null)
			throw ServicePriceException::newServiceNotFound();

		$servicePrices = $this->getServicePriceControl()->getByService($idService);

		$result = new ApiResultServicePrices();
		$result->setServicePrices($servicePrices);
		$result->setMessage('encontrado %d preços para o serviço "%s"', $servicePrices->size(), $service->getName());

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idService","idProvider"]})
	 * @param ApiContent $content
	 * @return ApiResultServicePrices
	 */
	public function actionGetProvider(ApiContent $content): ApiResultServicePrices
	{
		$idService = $content->getParameters()->getInt('idService');
		$idProvider = $content->getParameters()->getInt('idProvider');

		if (($service = $this->getServiceControl()->get($idService)) === null)
			throw ServicePriceException::newServiceNotFound();

		if (($provider = $this->getProviderControl()->get($idProvider)) === null)
			throw ServicePriceException::newProviderNotFound();

		$servicePrices = $this->getServicePriceControl()->getByService($idService);

		$result = new ApiResultServicePrices();
		$result->setServicePrices($servicePrices);
		$result->setMessage('encontrado %d preços para o serviço "%s" em "%s"', $servicePrices->size(), $service->getName(), $provider->getFantasyName());

		return $result;
	}
}

