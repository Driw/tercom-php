<?php

namespace tercom\boundary\dashboard;

use dProject\restful\template\ApiTemplateResult;
use dProject\restful\ApiContent;
use tercom\control\ProviderControl;
use tercom\entities\Phone;

/**
 * @see BoundaryManager
 * @author Andrew
 */

class ProviderBoundary extends DefaultDashboardLoggedBoundary
{
	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::init()
	 */

	public function init()
	{
		parent::init();

		$this->setNavSideActive('provider');
	}

	/**
	 * @return ApiTemplateResult
	 */

	public function onList(): ApiTemplateResult
	{
		$dashboardTemplate = $this->prepareInclude('ProviderList');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @return ApiTemplateResult
	 */

	public function onSearch()
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude('ProviderSearch');
		$dashboardTemplate->setDataArray('FilterOption', $this->getFilterOptions());

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @return array
	 */

	private function getFilterOptions(): array
	{
		$providerFilters = ProviderControl::getFilters();
		$filterOptions = [];

		foreach ($providerFilters as $value => $option)
			$filterOptions[] = [ 'Value' => $value, 'Option' => $option ];

		return $filterOptions;
	}

	/**
	 * @return ApiTemplateResult
	 */

	public function onAdd(): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude('ProviderAdd');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProvider"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */

	public function onView(ApiContent $content): ApiTemplateResult
	{
		$phoneTypeOptions = DashboardService::parseOptions(Phone::getTypes());
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude('ProviderView');
		$dashboardTemplate->ProviderID = $content->getParameters()->getInt('idProvider');
		$dashboardTemplate->setDataArray('Commercial_PhoneType', $phoneTypeOptions);
		$dashboardTemplate->setDataArray('Otherphone_PhoneType', $phoneTypeOptions);
		$dashboardTemplate->setDataArray('Contact_Commercial_PhoneType', $phoneTypeOptions);
		$dashboardTemplate->setDataArray('Contact_Otherphone_PhoneType', $phoneTypeOptions);

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * {@inheritDoc}
	 * @see \dproject\restful\template\ApiTemplate::callIndex()
	 */

	public function callIndex()
	{
		return $this->callIndex();
	}
}

