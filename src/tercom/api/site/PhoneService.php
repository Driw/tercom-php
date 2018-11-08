<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\api\site\results\ApiResultPhone;
use tercom\api\site\results\ApiResultPhones;
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
	 * @return ApiResultPhone
	 */
	protected function addCustomerPhone(ApiContent $content, int $idCustomer): ApiResultPhone
	{
		$post = $content->getPost();
		$customer = $this->getCustomerControl()->get($idCustomer);

		$phone = new Phone();
		$phone->setDDD($post->getInt('ddd'));
		$phone->setNumber($post->getString('number'));
		$phone->setType($post->getString('type'));
		$this->getCustomerPhoneControl()->addRelationship($customer, $phone);

		$result = new ApiResultPhone();
		$result->setPhone($phone);
		$result->setMessage('novo telefone para o cliente "%s"', $customer->getFantasyName());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @param int $idPhone
	 * @return ApiResultPhone
	 */
	protected function setCustomerPhone(ApiContent $content, int $idCustomer, int $idPhone): ApiResultPhone
	{
		$post = $content->getPost();
		$customer = $this->getCustomerControl()->get($idCustomer);
		$phone = $this->getCustomerPhoneControl()->getRelationship($customer, $idPhone);

		if ($post->isSetted('ddd')) $phone->setDDD($post->getInt('ddd'));
		if ($post->isSetted('number')) $phone->setNumber($post->getString('number'));
		if ($post->isSetted('type')) $phone->setType($post->getString('type'));

		$this->getCustomerPhoneControl()->setRelationship($customer, $phone);

		$result = new ApiResultPhone();
		$result->setPhone($phone);
		$result->setMessage('telefone atualizado com êxito');

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @param int $idPhone
	 * @return ApiResultPhone
	 */
	protected function removeCustomerPhone(ApiContent $content, int $idCustomer, int $idPhone): ApiResultPhone
	{
		$customer = $this->getCustomerControl()->get($idCustomer);
		$phone = $this->getCustomerPhoneControl()->getRelationship($customer, $idPhone);
		$this->getCustomerPhoneControl()->removeRelationship($customer, $phone);

		$result = new ApiResultPhone();
		$result->setPhone($phone);
		$result->setMessage('telefone excluído com êxito');

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @param int $idPhone
	 * @return ApiResultPhone
	 */
	protected function getCustomerPhone(ApiContent $content, int $idCustomer, int $idPhone): ApiResultPhone
	{
		$customer = $this->getCustomerControl()->get($idCustomer);
		$phone = $this->getCustomerPhoneControl()->getRelationship($customer, $idPhone);

		$result = new ApiResultPhone();
		$result->setPhone($phone);
		$result->setMessage('telefone carregado com êxito');

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @param int $idCustomer
	 * @return ApiResultPhones
	 */
	protected function getAllCustomerPhone(ApiContent $content, int $idCustomer): ApiResultPhones
	{
		$customer = $this->getCustomerControl()->get($idCustomer);
		$this->getCustomerPhoneControl()->getRelationships($customer);

		$result = new ApiResultPhones();
		$result->setPhones($customer->getPhones());
		$result->setMessage('encontrado %d telefones para "%s"', $customer->getPhones()->size(), $customer->getFantasyName());

		return $result;
	}
}

