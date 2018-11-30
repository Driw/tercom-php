<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;
use tercom\entities\TercomEmployee;
use tercom\api\site\results\ApiResultTercomEmployeeSettings;

/**
 * @author Andrew
 */
class TercomEmployeeService extends DefaultSiteService
{
	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultTercomEmployeeSettings
	 */
	public function actionSettings(ApiContent $content): ApiResultTercomEmployeeSettings
	{
		return new ApiResultTercomEmployeeSettings();
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post"})
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
		$tercomEmployee->setEnable($post->getBoolean('enable'));
		$this->getTercomEmployeeControl()->add($tercomEmployee);

		$result = new ApiResultObject();
		$result->setObject($tercomEmployee);
		$result->setMessage('funcionário da TERCOM "%s" adicionado com êxito', $tercomEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post","params":["idTercomEmployee"]})
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
		if ($post->isSetted('password')) $tercomEmployee->setPassword($post->getString('password'), false);
		if ($post->isSetted('phone')) $tercomEmployee->setPhone($post->getInt('phone'));
		if ($post->isSetted('cellphone')) $tercomEmployee->setCellphone($post->getInstance('cellphone'));
		if ($post->isSetted('enable')) $tercomEmployee->setEnable($post->getBoolean('enable'));

		$this->getTercomEmployeeControl()->set($tercomEmployee, $tercomProfile);

		$result = new ApiResultObject();
		$result->setObject($tercomEmployee);
		$result->setMessage('funcionário da TERCOM "%s" atualizado com êxito', $tercomEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post","params":["idTercomEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionEnable(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idTercomEmployee = $content->getParameters('idTercomEmployee');
		$tercomEmployee = $this->getTercomEmployeeControl()->get($idTercomEmployee);
		$tercomEmployee->setEnable($post->getBoolean('enable'));

		$this->getTercomEmployeeControl()->setEnabled($tercomEmployee);

		$result = new ApiResultObject();
		$result->setObject($tercomEmployee);

		if ($tercomEmployee->isEnable())
			$result->setMessage('funcionário da TERCOM "%s" habilitado com êxito', $tercomEmployee->getName());
		else
			$result->setMessage('funcionário da TERCOM "%s" desabilitado com êxito', $tercomEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["idTercomEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idTercomEmployee = $content->getParameters()->getInt('idTercomEmployee');
		$tercomEmployee = $this->getTercomEmployeeControl()->get($idTercomEmployee);

		$result = new ApiResultObject();
		$result->setObject($tercomEmployee);
		$result->setMessage('funcionário da TERCOM "%s" obtido com êxito', $tercomEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$tercomEmployees = $this->getTercomEmployeeControl()->getAll();

		$result = new ApiResultObject();
		$result->setObject($tercomEmployees);
		$result->setMessage('encontrado um total de %d funcionários da TERCOM no banco de dados', $tercomEmployees->size());

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
		$tercomEmployees = $this->getTercomEmployeeControl()->getByAssignmentLevel($assignmentLevel);

		$result = new ApiResultObject();
		$result->setObject($tercomEmployees);
		$result->setMessage('encontrado %d funcionários da TERCOM por nível de permissão', $tercomEmployees->size());

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"params":["idTercomProfile"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetByProfile(ApiContent $content): ApiResultObject
	{
		$idTercomProfile = $content->getParameters()->getInt('idTercomProfile');
		$tercomProfile = $this->getTercomProfileControl()->get($idTercomProfile);
		$tercomEmployees = $this->getTercomEmployeeControl()->getByTercomProfile($tercomProfile);

		$result = new ApiResultObject();
		$result->setObject($tercomEmployees);
		$result->setMessage('encontrado %d funcionários da TERCOM no perfil "%s"', $tercomEmployees->size(), $tercomProfile->getName());

		return $result;
	}
}

