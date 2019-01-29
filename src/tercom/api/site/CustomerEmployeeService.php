<?php

namespace tercom\api\site;

use dProject\Primitive\ArrayData;
use dProject\Primitive\StringUtil;
use dProject\restful\ApiContent;
use tercom\api\exceptions\FilterException;
use tercom\api\site\results\ApiResultCustomerEmployeeSettings;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\entities\CustomerEmployee;

/**
 * @author Andrew
 */
class CustomerEmployeeService extends DefaultSiteService
{
	/**
	 *
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content
	 * @return ApiResultCustomerEmployeeSettings
	 */
	public function actionSettings(ApiContent $content): ApiResultCustomerEmployeeSettings
	{
		return new ApiResultCustomerEmployeeSettings();
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
		$customerProfile = $this->getCustomerProfileControl()->get($post->getInt('idCustomerProfile'), false, $this->getCurrentAssignmentLevel());

		$customerEmployee = new CustomerEmployee();
		$customerEmployee->setCustomerProfile($customerProfile);
		$customerEmployee->setName($post->getString('name'));
		$customerEmployee->setEmail($post->getString('email'));
		$customerEmployee->setPassword($post->getString('password'), false);
		$customerEmployee->setEnabled(!$post->isSetted('enabled') || $post->getBoolean('enable'));
		$this->getCustomerEmployeeControl()->add($customerEmployee, $this->getCurrentAssignmentLevel());

		$result = new ApiResultObject();
		$result->setResult($customerEmployee, 'funcionário de cliente "%s" adicionado com êxito', $customerEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idCustomerEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idCustomerEmployee = $content->getParameters()->getInt('idCustomerEmployee');
		$customerEmployee = $this->getCustomerEmployeeControl()->get($idCustomerEmployee);

		if ($post->isSetted('idCustomerProfile'))
			$customerProfile = $this->getCustomerProfileControl()->get($post->getInt('idCustomerProfile'), false, $this->getCurrentAssignmentLevel());
		else
			$customerProfile = null;

		if ($post->isSetted('name')) $customerEmployee->setName($post->getString('name'));
		if ($post->isSetted('email')) $customerEmployee->setEmail($post->getString('email'));
		if ($post->isSetted('enable')) $customerEmployee->setEnabled($post->getBoolean('enable'));
		if ($post->isSetted('password') && !StringUtil::isEmpty($post->getString('password'))) $customerEmployee->setPassword($post->getString('password'), false);

		if ($post->isSetted('phone'))
		{
			$arrayPhone = $post->getArray('phone');
			$phone = new ArrayData($arrayPhone);
			$customerEmployee->getPhone()->setDDD($phone->getInt('ddd'));
			$customerEmployee->getPhone()->setNumber($phone->getString('number'));
			$customerEmployee->getPhone()->setType($phone->getString('type'));
		}

		if ($post->isSetted('cellphone'))
		{
			$arrayCellphone = $post->getArray('cellphone');
			$cellphone = new ArrayData($arrayCellphone);
			$customerEmployee->getCellphone()->setDDD($cellphone->getInt('ddd'));
			$customerEmployee->getCellphone()->setNumber($cellphone->getString('number'));
			$customerEmployee->getCellphone()->setType($cellphone->getString('type'));
		}

		$this->getCustomerEmployeeControl()->set($customerEmployee, $customerProfile, $this->getCurrentAssignmentLevel());

		$result = new ApiResultObject();
		$result->setResult($customerEmployee, 'funcionário de cliente "%s" atualizado com êxito', $customerEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idCustomerEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemovePhone(ApiContent $content): ApiResultObject
	{
		$idCustomerEmployee = $content->getParameters()->getInt('idCustomerEmployee');
		$customerEmployee = $this->getCustomerEmployeeControl()->get($idCustomerEmployee);
		$this->getCustomerEmployeeControl()->removePhone($customerEmployee);

		$result = new ApiResultObject();
		$result->setResult($customerEmployee, 'telefone excluído com êxito', $customerEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idCustomerEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRemoveCellphone(ApiContent $content): ApiResultObject
	{
		$idCustomerEmployee = $content->getParameters()->getInt('idCustomerEmployee');
		$customerEmployee = $this->getCustomerEmployeeControl()->get($idCustomerEmployee);
		$this->getCustomerEmployeeControl()->removeCellphone($customerEmployee);

		$result = new ApiResultObject();
		$result->setResult($customerEmployee, 'telefone celular excluído com êxito', $customerEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idCustomerEmployee","enable"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionEnable(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idCustomerEmployee = $parameters->getInt('idCustomerEmployee');
		$customerEmployee = $this->getCustomerEmployeeControl()->get($idCustomerEmployee);
		$customerEmployee->setEnabled(($enable = $parameters->getBoolean('enable')));
		$this->getCustomerEmployeeControl()->setEnabled($customerEmployee);
		$enabled = $enable ? 'habilitado' : 'desabilitado';

		$result = new ApiResultObject();
		$result->setResult($customerEmployee, 'funcionário de cliente "%s" %s com êxito', $customerEmployee->getName(), $enabled);

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idCustomerEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idCustomerEmployee = $content->getParameters()->getInt('idCustomerEmployee');
		$customerEmployee = $this->getCustomerEmployeeControl()->get($idCustomerEmployee);

		$result = new ApiResultObject();
		$result->setResult($customerEmployee, 'funcionário de cliente "%s" obtido com êxito', $customerEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$customerEmployees = $this->getCustomerEmployeeControl()->getAll();

		$result = new ApiResultObject();
		$result->setResult($customerEmployees, 'encontrado um total de %d funcionários de clientes no banco', $customerEmployees->size());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetByCustomer(ApiContent $content): ApiResultObject
	{
		$idCustomer = $content->getParameters()->getInt('idCustomer');
		$customer = $this->getCustomerControl()->get($idCustomer);
		$customerEmployees = $this->getCustomerEmployeeControl()->getByCustomer($customer);

		$result = new ApiResultObject();
		$result->setResult($customerEmployees, 'encontrado %d funcionários no cliente "%s"', $customerEmployees->size(), $customer->getFantasyName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idCustomerProfile"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetByProfile(ApiContent $content): ApiResultObject
	{
		$idCustomerProfile = $content->getParameters()->getInt('idCustomerProfile');
		$customerProfile = $this->getCustomerProfileControl()->get($idCustomerProfile, false, $this->getCurrentAssignmentLevel());
		$customerEmployees = $this->getCustomerEmployeeControl()->getByCustomerProfile($customerProfile);

		$result = new ApiResultObject();
		$result->setResult($customerEmployees, 'encontrado %d funcionários no perfil "%s"', $customerEmployees->size(), $customerProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["filter","value","idCustomerEmployee"]})
	 * @param ApiContent $content
	 * @throws FilterException
	 * @return ApiResultSimpleValidation
	 */
	public function actionAvaiable(ApiContent $content): ApiResultSimpleValidation
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'email': return $this->avaiableEmail($content);
		}

		throw new FilterException($filter);
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultSimpleValidation
	 */
	private function avaiableEmail(ApiContent $content): ApiResultSimpleValidation
	{
		$parameters = $content->getParameters();
		$email = $parameters->getString('value');
		$idCustomerEmployee = $this->parseNullToInt($parameters->getInt('idCustomerEmployee', false));
		$avaiable = $this->getCustomerEmployeeControl()->avaiableEmail($email, $idCustomerEmployee);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'endereço de e-mail "%s" %s', $email, $this->getMessageAvaiable($avaiable));

		return $result;
	}
}

