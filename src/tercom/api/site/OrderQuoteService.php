<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;

/**
 * @author Andrew
 */
class OrderQuoteService extends DefaultSiteService
{
	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderRequest"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionQuote(ApiContent $content): ApiResultObject
	{
		$idOrderRequest = $content->getParameters()->getInt('idOrderRequest');
		$orderRequest = $this->getOrderRequestControl()->get($idOrderRequest);
		$orderQuote = $this->getOrderQuoteControl()->openQuoting($orderRequest, $this->getTercomEmployee());

		$result = new ApiResultObject();
		$result->setResult($orderQuote, 'cotação do pedido de número %d gerado com êxito', $orderRequest->getId());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderQuote"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionQuoted(ApiContent $content): ApiResultObject
	{
		$idOrderQuote = $content->getParameters()->getInt('idOrderQuote');
		$orderQuote = $this->getOrderQuoteControl()->get($idOrderQuote);
		$this->getOrderQuoteControl()->closeQuoting($orderQuote);

		$result = new ApiResultObject();
		$result->setResult($orderQuote, 'cotação do pedido de número %d concluída com êxito', $orderQuote->getOrderRequest()->getId());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idOrderQuote"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idOrderQuote = $content->getParameters()->getInt('idOrderQuote');
		$orderQuote = $this->getOrderQuoteControl()->get($idOrderQuote);

		$result = new ApiResultObject();
		$result->setResult($orderQuote, 'cotação do pedido de número %d obtido com êxito', $orderQuote->getOrderRequest()->getId());

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
		if ($this->getCustomerEmployeeNull() === null)
		{
			$idCustomer = $content->getParameters()->getInt('idCustomer');
			$customer = $this->getCustomerControl()->get($idCustomer);
		}
		else
			$customer = $this->getLoginCustomerControl()->getCustomerLogged();

		$orderQuotes = $this->getOrderQuoteControl()->getByCustomer($customer);

		$result = new ApiResultObject();
		$result->setResult($orderQuotes, 'encontrado %d cotações de pedidos feitas por "%s"', $orderQuotes->size(), $customer->getFantasyName());

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
		$parameters = $content->getParameters();

		if ($parameters->isSetted('idCustomerEmployee'))
		{
			$idCustomerEmployee = $parameters->getInt('idCustomerEmployee');
			$customerEmployee = $this->getCustomerEmployeeControl()->get($idCustomerEmployee);
		}
		else
			$customerEmployee = $this->getCustomerEmployee();

		$orderQuotes = $this->getOrderQuoteControl()->getByCustomerEmployee($customerEmployee);

		$result = new ApiResultObject();
		$result->setResult($orderQuotes, 'encontrado %d cotações de pedidos feitas por "%s"', $orderQuotes->size(), $customerEmployee->getName());

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
		$parameters = $content->getParameters();

		if ($parameters->isSetted('idTercomEmployee'))
		{
			$idTercomEmployee = $parameters->getInt('idTercomEmployee');
			$tercomEmployee = $this->getTercomEmployeeControl()->get($idTercomEmployee);
		}
		else
			$tercomEmployee = $this->getTercomEmployee();

		$orderQuotes = $this->getOrderQuoteControl()->getByTercomEmployee($tercomEmployee);

		$result = new ApiResultObject();
		$result->setResult($orderQuotes, 'encontrado %d cotações de pedidos feitas por "%s"', $orderQuotes->size(), $tercomEmployee->getName());

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
		$orderQuotes = $this->getOrderQuoteControl()->getAll();

		$result = new ApiResultObject();
		$result->setResult($orderQuotes, 'encontrado %d cotações de pedidos no banco de dados', $orderQuotes->size());

		return $result;
	}
}

