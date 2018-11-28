<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;
use tercom\entities\CustomerEmployee;

/**
 * @author Andrew
 */
class CustomerEmployeeService extends DefaultSiteService
{
	/**
	 *
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$customerProfile = $this->getCustomerProfileControl()->get($post->getInt('idCustomerProfile'));

		$customerEmployee = new CustomerEmployee();
		$customerEmployee->setCustomerProfile($customerProfile);
		$customerEmployee->setName($post->getString('name'));
		$customerEmployee->setEmail($post->getString('email'));
		$customerEmployee->setPassword($post->getString('password'), false);
		$customerEmployee->setEnable($post->getBoolean('enable'));
		$this->getCustomerEmployeeControl()->add($customerEmployee);

		$result = new ApiResultObject();
		$result->setObject($customerEmployee);
		$result->setMessage('funcionário de cliente "%s" adicionado com êxito', $customerEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post","params":["idCustomerEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idCustomerEmployee = $content->getParameters()->getInt('idCustomerEmployee');
		$customerEmployee = $this->getCustomerEmployeeControl()->get($idCustomerEmployee);

		if ($post->isSetted('idCustomerProfile'))
			$customerProfile = $this->getCustomerProfileControl()->get($post->getInt('idCustomerProfile'));
		else
			$customerProfile = null;

		if ($post->isSetted('name')) $customerEmployee->setName($post->getString('name'));
		if ($post->isSetted('email')) $customerEmployee->setEmail($post->getString('email'));
		if ($post->isSetted('password')) $customerEmployee->setPassword($post->getString('password'), false);
		if ($post->isSetted('phone')) $customerEmployee->setPhone($post->getInt('phone'));
		if ($post->isSetted('cellphone')) $customerEmployee->setCellphone($post->getInstance('cellphone'));
		if ($post->isSetted('enable')) $customerEmployee->setEnable($post->getBoolean('enable'));

		$this->getCustomerEmployeeControl()->set($customerEmployee, $customerProfile);

		$result = new ApiResultObject();
		$result->setObject($customerEmployee);
		$result->setMessage('funcionário de cliente "%s" atualizado com êxito', $customerEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post","params":["idCustomerEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionEnable(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idCustomerEmployee = $content->getParameters('idCustomerEmployee');
		$customerEmployee = $this->getCustomerEmployeeControl()->get($idCustomerEmployee);
		$customerEmployee->setEnable($post->getBoolean('enable'));

		$this->getCustomerEmployeeControl()->setEnabled($customerEmployee);

		$result = new ApiResultObject();
		$result->setObject($customerEmployee);

		if ($customerEmployee->isEnable())
			$result->setMessage('funcionário de cliente "%s" habilitado com êxito', $customerEmployee->getName());
		else
			$result->setMessage('funcionário de cliente "%s" desabilitado com êxito', $customerEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["idCustomerEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idCustomerEmployee = $content->getParameters()->getInt('idCustomerEmployee');
		$customerEmployee = $this->getCustomerEmployeeControl()->get($idCustomerEmployee);

		$result = new ApiResultObject();
		$result->setObject($customerEmployee);
		$result->setMessage('funcionário de cliente "%s" obtido com êxito', $customerEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$customerEmployees = $this->getCustomerEmployeeControl()->getAll();

		$result = new ApiResultObject();
		$result->setObject($customerEmployees);
		$result->setMessage('encontrado um total de %d funcionários de clientes no banco', $customerEmployees->size());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["assignmentLevel"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetByAssignmentLevel(ApiContent $content): ApiResultObject
	{
		$assignmentLevel = $content->getParameters()->getInt('assignmentLevel');
		$customerEmployees = $this->getCustomerEmployeeControl()->getByAssignmentLevel($assignmentLevel);

		$result = new ApiResultObject();
		$result->setObject($customerEmployees);
		$result->setMessage('encontrado %d funcionários de clientes por nível de permissão', $customerEmployees->size());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetByCustomer(ApiContent $content): ApiResultObject
	{
		$idCustomer = $content->getParameters()->getInt('idCustomer');
		$customer = $this->getCustomerControl()->get($idCustomer);
		$customerEmployees = $this->getCustomerEmployeeControl()->getByCustomer($customer);

		$result = new ApiResultObject();
		$result->setObject($customerEmployees);
		$result->setMessage('encontrado %d funcionários no cliente "%s"', $customerEmployees->size(), $customer->getFantasyName());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["idCustomerProfile"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetByProfile(ApiContent $content): ApiResultObject
	{
		$idCustomerProfile = $content->getParameters()->getInt('idCustomerProfile');
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile);
		$customerEmployees = $this->getCustomerEmployeeControl()->getByCustomerProfile($customerProfile);

		$result = new ApiResultObject();
		$result->setObject($customerEmployees);
		$result->setMessage('encontrado %d funcionários no perfil "%s"', $customerEmployees->size(), $customerProfile->getName());

		return $result;
	}
}

