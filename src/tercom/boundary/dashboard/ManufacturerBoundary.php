<?php

namespace tercom\boundary\dashboard;

use dProject\restful\ApiContent;
use dProject\restful\template\ApiTemplateResult;
use tercom\control\ManufactureControl;

/**
 * @see BoundaryManager
 * @author Andrew
 */

class ManufacturerBoundary extends DefaultDashboardBoundary
{
	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::init()
	 */

	public function init()
	{
		parent::init();

		$this->setNavSideActive('manufacturer');
	}

	/**
	 * {@inheritDoc}
	 * @see \dproject\restful\template\ApiTemplate::callIndex()
	 */

	public function callIndex()
	{
		$baseTemplate = $this->newBaseTemplate();
		$result = new ApiTemplateResult();
		$result->add($baseTemplate);

		return $result;
	}

	/**
	 * @return ApiTemplateResult
	 */

	public function onList(): ApiTemplateResult
	{
		$dashboardTemplate = $this->prepareInclude('ManufacturerList');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	* @ApiAnnotation({"params":["fantasyName"]})
	 * @return ApiTemplateResult
	 */

	public function onSearch()
	{
		$dashboardTemplate = $this->getApiParent()->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude('ManufactureSearch');
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
		$providerFilters = ManufactureControl::getFilters();
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
		$dashboardTemplate = $this->getApiParent()->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude('ManufacturerAdd');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idManufacturer"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */

	public function onView(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->getApiParent()->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude('ManufacturerView');
		$dashboardTemplate->idManufacturer = $content->getParameters()->getInt('idManufacturer');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}
}

