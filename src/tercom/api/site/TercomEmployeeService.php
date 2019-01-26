<?php

namespace tercom\api\site;

use dProject\Primitive\StringUtil;
use dProject\restful\ApiContent;
use tercom\api\exceptions\FilterException;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\api\site\results\ApiResultTercomEmployeeSettings;
use tercom\entities\TercomEmployee;

/**
 * @author Andrew
 */
class TercomEmployeeService extends DefaultSiteService
{
	/**
	 *
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content
	 * @return ApiResultTercomEmployeeSettings
	 */
	public function actionSettings(ApiContent $content): ApiResultTercomEmployeeSettings
	{
		return new ApiResultTercomEmployeeSettings();
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
		$tercomProfile = $this->getTercomProfileControl()->get($post->getInt('idTercomProfile'));

		$tercomEmployee = new TercomEmployee();
		$tercomEmployee->setTercomProfile($tercomProfile);
		$tercomEmployee->setCpf($post->getString('cpf'));
		$tercomEmployee->setName($post->getString('name'));
		$tercomEmployee->setEmail($post->getString('email'));
		$tercomEmployee->setPassword($post->getString('password'), false);
		$tercomEmployee->setEnabled(!$post->isSetted('enabled') || $post->getBoolean('enabled'));
		$this->getTercomEmployeeControl()->add($tercomEmployee);

		$result = new ApiResultObject();
		$result->setResult($tercomEmployee, 'funcionário da TERCOM "%s" adicionado com êxito', $tercomEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idTercomEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idTercomEmployee = $content->getParameters()->getInt('idTercomEmployee');
		$tercomEmployee = $this->getTercomEmployeeControl()->get($idTercomEmployee);

		if ($post->isSetted('idTercomProfile'))
			$tercomProfile = $this->getTercomProfileControl()->get($post->getInt('idTercomProfile'));
		else
			$tercomProfile = null;

		if ($post->isSetted('cpf')) $tercomEmployee->setName($post->getString('cpf'));
		if ($post->isSetted('name')) $tercomEmployee->setName($post->getString('name'));
		if ($post->isSetted('email')) $tercomEmployee->setEmail($post->getString('email'));
		if ($post->isSetted('phone')) $tercomEmployee->setPhone($post->getInt('phone'));
		if ($post->isSetted('cellphone')) $tercomEmployee->setCellphone($post->getInstance('cellphone'));
		if ($post->isSetted('enabled')) $tercomEmployee->setEnabled($post->getBoolean('enable'));
		if ($post->isSetted('password') && !StringUtil::isEmpty($post->getString('password'))) $tercomEmployee->setPassword($post->getString('password'), false);

		$this->getTercomEmployeeControl()->set($tercomEmployee, $tercomProfile);

		$result = new ApiResultObject();
		$result->setResult($tercomEmployee, 'funcionário da TERCOM "%s" atualizado com êxito', $tercomEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idTercomEmployee","enabled"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionEnable(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idTercomEmployee = $parameters->getInt('idTercomEmployee');
		$tercomEmployee = $this->getTercomEmployeeControl()->get($idTercomEmployee);
		$tercomEmployee->setEnabled(($enable = $parameters->getBoolean('enabled')));
		$this->getTercomEmployeeControl()->setEnabled($tercomEmployee);
		$enabled = $enable ? 'habilitado' : 'desabilitado';

		$result = new ApiResultObject();
		$result->setResult($tercomEmployee, 'funcionário da TERCOM "%s" %s com êxito', $tercomEmployee->getName(), $enabled);

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idTercomEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idTercomEmployee = $content->getParameters()->getInt('idTercomEmployee');
		$tercomEmployee = $this->getTercomEmployeeControl()->get($idTercomEmployee);

		$result = new ApiResultObject();
		$result->setResult($tercomEmployee, 'funcionário da TERCOM "%s" obtido com êxito', $tercomEmployee->getName());

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
		$tercomEmployees = $this->getTercomEmployeeControl()->getAll();

		$result = new ApiResultObject();
		$result->setResult($tercomEmployees, 'encontrado um total de %d funcionários da TERCOM no banco de dados', $tercomEmployees->size());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idTercomProfile"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetByProfile(ApiContent $content): ApiResultObject
	{
		$idTercomProfile = $content->getParameters()->getInt('idTercomProfile');
		$tercomProfile = $this->getTercomProfileControl()->get($idTercomProfile);
		$tercomEmployees = $this->getTercomEmployeeControl()->getByTercomProfile($tercomProfile);

		$result = new ApiResultObject();
		$result->setResult($tercomEmployees, 'encontrado %d funcionários da TERCOM no perfil "%s"', $tercomEmployees->size(), $tercomProfile->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["filter","value","idTercomEmployee"]})
	 * @param ApiContent $content
	 * @throws FilterException
	 * @return ApiResultSimpleValidation
	 */
	public function actionAvaiable(ApiContent $content): ApiResultSimpleValidation
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'cpf': return $this->avaiableCpf($content);
			case 'email': return $this->avaiableEmail($content);
		}

		throw new FilterException($filter);
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultSimpleValidation
	 */
	private function avaiableCpf(ApiContent $content): ApiResultSimpleValidation
	{
		$parameters = $content->getParameters();
		$cpf = $parameters->getString('value');
		$idTercomEmployee = $this->parseNullToInt($parameters->getInt('idTercomEmployee', false));
		$avaiable = $this->getTercomEmployeeControl()->avaiableCpf($cpf, $idTercomEmployee);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'CPF "%s" %s', $cpf, $this->getMessageAvaiable($avaiable));

		return $result;
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
		$idTercomEmployee = $this->parseNullToInt($parameters->getInt('idTercomEmployee', false));
		$avaiable = $this->getTercomEmployeeControl()->avaiableEmail($email, $idTercomEmployee);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'endereço de e-mail "%s" %s', $email, $this->getMessageAvaiable($avaiable));

		return $result;
	}
}

