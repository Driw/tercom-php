<?php

namespace tercom\boundary\dashboard;

use dProject\restful\template\ApiTemplateResult;
use dProject\restful\ApiContent;
use tercom\control\ServiceControl;
use tercom\core\System;

/**
 * @see BoundaryManager
 * @author Andrew
 */

class ServiceBoundary extends DefaultDashboardLoggedBoundary
{
	/**
	 * @var string
	 */
	public const BASE_PATH = 'Service/';
	/**
	 * @var string
	 */
	public const BASE_PATH_PRICE = 'ServicePrice/';

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::init()
	 */
	public function init()
	{
		parent::init();

		$this->setNavSideActive('service');
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
	 * @return ApiTemplateResult
	 */
	public function onList(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ServiceList');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @return ApiTemplateResult
	 */
	public function onSearch(ApiContent $content)
	{
		$filterOptions = DashboardService::parseOptions(ServiceControl::getFilters());
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ServiceSearch');
		$dashboardTemplate->setDataArray('FilterOption', $filterOptions);

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @return ApiTemplateResult
	 */
	public function onAdd(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ServiceAdd');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idService"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onView(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ServiceView');
		$dashboardTemplate->idService = $content->getParameters()->getInt('idService');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idService"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onViewPrices(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH_PRICE. 'ServicePriceList');
		$dashboardTemplate->idService = $content->getParameters()->getInt('idService');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idService"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onAddPrice(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH_PRICE. 'ServicePriceAdd');
		$dashboardTemplate->idService = $content->getParameters()->getInt('idService');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idServicePrice"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onViewPrice(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH_PRICE. 'ServicePriceView');
		$dashboardTemplate->idServicePrice = $content->getParameters()->getInt('idServicePrice');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idServicePrice"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onRemovePrice(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH_PRICE. 'ServicePriceRemove');
		$dashboardTemplate->idServicePrice = $content->getParameters()->getInt('idServicePrice');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}
}

