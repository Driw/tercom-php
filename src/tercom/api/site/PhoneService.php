<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultPhoneSettings;
use tercom\entities\Phone;

/**
 * @see RelationshipService
 * @author Andrew
 */
class PhoneService extends RelationshipService
{
	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\RelationshipService::getRelationshipName()
	 */
	public function getRelationshipName(): string
	{
		return nameOf(Phone::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\RelationshipService::actionSettings()
	 */
	public function actionSettings(ApiContent $content): ApiResult
	{
		return new ApiResultPhoneSettings();
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @return ApiResultObject
	 */
	protected function addCustomerPhone(ApiContent $content, int $idCustomer): ApiResultObject
	{
		$post = $content->getPost();
		$customer = $this->getCustomerControl()->get($idCustomer);

		$phone = new Phone();
		$phone->setDDD($post->getInt('ddd'));
		$phone->setNumber($post->getString('number'));
		$phone->setType($post->getString('type'));
		$this->getCustomerPhoneControl()->addRelationship($customer, $phone);

		$result = new ApiResultObject();
		$result->setResult($phone, 'novo telefone para o cliente "%s"', $customer->getFantasyName());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @param int $idPhone
	 * @return ApiResultObject
	 */
	protected function setCustomerPhone(ApiContent $content, int $idCustomer, int $idPhone): ApiResultObject
	{
		$post = $content->getPost();
		$customer = $this->getCustomerControl()->get($idCustomer);
		$phone = $this->getCustomerPhoneControl()->getRelationship($customer, $idPhone);

		if ($post->isSetted('ddd')) $phone->setDDD($post->getInt('ddd'));
		if ($post->isSetted('number')) $phone->setNumber($post->getString('number'));
		if ($post->isSetted('type')) $phone->setType($post->getString('type'));

		$this->getCustomerPhoneControl()->setRelationship($customer, $phone);

		$result = new ApiResultObject();
		$result->setResult($phone, 'telefone atualizado com êxito');

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @param int $idPhone
	 * @return ApiResultObject
	 */
	protected function removeCustomerPhone(ApiContent $content, int $idCustomer, int $idPhone): ApiResultObject
	{
		$customer = $this->getCustomerControl()->get($idCustomer);
		$phone = $this->getCustomerPhoneControl()->getRelationship($customer, $idPhone);
		$this->getCustomerPhoneControl()->removeRelationship($customer, $phone);

		$result = new ApiResultObject();
		$result->setResult($phone, 'telefone excluído com êxito');

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @param int $idPhone
	 * @return ApiResultObject
	 */
	protected function getCustomerPhone(ApiContent $content, int $idCustomer, int $idPhone): ApiResultObject
	{
		$customer = $this->getCustomerControl()->get($idCustomer);
		$phone = $this->getCustomerPhoneControl()->getRelationship($customer, $idPhone);

		$result = new ApiResultObject();
		$result->setResult($phone, 'telefone carregado com êxito');

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @return ApiResultObject
	 */
	protected function getAllCustomerPhone(ApiContent $content, int $idCustomer): ApiResultObject
	{
		$customer = $this->getCustomerControl()->get($idCustomer);
		$this->getCustomerPhoneControl()->getRelationships($customer);

		$result = new ApiResultObject();
		$result->setResult($customer->getPhones(), 'encontrado %d telefones para "%s"', $customer->getPhones()->size(), $customer->getFantasyName());

		return $result;
	}
}

