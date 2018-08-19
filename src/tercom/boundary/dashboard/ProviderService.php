<?php

namespace tercom\boundary\dashboard;

use dProject\restful\template\ApiTemplateResult;
use dProject\restful\ApiContent;
use tercom\control\ProviderControl;

/**
 * @see BoundaryManager
 * @author Andrew
 */

class ProviderService extends DashboardApiTemplate
{
	/**
	 * @var DashboardConfigs
	 */
	private $configs;

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::init()
	 */

	public function init()
	{
		$this->setNavSideActive('provider');
	}

	/**
	 * @return ApiTemplateResult
	 */

	public function actionList(): ApiTemplateResult
	{
		$dashboardTemplate = $this->prepareInclude('ProviderList');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @return ApiTemplateResult
	 */

	public function actionSearch()
	{
		$dashboardTemplate = $this->getApiParent()->newBaseTemplate();
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

	public function actionAdd(): ApiTemplateResult
	{
		$dashboardTemplate = $this->getApiParent()->newBaseTemplate();
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

	public function actionView(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->getApiParent()->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude('ProviderView');
		$dashboardTemplate->ProviderID = $content->getParameters()->getInt('idProvider');

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
		return $this->getApiParent()->callIndex();
	}
}

