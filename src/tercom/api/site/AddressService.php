<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\api\exceptions\RelationshipException;
use tercom\api\site\results\ApiResultAddress;
use tercom\api\site\results\ApiResultAddresses;
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
	 * @var string
	 */
	public const RELATIONSHIP_CUSTOMER = 'customer';

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
	 * @ApiAnnotation({"method":"post","params":["relationship"]})
	 * @param ApiContent $content
	 * @return ApiResultAddress
	 */
	public function actionAdd(ApiContent $content): ApiResultAddress
	{
		$relationship = $content->getParameters()->getString('relationship');

		switch ($relationship)
		{
			case self::RELATIONSHIP_CUSTOMER: return $this->addCustomerAddress($content);
		}

		throw new RelationshipException($relationship);
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultAddress
	 */
	private function addCustomerAddress(ApiContent $content): ApiResultAddress
	{
		$post = $content->getPost();
		$idCustomer = $post->getInt('idCustomer');
		$customer = $this->getCustomerControl()->get($idCustomer);

		$address = new Address();
		$address->setState($post->getString('state'));
		$address->setCity($post->getString('city'));
		$address->setCep($post->getString('cep'));
		$address->setNeighborhood($post->getString('neighborhood'));
		$address->setStreet($post->getString('street'));
		$address->setNumber($post->getInt('number'));
		$address->setComplement($post->getString('complement', false));
		$this->getAddressControl()->addCustomerAddress($customer, $address);

		$result = new ApiResultAddress();
		$result->setAddress($address);
		$result->setMessage('novo endereço para o cliente "%s"', $customer->getFantasyName());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post","params":["relationship","idAddress"]})
	 * @param ApiContent $content
	 * @throws RelationshipException
	 * @return ApiResultAddress
	 */
	public function actionSet(ApiContent $content): ApiResultAddress
	{
		$relationship = $content->getParameters()->getString('relationship');

		switch ($relationship)
		{
			case self::RELATIONSHIP_CUSTOMER: return $this->setCustomerAddress($content);
		}

		throw new RelationshipException($relationship);
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultAddress
	 */
	private function setCustomerAddress(ApiContent $content): ApiResultAddress
	{
		$idAddress = $content->getParameters()->getInt('idAddress');
		$post = $content->getPost();
		$idCustomer = $post->getInt('idCustomer');

		$customer = $this->getCustomerControl()->get($idCustomer);
		$address = $this->getAddressControl()->getCustomerAddresses($customer, $idAddress);

		if ($post->isSetted('state')) $address->setState($post->getString('state'));
		if ($post->isSetted('city')) $address->setCity($post->getString('city'));
		if ($post->isSetted('cep')) $address->setCep($post->getString('cep'));
		if ($post->isSetted('neighborhood')) $address->setNeighborhood($post->getString('neighborhood'));
		if ($post->isSetted('street')) $address->setStreet($post->getString('street'));
		if ($post->isSetted('number')) $address->setNumber($post->getInt('number'));
		if ($post->isSetted('complement')) $address->setComplement($post->getString('complement'));

		$result = new ApiResultAddress();
		$result->setAddress($address);

		if ($this->getAddressControl()->setCustomerAddress($customer, $address))
			$result->setMessage('endereço atualizado com êxito');
		else
			$result->setMessage('nenhum dado alterado no endereço');

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post","params":["relationship","idAddress"]})
	 * @param ApiContent $content
	 * @return ApiResultAddress
	 */
	public function actionRemove(ApiContent $content): ApiResultAddress
	{
		$relationship = $content->getParameters()->getString('relationship');

		switch ($relationship)
		{
			case self::RELATIONSHIP_CUSTOMER: return $this->removeCustomerAddress($content);
		}

		throw new RelationshipException($relationship);
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultAddress
	 */
	public function removeCustomerAddress(ApiContent $content): ApiResultAddress
	{
		$idAddress = $content->getParameters()->getInt('idAddress');
		$post = $content->getPost();
		$idCustomer = $post->getInt('idCustomer');

		$customer = $this->getCustomerControl()->get($idCustomer);
		$address = $this->getAddressControl()->getCustomerAddresses($customer, $idAddress);
		$this->getAddressControl()->removeCustomerAddress($customer, $address);

		$result = new ApiResultAddress();
		$result->setAddress($address);
		$result->setMessage('endereço excluído com êxito');

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["relationship","idRelationship","idAddress"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */
	public function actionGet(ApiContent $content): ApiResult
	{
		$parameters = $content->getParameters();
		$relationship = $parameters->getString('relationship');
		$idRelationship = $parameters->getInt('idRelationship');
		$idAddress = $parameters->getInt('idAddress');

		switch ($relationship)
		{
			case self::RELATIONSHIP_CUSTOMER: return $this->getCustomerAddress($content, $idRelationship, $idAddress);
		}

		throw new RelationshipException($relationship);
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @param int $idAddress
	 * @return ApiResultAddress
	 */
	public function getCustomerAddress(ApiContent $content, int $idCustomer, int $idAddress): ApiResultAddress
	{
		$customer = $this->getCustomerControl()->get($idCustomer);
		$address = $this->getAddressControl()->getCustomerAddresses($customer, $idAddress);

		$result = new ApiResultAddress();
		$result->setAddress($address);
		$result->setMessage('endereço carregado com êxito');

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["relationship","idRelationship"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */
	public function actionGetAll(ApiContent $content): ApiResult
	{
		$parameters = $content->getParameters();
		$relationship = $parameters->getString('relationship');
		$idRelationship = $parameters->getInt('idRelationship');

		switch ($relationship)
		{
			case self::RELATIONSHIP_CUSTOMER: return $this->getCustomerAddresses($content, $idRelationship);
		}

		throw new RelationshipException($relationship);
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @return ApiResultAddresses
	 */
	public function getCustomerAddresses(ApiContent $content, int $idCustomer): ApiResultAddresses
	{
		$customer = $this->getCustomerControl()->get($idCustomer);
		$this->getAddressControl()->loadCustomerAddresses($customer);

		$result = new ApiResultAddresses();
		$result->setAddresses($customer->getAddresses());
		$result->setMessage('encontrado %d endereços para "%s"', $customer->getAddresses()->size(), $customer->getFantasyName());

		return $result;
	}
}

