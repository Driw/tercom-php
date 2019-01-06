<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultCustomerProfile;
use tercom\api\site\results\ApiResultCustomerProfiles;
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
	 * @return ApiResultCustomerProfile
	 */
	public function actionAdd(ApiContent $content): ApiResultCustomerProfile
	{
		$post = $content->getPost();
		$idCustomer = $post->getInt('idCustomer');
		$customer = $this->getCustomerControl()->get($idCustomer);

		$customerProfile = new CustomerProfile();
		$customerProfile->setCustomer($customer);
		$customerProfile->setName($post->getString('name'));
		$customerProfile->setAssignmentLevel($post->getInt('assignmentLevel'));
		$this->getCustomerProfileControl()->add($customerProfile, $this->getCurrentAssignmentLevel());

		$result = new ApiResultCustomerProfile();
		$result->setCustomerProfile($customerProfile);
		$result->setMessage('perfil de cliente "%s" adicionado com êxito', $customerProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idCustomerProfile"]})
	 * @param ApiContent $content
	 * @return ApiResultCustomerProfile
	 */
	public function actionSet(ApiContent $content): ApiResultCustomerProfile
	{
		$post = $content->getPost();
		$idCustomerProfile = $content->getParameters()->getInt('idCustomerProfile');
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile, true, $this->getCurrentAssignmentLevel());
		$customer = $this->getCustomerControl()->get($customerProfile->getCustomerId());
		$customerProfile->setCustomer($customer);

		if ($post->isSetted('name')) $customerProfile->setName($post->getString('name'));
		if ($post->isSetted('assignmentLevel')) $customerProfile->setAssignmentLevel($post->getInt('assignmentLevel'));

		$this->getCustomerProfileControl()->set($customerProfile, $this->getCurrentAssignmentLevel());

		$result = new ApiResultCustomerProfile();
		$result->setCustomerProfile($customerProfile);
		$result->setMessage('perfil de cliente "%s" atualizado com êxito', $customerProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idCustomerProfile"]})
	 * @param ApiContent $content
	 * @return ApiResultCustomerProfile
	 */
	public function actionRemove(ApiContent $content): ApiResultCustomerProfile
	{
		$idCustomerProfile = $content->getParameters()->getInt('idCustomerProfile');
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile, true, $this->getCurrentAssignmentLevel());
		$this->getCustomerProfileControl()->remove($customerProfile, $this->getCurrentAssignmentLevel());

		$result = new ApiResultCustomerProfile();
		$result->setCustomerProfile($customerProfile);
		$result->setMessage('perfil de cliente "%s" excluído com êxito', $customerProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idCustomerProfile"]})
	 * @param ApiContent $content
	 * @return ApiResultCustomerProfile
	 */
	public function actionGet(ApiContent $content): ApiResultCustomerProfile
	{
		$idCustomerProfile = $content->getParameters()->getInt('idCustomerProfile');
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile, true, $this->getCurrentAssignmentLevel());

		$result = new ApiResultCustomerProfile();
		$result->setCustomerProfile($customerProfile);
		$result->setMessage('perfil de cliente "%s" obtido com êxito', $customerProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiResultCustomerProfiles
	 */
	public function actionCustomer(ApiContent $content): ApiResultCustomerProfiles
	{
		$idCustomer = $content->getParameters()->getInt('idCustomer');
		$customer = $this->getCustomerControl()->get($idCustomer);
		$customerProfiles = $this->getCustomerProfileControl()->getByCustomer($customer, $this->getCurrentAssignmentLevel());

		$result = new ApiResultCustomerProfiles();
		$result->setCustomerProfiles($customerProfiles);
		$result->setMessage('encontrado %d perfis de cliente', $customerProfiles->size());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultCustomerProfile
	 */
	public function actionGetAll(ApiContent $content): ApiResultCustomerProfiles
	{
		$customerProfiles = $this->getCustomerProfileControl()->getAll();

		$result = new ApiResultCustomerProfiles();
		$result->setCustomerProfiles($customerProfiles);
		$result->setMessage('encontrados %d perfis de cliente no banco de dados', $customerProfiles->size());

		return $result;
	}
}

