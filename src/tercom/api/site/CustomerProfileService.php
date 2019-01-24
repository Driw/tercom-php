<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultCustomerProfileSettings;
use tercom\entities\CustomerProfile;

/**
 * @see DefaultSiteService
 * @author Andrew
 */
class CustomerProfileService extends DefaultSiteService
{
	/**
	 *
	 * @ApiPermissionAnnotation({})
	 * @return ApiResultCustomerProfileSettings
	 */
	public function actionSettings(): ApiResultCustomerProfileSettings
	{
		return new ApiResultCustomerProfileSettings();
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
		$idCustomer = $post->getInt('idCustomer');
		$customer = $this->getCustomerControl()->get($idCustomer);

		$customerProfile = new CustomerProfile();
		$customerProfile->setCustomer($customer);
		$customerProfile->setName($post->getString('name'));
		$customerProfile->setAssignmentLevel($post->getInt('assignmentLevel'));
		$this->getCustomerProfileControl()->add($customerProfile, $this->getCurrentAssignmentLevel());

		$result = new ApiResultObject();
		$result->setResult($customerProfile, 'perfil de cliente "%s" adicionado com êxito', $customerProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idCustomerProfile"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idCustomerProfile = $content->getParameters()->getInt('idCustomerProfile');
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile, true, $this->getCurrentAssignmentLevel());
		$customer = $this->getCustomerControl()->get($customerProfile->getCustomerId());
		$customerProfile->setCustomer($customer);

		if ($post->isSetted('name')) $customerProfile->setName($post->getString('name'));
		if ($post->isSetted('assignmentLevel')) $customerProfile->setAssignmentLevel($post->getInt('assignmentLevel'));

		$this->getCustomerProfileControl()->set($customerProfile, $this->getCurrentAssignmentLevel());

		$result = new ApiResultObject();
		$result->setResult($customerProfile, 'perfil de cliente "%s" atualizado com êxito', $customerProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idCustomerProfile"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$idCustomerProfile = $content->getParameters()->getInt('idCustomerProfile');
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile, true, $this->getCurrentAssignmentLevel());
		$this->getCustomerProfileControl()->remove($customerProfile, $this->getCurrentAssignmentLevel());

		$result = new ApiResultObject();
		$result->setResult($customerProfile, 'perfil de cliente "%s" excluído com êxito', $customerProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idCustomerProfile"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idCustomerProfile = $content->getParameters()->getInt('idCustomerProfile');
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile, true, $this->getCurrentAssignmentLevel());

		$result = new ApiResultObject();
		$result->setResult($customerProfile, 'perfil de cliente "%s" obtido com êxito', $customerProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionCustomer(ApiContent $content): ApiResultObject
	{
		$idCustomer = $content->getParameters()->getInt('idCustomer');
		$customer = $this->getCustomerControl()->get($idCustomer);
		$customerProfiles = $this->getCustomerProfileControl()->getByCustomer($customer);

		$result = new ApiResultObject();
		$result->setResult($customerProfiles, 'encontrado %d perfis no cliente "%s"', $customerProfiles->size(), $customer->getFantasyName());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$customerProfiles = $this->getCustomerProfileControl()->getAll();

		$result = new ApiResultObject();
		$result->setResult($customerProfiles, 'encontrados %d perfis de cliente no banco de dados', $customerProfiles->size());

		return $result;
	}
}

