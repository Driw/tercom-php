<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultAddress;
use tercom\api\site\results\ApiResultAddressSettings;
use tercom\entities\Address;

/**
 * @see DefaultSiteService
 * @see AddressControl
 * @author Andrew
 */
class AddressService extends DefaultSiteService
{
	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultAddressSettings
	 */
	public function actionSettings(ApiContent $content): ApiResultAddressSettings
	{
		return new ApiResultAddressSettings();
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content
	 * @return ApiResultAddress
	 */
	public function actionAdd(ApiContent $content): ApiResultAddress
	{
		$post = $content->getPost();

		$address = new Address();
		$address->setState($post->getString('state'));
		$address->setCity($post->getString('city'));
		$address->setCep($post->getString('cep'));
		$address->setNeighborhood($post->getString('neighborhood'));
		$address->setStreet($post->getString('street'));
		$address->setNumber($post->getInt('number'));
		$address->setComplement($post->getString('complement', false));
		$this->getAddressControl()->add($address);

		$result = new ApiResultAddress();
		$result->setAddress($address);
		$result->setMessage('endereço adicionado com êxito');

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post","params":["idAddress"]})
	 * @param ApiContent $content
	 * @return ApiResultAddress
	 */
	public function actionSet(ApiContent $content): ApiResultAddress
	{
		$post = $content->getPost();
		$idAddress = $content->getParameters()->getInt('idAddress');
		$address = $this->getAddressControl()->get($idAddress);

		if ($post->isSetted('state')) $address->setState($post->getString('state'));
		if ($post->isSetted('city')) $address->setCity($post->getString('city'));
		if ($post->isSetted('cep')) $address->setCep($post->getString('cep'));
		if ($post->isSetted('neighborhood')) $address->setNeighborhood($post->getString('neighborhood'));
		if ($post->isSetted('street')) $address->setStreet($post->getString('street'));
		if ($post->isSetted('number')) $address->setNumber($post->getInt('number'));
		if ($post->isSetted('complement')) $address->setComplement($post->getString('complement'));

		$this->getAddressControl()->set($address);

		$result = new ApiResultAddress();
		$result->setAddress($address);
		$result->setMessage('endereço atualizado com êxito');

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["idAddress"]})
	 * @param ApiContent $content
	 * @return ApiResultAddress
	 */
	public function actionRemove(ApiContent $content): ApiResultAddress
	{
		$idAddress = $content->getParameters()->getInt('idAddress');
		$address = $this->getAddressControl()->get($idAddress);
		$this->getAddressControl()->remove($address);

		$result = new ApiResultAddress();
		$result->setAddress($address);
		$result->setMessage('endereço excluído com êxito');

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["idAddress"]})
	 * @param ApiContent $content
	 * @return ApiResultAddress
	 */
	public function actionGet(ApiContent $content): ApiResultAddress
	{
		$idAddress = $content->getParameters()->getInt('idAddress');
		$address = $this->getAddressControl()->get($idAddress);

		$result = new ApiResultAddress();
		$result->setAddress($address);
		$result->setMessage('endereço atualizado com êxito');

		return $result;
	}
}

