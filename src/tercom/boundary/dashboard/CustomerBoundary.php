<?php

namespace tercom\boundary\dashboard;

use dProject\restful\template\ApiTemplateResult;
use dProject\restful\ApiContent;
use tercom\control\CustomerControl;
use tercom\core\System;
use tercom\entities\Address;

/**
 * @see BoundaryManager
 * @author Andrew
 */
class CustomerBoundary extends DefaultDashboardLoggedBoundary
{
	/**
	 * @var string
	 */
	public const BASE_PATH = 'Customer/';
	/**
	 * @var string
	 */
	public const ADDRESS_BASE_PATH = 'Address/';

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::init()
	 */
	public function init()
	{
		parent::init();

		$this->setNavSideActive('customer');
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
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onList(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'CustomerList');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onSearch(ApiContent $content)
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'CustomerSearch');
		$dashboardTemplate->setDataArray('FilterOption', $this->getFilterOptions());

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onAdd(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'CustomerAdd');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onView(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'CustomerView');
		$dashboardTemplate->idCustomer = $content->getParameters()->getInt('idCustomer');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onViewAddresses(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::ADDRESS_BASE_PATH. 'AddressList');
		$dashboardTemplate->idRelationship = $content->getParameters()->getInt('idCustomer');
		$dashboardTemplate->relationship = 'customer';

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onAddAddress(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::ADDRESS_BASE_PATH. 'AddressAdd');
		$dashboardTemplate->setDataArray('StateOption', $this->getStateOptions());
		$dashboardTemplate->relationship = 'customer';
		$dashboardTemplate->idRelationship = $content->getParameters()->getInt('idCustomer');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idCustomer","idAddress"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onViewAddress(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::ADDRESS_BASE_PATH. 'AddressView');
		$dashboardTemplate->setDataArray('StateOption', $this->getStateOptions());
		$dashboardTemplate->relationship = 'customer';
		$dashboardTemplate->idRelationship = $content->getParameters()->getInt('idCustomer');
		$dashboardTemplate->idAddress = $content->getParameters()->getInt('idAddress');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idCustomer","idAddress"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onRemoveAddress(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::ADDRESS_BASE_PATH. 'AddressRemove');
		$dashboardTemplate->setDataArray('StateOption', $this->getStateOptions());
		$dashboardTemplate->relationship = 'customer';
		$dashboardTemplate->idRelationship = $content->getParameters()->getInt('idCustomer');
		$dashboardTemplate->idAddress = $content->getParameters()->getInt('idAddress');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @return array
	 */
	private function getFilterOptions(): array
	{
		$providerFilters = CustomerControl::getFilters();
		$filterOptions = [];

		foreach ($providerFilters as $value => $option)
			$filterOptions[] = [ 'Value' => $value, 'Option' => $option ];

		return $filterOptions;
	}

	/**
	 * @return array
	 */
	private function getStateOptions(): array
	{
		$stateOptions = [];

		foreach (Address::getUfs() as $value => $option)
			$stateOptions[] = ['Value' => $value, 'Option' => $option];

		return $stateOptions;
	}
}

