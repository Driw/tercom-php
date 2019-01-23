<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;
use tercom\entities\OrderAcceptance;

/**
 *
 *
 * @see DefaultSiteService
 * @see ApiResultObject
 *
 * @author Andrew
 */
class OrderAcceptanceService extends DefaultSiteService
{
	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderQuote"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idAddress = $post->getInt('idAddress');
		$idOrderQuote = $content->getParameters()->getInt('idOrderQuote');
		$orderQuote = $this->getOrderQuoteControl()->get($idOrderQuote);
		$customer = $this->getCustomerEmployee()->getCustomerProfile()->getCustomer();
		$address = $this->getCustomerAddressControl()->getRelationship($customer, $idAddress);

		$orderAcceptance = new OrderAcceptance();
		$orderAcceptance->getAddress()->clone($address);
		$orderAcceptance->setOrderQuote($orderQuote);
		$orderAcceptance->setCustomerEmployee($orderQuote->getOrderRequest()->getCustomerEmployee());
		$orderAcceptance->setTercomEmployee($orderQuote->getOrderRequest()->getTercomEmployee());
		$orderAcceptance->setObservations($post->getString('observations', false));

		$this->getOrderAcceptanceControl()->add($orderAcceptance);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptance, 'aceitação de pedido adicionada com êxito');

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idOrderAcceptance = $content->getParameters()->getInt('idOrderAcceptance');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance);
		$orderAcceptance->setObservations($post->getString('observations', false));

		$this->getOrderAcceptanceControl()->set($orderAcceptance);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptance, 'aceitação de pedido atualizada com êxito');

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance","idAddress"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSetAddress(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$parameters = $content->getParameters();
		$idOrderAcceptance = $content->getParameters()->getInt('idOrderAcceptance');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance);

		if ($parameters->isSetted('idAddress'))
		{
			$idAddress = $content->getParameters()->getInt('idAddress');
			$address = $this->getAddressControl()->get($idAddress);
			$orderAcceptance->getAddress()->clone($address);
		}

		$address = $orderAcceptance->getAddress();

		if ($post->isSetted('state')) $address->setState($post->getString('state'));
		if ($post->isSetted('city')) $address->setCity($post->getString('city'));
		if ($post->isSetted('cep')) $address->setCep($post->getString('cep'));
		if ($post->isSetted('neighborhood')) $address->setNeighborhood($post->getString('neighborhood'));
		if ($post->isSetted('street')) $address->setStreet($post->getString('street'));
		if ($post->isSetted('number')) $address->setNumber($post->getInt('number'));
		if ($post->isSetted('complement')) $address->setComplement($post->getString('complement'));

		$this->getOrderAcceptanceControl()->setAddress($orderAcceptance);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptance, 'endereço da aceitação de pedido atualizada com êxito');

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idOrderAcceptance = $content->getParameters()->getInt('idOrderAcceptance');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance, true);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptance, 'aceitação de pedido obtida com êxito');

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderQuote"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetByOrderQuote(ApiContent $content): ApiResultObject
	{
		$idOrderQuote = $content->getParameters()->getInt('idOrderQuote');
		$orderQuote = $this->getOrderQuoteControl()->get($idOrderQuote);
		$orderAcceptance = $this->getOrderAcceptanceControl()->getByOrderQuote($orderQuote, true);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptance, 'aceitação de pedido obtida com êxito');

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
		$idCustomer = $content->getParameters()->getInt('idCustomer', false);
		$customer = $idCustomer === null ? $this->getOrderAcceptanceControl()->getCustomerLogged() : $this->getCustomerControl()->get($idCustomer);
		$orderAcceptances = $this->getOrderAcceptanceControl()->getByCustomer($customer);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptances, 'encontrado %d aceitações de pedido do cliente "%s"', $orderAcceptances->size(), $customer->getFantasyName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idCustomerEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetByCustomerEmployee(ApiContent $content): ApiResultObject
	{
		$idCustomerEmployee = $content->getParameters()->getInt('idCustomerEmployee', false);
		$customerEmployee = $idCustomerEmployee === null ? $this->getCustomerEmployee() : $this->getCustomerEmployeeControl()->get($idCustomerEmployee);
		$orderAcceptances = $this->getOrderAcceptanceControl()->getByCustomerEmployee($customerEmployee);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptances, 'encontrado %d aceitações de pedido feitas por "%s"', $orderAcceptances->size(), $customerEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idTercomEmployee"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetByTercomEmployee(ApiContent $content): ApiResultObject
	{
		$idTercomEmployee = $content->getParameters()->getInt('idTercomEmployee', false);
		$tercomEmployee = $idTercomEmployee === null ? $this->getTercomEmployee() : $this->getTercomEmployeeControl()->get($idTercomEmployee);
		$orderAcceptances = $this->getOrderAcceptanceControl()->getByTercomEmployee($tercomEmployee);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptances, 'encontrado %d aceitações de pedido cotadas por "%s"', $orderAcceptances->size(), $tercomEmployee->getName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionApprove(ApiContent $content): ApiResultObject
	{
		$idOrderAcceptance = $content->getParameters()->getInt('idOrderAcceptance');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance, true);
		$this->getOrderAcceptanceControl()->approved($orderAcceptance);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptance, 'aceitação de pedido atualizado para "%s"', $orderAcceptance->getStatusDescription());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionRequest(ApiContent $content): ApiResultObject
	{
		$idOrderAcceptance = $content->getParameters()->getInt('idOrderAcceptance');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance, true);
		$this->getOrderAcceptanceControl()->request($orderAcceptance);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptance, 'aceitação de pedido atualizado para "%s"', $orderAcceptance->getStatusDescription());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionPay(ApiContent $content): ApiResultObject
	{
		$idOrderAcceptance = $content->getParameters()->getInt('idOrderAcceptance');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance, true);
		$this->getOrderAcceptanceControl()->paid($orderAcceptance);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptance, 'aceitação de pedido atualizado para "%s"', $orderAcceptance->getStatusDescription());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderAcceptance"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionDeliver(ApiContent $content): ApiResultObject
	{
		$idOrderAcceptance = $content->getParameters()->getInt('idOrderAcceptance');
		$orderAcceptance = $this->getOrderAcceptanceControl()->get($idOrderAcceptance, true);
		$this->getOrderAcceptanceControl()->onTheWay($orderAcceptance);

		$result = new ApiResultObject();
		$result->setResult($orderAcceptance, 'aceitação de pedido atualizado para "%s"', $orderAcceptance->getStatusDescription());

		return $result;
	}
}

