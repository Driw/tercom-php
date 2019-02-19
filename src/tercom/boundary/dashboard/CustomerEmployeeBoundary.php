<?php

namespace tercom\boundary\dashboard;

use dProject\restful\template\ApiTemplateResult;
use dProject\restful\ApiContent;
use tercom\core\System;
use tercom\entities\Phone;

/**
 * @see BoundaryManager
 * @author Andrew
 */
class CustomerEmployeeBoundary extends DefaultDashboardLoggedBoundary
{
	/**
	 * @var string
	 */
	public const BASE_PATH = 'CustomerEmployee/';

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::init()
	 */
	public function init()
	{
		parent::init();

		$this->setNavSideActive('customerEmployee');
	}

	/**
	 * {@inheritDoc}
	 * @see \dproject\restful\template\ApiTemplate::callIndex()
	 */
	public function callIndex()
	{
		return $this->onList(System::getApiConnection()->getContent());
	}

	/**
	 * @param DashboardTemplate $dashboardTemplate
	 * @param ApiContent $content
	 */
	private function prepare(DashboardTemplate $dashboardTemplate, ApiContent $content): void
	{
		if (!$this->isLoginTercom())
		{
			$dashboardTemplate->idCustomer = ($idCustomer = $this->getCustomerId($content, false));
			$dashboardTemplate->HiddenCustomer = 'd-none';
			$dashboardTemplate->setDataArray('Customer', ['Id' => $idCustomer, 'FantasyName' => $this->getCustomerLogged()->getFantasyName()]);
		}

		else
		{
			$dashboardTemplate->idCustomer = ($idCustomer = $this->getCustomerId($content, false));
			$dashboardTemplate->HiddenCustomer = $idCustomer === null ? '' : 'd-none';
			$dashboardTemplate->setDataArray('Customer', []);
		}
	}

	/**
	 * @ApiAnnotation({"params":["idCustomerProfile","idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onList(ApiContent $content): ApiTemplateResult
	{
		$parameters = $content->getParameters();
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'CustomerEmployeeList');
		$dashboardTemplate->idCustomerProfile = $parameters->getInt('idCustomerProfile', false);
		$dashboardTemplate->idCustomer = $parameters->getInt('idCustomer', false);
		$dashboardTemplate->block($this->isLoginTercom() ? 'LoginTercom' : 'LoginCustomer');
		$this->prepare($dashboardTemplate, $content);

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onAdd(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'CustomerEmployeeAdd');
		$this->prepare($dashboardTemplate, $content);

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idCustomerEmployee","idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onView(ApiContent $content): ApiTemplateResult
	{
		$phoneTypeOptions = DashboardService::parseOptions(Phone::getTypes());
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'CustomerEmployeeView');
		$dashboardTemplate->idCustomer = $this->getCustomerId($content);
		$dashboardTemplate->idCustomerEmployee = $content->getParameters()->getInt('idCustomerEmployee');
		$dashboardTemplate->setDataArray('CellPhoneType', $phoneTypeOptions);
		$dashboardTemplate->setDataArray('PhoneType', $phoneTypeOptions);

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idCustomerEmployee","idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onRemove(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'CustomerEmployeeRemove');
		$dashboardTemplate->idCustomer = $this->getCustomerId($content);
		$dashboardTemplate->idCustomerEmployee = $content->getParameters()->getInt('idCustomerEmployee');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}
}

