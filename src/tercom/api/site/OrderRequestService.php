<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultOrderRequestSettings;
use tercom\dao\OrderRequestDAO;
use tercom\entities\OrderRequest;

/**
 * @author Andrew
 */
class OrderRequestService extends DefaultSiteService
{
	/**
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content
	 * @return ApiResultOrderRequestSettings
	 */
	public function actionSettings(ApiContent $content): ApiResultOrderRequestSettings
	{
		return new ApiResultOrderRequestSettings();
	}

	/**
	 * @ApiPermissionAnnotation({"method":"post"})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$customerEmployee = $this->getCustomerEmployeeLogin($content)->getCustomerEmployee();

		$orderRequest = new OrderRequest();
		$orderRequest->setBudget($post->getFloat('budget'));
		$orderRequest->setCustomerEmployee($customerEmployee);

		if ($post->isSetted('expiration')) $orderRequest->setExpiration(new \DateTime($post->getString('expiration')));

		$this->getOrderRequestControl()->add($orderRequest);

		$result = new ApiResultObject();
		$result->setResult($orderRequest, 'pedido de código %d efetuado com êxito', $orderRequest->getId());

		return $result;
	}

	/**
	 * @ApiPermissionAnnotation({"method":"post","params":["idOrderRequest"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$customerEmployee = $this->getCustomerEmployeeLogin($content)->getCustomerEmployee();
		$idOrderRequest = $content->getParameters()->getInt('idOrderRequest');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest, $customerEmployee);

		if ($post->isSetted('budget')) $orderRequest->setBudget($post->getFloat('budget'));
		if ($post->isSetted('expiration')) $orderRequest->setExpiration(new \DateTime($post->getString('expiration')));

		$this->getOrderRequestControl()->set($orderRequest);

		$result = new ApiResultObject();
		$result->setResult($orderRequest, 'pedido de código %d atualizado com êxito', $orderRequest->getId());

		return $result;
	}

	/**
	 * @ApiPermissionAnnotation({"params":["idOrderRequest"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$customerEmployee = $this->getCustomerEmployeeLogin($content)->getCustomerEmployee();
		$idOrderRequest = $content->getParameters()->getInt('idOrderRequest');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest, $customerEmployee);

		$result = new ApiResultObject();
		$result->setResult($orderRequest, 'pedido de código %d obtido com êxito', $orderRequest->getId());

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$mode = $this->parseInt($content->getPost()->getInt('mode', false), OrderRequestDAO::SELECT_MODE_ALL);
		$orderRequests = $this->getOrderRequestControl()->getAll($mode);

		$result = new ApiResultObject();
		$result->setResult($orderRequests, 'encontrado %d solicitações de pedidos no banco de dados', $orderRequests->size());

		return $result;
	}

	/**
	 * @ApiPermissionAnnotation({"params":["idCustomerEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetByCustomer(ApiContent $content): ApiResultObject
	{
		$idCustomerEmployee = $content->getParameters()->getInt('idCustomerEmployee', false);

		if ($idCustomerEmployee === null)
			$customerEmployee = $this->getCustomerEmployeeLogin($content)->getCustomerEmployee();
		else
			$customerEmployee = $this->getCustomerEmployeeControl()->get($idCustomerEmployee);

		$mode = $this->parseInt($content->getPost()->getInt('mode', false), OrderRequestDAO::SELECT_MODE_ALL);
		$orderRequests = $this->getOrderRequestControl()->getByCustomerEmployee($customerEmployee, $mode);

		$result = new ApiResultObject();
		$result->setResult($orderRequests, 'encontrado %d solicitações de pedidos feitas por "%s"', $orderRequests->size(), $customerEmployee->getName());

		return $result;
	}

	/**
	 * @ApiPermissionAnnotation({"params":["idTercomEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetByTercom(ApiContent $content): ApiResultObject
	{
		$idTercomEmployee = $content->getParameters()->getInt('idTercomEmployee', false);

		if ($idTercomEmployee === null)
			$tercomEmployee = $this->getTercomEmployeeLogin($content)->getTercomEmployee();
		else
			$tercomEmployee = $this->getTercomEmployeeControl()->get($idTercomEmployee);

		$mode = $this->parseInt($content->getPost()->getInt('mode', false), OrderRequestDAO::SELECT_MODE_ALL);
		$orderRequests = $this->getOrderRequestControl()->getByTercomEmployee($tercomEmployee, $mode);

		$result = new ApiResultObject();
		$result->setResult($orderRequests, 'encontrado %d solicitações de pedidos feitas por "%s"', $orderRequests->size(), $tercomEmployee->getName());

		return $result;
	}
}

