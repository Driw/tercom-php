<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultCustomer;
use tercom\api\site\results\ApiResultCustomers;
use tercom\api\site\results\ApiResultCustomerSettings;
use tercom\entities\Customer;
use tercom\api\exceptions\FilterException;
use tercom\api\site\results\ApiResultSimpleValidation;

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
	 * @return ApiResultCustomer
	 */
	public function actionAdd(ApiContent $content): ApiResultCustomer
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

		$result = new ApiResultCustomer();
		$result->setCustomer($customer);
		$result->setMessage('cliente "%s" de CNPJ "%s" adicionado com êxito', $customer->getFantasyName(), $customer->getCnpj());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiResultCustomer
	 */
	public function actionSet(ApiContent $content): ApiResultCustomer
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

		$result = new ApiResultCustomer();
		$result->setCustomer($customer);
		$result->setMessage('cliente "%s" de CNPJ "%s" atualizado com êxito', $customer->getFantasyName(), $customer->getCnpj());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiResultCustomer
	 */
	public function actionSetInactive(ApiContent $content): ApiResultCustomer
	{
		$post = $content->getPost();
		$idCustomer = $content->getParameters()->getInt('idCustomer');
		$customer = $this->getCustomerControl()->get($idCustomer);

		if ($post->isSetted('inactive')) $customer->setInactive($post->getBoolean('inactive'));

		$this->getCustomerControl()->set($customer);

		$result = new ApiResultCustomer();
		$result->setCustomer($customer);
		$result->setMessage('cliente "%s" de CNPJ "%s" atualizado com êxito', $customer->getFantasyName(), $customer->getCnpj());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiResultCustomer
	 */
	public function actionGet(ApiContent $content): ApiResultCustomer
	{
		$idCustomer = $content->getParameters()->getInt('idCustomer');
		$customer = $this->getCustomerControl()->get($idCustomer);

		$result = new ApiResultCustomer();
		$result->setCustomer($customer);
		$result->setMessage('cliente "%s" obtido com êxito', $customer->getFantasyName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["cnpj"]})
	 * @param ApiContent $content
	 * @return ApiResultCustomer
	 */
	public function actionGetByCnpj(ApiContent $content): ApiResultCustomer
	{
		$cnpj = $content->getParameters()->getString('cnpj');
		$customer = $this->getCustomerControl()->getByCnpj($cnpj);

		$result = new ApiResultCustomer();
		$result->setCustomer($customer);
		$result->setMessage('cliente "%s" obtido com êxito', $customer->getFantasyName());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content
	 * @return ApiResultCustomer
	 */
	public function actionGetAll(ApiContent $content): ApiResultCustomers
	{
		$customers = $this->getCustomerControl()->getAll();

		$result = new ApiResultCustomers();
		$result->setCustomers($customers);
		$result->setMessage('encontrado %d clientes no banco de dados', $customers->size());

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["filter","value","idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiResultCustomers
	 */
	public function actionSearch(ApiContent $content): ApiResultCustomers
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
	 * @return ApiResultCustomers
	 */
	private function searchByStateRegistry(ApiContent $content): ApiResultCustomers
	{
		$stateRegistry = $content->getParameters()->getString('value');
		$customers = $this->getCustomerControl()->searchByStateRegistry($stateRegistry);

		$result = new ApiResultCustomers();
		$result->setCustomers($customers);
		$result->setMessage('encontrados %d clientes com inscrição estadual "%s"', $customers->size(), $stateRegistry);

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultCustomers
	 */
	private function searchByCnpj(ApiContent $content): ApiResultCustomers
	{
		$cnpj = $content->getParameters()->getString('value');
		$customers = $this->getCustomerControl()->searchByCnpj($cnpj);

		$result = new ApiResultCustomers();
		$result->setCustomers($customers);
		$result->setMessage('encontrados %d clientes com CNPJ "%s"', $customers->size(), $cnpj);

		return $result;
	}

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResultCustomers
	 */
	private function searchByFantasyName(ApiContent $content): ApiResultCustomers
	{
		$fantasyName = $content->getParameters()->getString('value');
		$customers = $this->getCustomerControl()->searchByStateRegistry($fantasyName);

		$result = new ApiResultCustomers();
		$result->setCustomers($customers);
		$result->setMessage('encontrados %d clientes com nome fantasia "%s"', $customers->size(), $fantasyName);

		return $result;
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["filter","value"]})
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
		$result = new ApiResultSimpleValidation();

		if ($this->getCustomerControl()->hasAvaiableCnpj($cnpj, $idCustomer))
			$result->setOkMessage(true, 'CNPJ "%s" disponível', $cnpj);
		else
			$result->setOkMessage(false, 'CNPJ "%s" indisponível', $cnpj);

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
		$result = new ApiResultSimpleValidation();

		if ($this->getCustomerControl()->hasAvaiableCompanyName($companyName, $idCustomer))
			$result->setOkMessage(true, 'razão social "%s" disponível', $companyName);
		else
			$result->setOkMessage(false, 'razão social "%s" indisponível', $companyName);

		return $result;
	}
}

