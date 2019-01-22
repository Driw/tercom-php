<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultCustomerSettings;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultSimpleValidation;
use tercom\api\exceptions\FilterException;
use tercom\entities\Customer;

/**
 * @author andrews
 */
class CustomerService extends DefaultSiteService
{
	/**
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content
	 * @return ApiResultCustomerSettings
	 */
	public function actionSettings(ApiContent $content): ApiResultCustomerSettings
	{
		return new ApiResultCustomerSettings();
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

		$customer = new Customer();
		$customer->setStateRegistry($post->getString('stateRegistry'));
		$customer->setCnpj($post->getString('cnpj'));
		$customer->setCompanyName($post->getString('companyName'));
		$customer->setFantasyName($post->getString('fantasyName'));
		$customer->setEmail($post->getString('email'));

		if ($post->isSetted('inactive')) $customer->setInactive($post->getBoolean('inactive'));

		$this->getCustomerControl()->add($customer);

		$result = new ApiResultObject();
		$result->setResult($customer, 'cliente "%s" de CNPJ "%s" adicionado com êxito', $customer->getFantasyName(), $customer->getCnpj());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idCustomer = $content->getParameters()->getInt('idCustomer');
		$customer = $this->getCustomerControl()->get($idCustomer);

		if ($post->isSetted('stateRegistry')) $customer->setStateRegistry($post->getString('stateRegistry'));
		if ($post->isSetted('cnpj')) $customer->setCnpj($post->getString('cnpj'));
		if ($post->isSetted('companyName')) $customer->setCompanyName($post->getString('companyName'));
		if ($post->isSetted('fantasyName')) $customer->setFantasyName($post->getString('fantasyName'));
		if ($post->isSetted('email')) $customer->setEmail($post->getString('email'));
		if ($post->isSetted('inactive')) $customer->setInactive($post->getBoolean('inactive'));

		$this->getCustomerControl()->set($customer);

		$result = new ApiResultObject();
		$result->setResult($customer, 'cliente "%s" de CNPJ "%s" atualizado com êxito', $customer->getFantasyName(), $customer->getCnpj());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idCustomer","inactive"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSetInactive(ApiContent $content): ApiResultObject
	{
		$parameters = $content->getParameters();
		$idCustomer = $parameters->getInt('idCustomer');
		$customer = $this->getCustomerControl()->get($idCustomer);
		$customer->setInactive($parameters->getBoolean('inactive'));

		$this->getCustomerControl()->set($customer);

		$result = new ApiResultObject();
		$result->setResult($customer, 'cliente "%s" de CNPJ "%s" atualizado com êxito', $customer->getFantasyName(), $customer->getCnpj());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idCustomer = $content->getParameters()->getInt('idCustomer');
		$customer = $this->getCustomerControl()->get($idCustomer);

		$result = new ApiResultObject();
		$result->setResult($customer, 'cliente "%s" obtido com êxito', $customer->getFantasyName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["cnpj"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionGetByCnpj(ApiContent $content): ApiResultObject
	{
		$cnpj = $content->getParameters()->getString('cnpj');
		$customer = $this->getCustomerControl()->getByCnpj($cnpj);

		$result = new ApiResultObject();
		$result->setResult($customer, 'cliente "%s" obtido com êxito', $customer->getFantasyName());

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
		$customers = $this->getCustomerControl()->getAll();

		$result = new ApiResultObject();
		$result->setResult($customers, 'encontrado %d clientes no banco de dados', $customers->size());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["filter","value","idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionSearch(ApiContent $content): ApiResultObject
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'stateRegistry': return $this->searchByStateRegistry($content);
			case 'cnpj': return $this->searchByCnpj($content);
			case 'fantasyName': return $this->searchByFantasyName($content);
		}

		throw new FilterException($filter);
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	private function searchByStateRegistry(ApiContent $content): ApiResultObject
	{
		$stateRegistry = $content->getParameters()->getString('value');
		$customers = $this->getCustomerControl()->searchByStateRegistry($stateRegistry);

		$result = new ApiResultObject();
		$result->setResult($customers, 'encontrados %d clientes com inscrição estadual "%s"', $customers->size(), $stateRegistry);

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	private function searchByCnpj(ApiContent $content): ApiResultObject
	{
		$cnpj = $content->getParameters()->getString('value');
		$customers = $this->getCustomerControl()->searchByCnpj($cnpj);

		$result = new ApiResultObject();
		$result->setResult($customers, 'encontrados %d clientes com CNPJ "%s"', $customers->size(), $cnpj);

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	private function searchByFantasyName(ApiContent $content): ApiResultObject
	{
		$fantasyName = $content->getParameters()->getString('value');
		$customers = $this->getCustomerControl()->searchByFantasyName($fantasyName);

		$result = new ApiResultObject();
		$result->setResult($customers, 'encontrados %d clientes com nome fantasia "%s"', $customers->size(), $fantasyName);

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["filter","value","idCustomer"]})
	 * @param ApiContent $content
	 * @throws FilterException
	 * @return ApiResultSimpleValidation
	 */
	public function actionAvaiable(ApiContent $content): ApiResultSimpleValidation
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'cnpj': return $this->avaiableCnpj($content);
			case 'companyName': return $this->avaiableCompanyName($content);
		}

		throw new FilterException($filter);
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultSimpleValidation
	 */
	private function avaiableCnpj(ApiContent $content): ApiResultSimpleValidation
	{
		$cnpj = $content->getParameters()->getString('value');
		$idCustomer = $this->parseNullToInt($content->getParameters()->getInt('idCustomer', false));
		$avaiable = $this->getCustomerControl()->hasAvaiableCnpj($cnpj, $idCustomer);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'CNPJ "%s" %s', $cnpj, $this->getMessageAvaiable($avaiable));

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultSimpleValidation
	 */
	private function avaiableCompanyName(ApiContent $content): ApiResultSimpleValidation
	{
		$companyName = $content->getParameters()->getString('value');
		$idCustomer = $this->parseNullToInt($content->getParameters()->getInt('idCustomer', false));
		$avaiable = $this->getCustomerControl()->hasAvaiableCompanyName($companyName, $idCustomer);

		$result = new ApiResultSimpleValidation();
		$result->setOkMessage($avaiable, 'razão social "%s" %s', $companyName, $this->getMessageAvaiable($avaiable));

		return $result;
	}
}

