<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultAddressSettings;
use tercom\entities\Address;

/**
 * @see RelationshipService
 * @see AddressControl
 * @author Andrew
 */
class AddressService extends RelationshipService
{
	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\RelationshipService::getRelationshipName()
	 */
	public function getRelationshipName(): string
	{
		return nameOf(Address::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\RelationshipService::actionSettings()
	 */
	public function actionSettings(ApiContent $content): ApiResult
	{
		return new ApiResultAddressSettings();
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @return ApiResultObject
	 */
	protected function addCustomerAddress(ApiContent $content, int $idCustomer): ApiResultObject
	{
		$post = $content->getPost();
		$customer = $this->getCustomerControl()->get($idCustomer);

		$address = new Address();
		$address->setState($post->getString('state'));
		$address->setCity($post->getString('city'));
		$address->setCep($post->getString('cep'));
		$address->setNeighborhood($post->getString('neighborhood'));
		$address->setStreet($post->getString('street'));
		$address->setNumber($post->getInt('number'));
		$address->setComplement($post->getString('complement', false));
		$this->getCustomerAddressControl()->addRelationship($customer, $address);

		$result = new ApiResultObject();
		$result->setResult($address, 'novo endereço para o cliente "%s"', $customer->getFantasyName());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @param int $idAddress
	 * @return ApiResultObject
	 */
	protected function setCustomerAddress(ApiContent $content, int $idCustomer, int $idAddress): ApiResultObject
	{
		$post = $content->getPost();
		$customer = $this->getCustomerControl()->get($idCustomer);
		$address = $this->getCustomerAddressControl()->getRelationship($customer, $idAddress);

		if ($post->isSetted('state')) $address->setState($post->getString('state'));
		if ($post->isSetted('city')) $address->setCity($post->getString('city'));
		if ($post->isSetted('cep')) $address->setCep($post->getString('cep'));
		if ($post->isSetted('neighborhood')) $address->setNeighborhood($post->getString('neighborhood'));
		if ($post->isSetted('street')) $address->setStreet($post->getString('street'));
		if ($post->isSetted('number')) $address->setNumber($post->getInt('number'));
		if ($post->isSetted('complement')) $address->setComplement($post->getString('complement'));

		$this->getCustomerAddressControl()->setRelationship($customer, $address);

		$result = new ApiResultObject();
		$result->setResult($address, 'endereço atualizado com êxito');

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @param int $idAddress
	 * @return ApiResultObject
	 */
	public function removeCustomerAddress(ApiContent $content, int $idCustomer, int $idAddress): ApiResultObject
	{
		$customer = $this->getCustomerControl()->get($idCustomer);
		$address = $this->getCustomerAddressControl()->getRelationship($customer, $idAddress);
		$this->getCustomerAddressControl()->removeRelationship($customer, $address);

		$result = new ApiResultObject();
		$result->setResult($address, 'endereço excluído com êxito');

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @param int $idAddress
	 * @return ApiResultObject
	 */
	public function getCustomerAddress(ApiContent $content, int $idCustomer, int $idAddress): ApiResultObject
	{
		$customer = $this->getCustomerControl()->get($idCustomer);
		$address = $this->getCustomerAddressControl()->getRelationship($customer, $idAddress);

		$result = new ApiResultObject();
		$result->setResult($address, 'endereço carregado com êxito');

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @return ApiResultObject
	 */
	public function getAllCustomerAddress(ApiContent $content, int $idCustomer): ApiResultObject
	{
		$customer = $this->getCustomerControl()->get($idCustomer);
		$addresses = $this->getCustomerAddressControl()->getRelationships($customer);

		$result = new ApiResultObject();
		$result->setResult($addresses, 'encontrado %d endereços para "%s"', $customer->getAddresses()->size(), $customer->getFantasyName());

		return $result;
	}
}

