<?php

namespace tercom\boundary\dashboard;

use dProject\restful\ApiContent;
use dProject\restful\template\ApiTemplateResult;
use tercom\core\System;

/**
 * @see BoundaryManager
 * @author Andrew
 */

class ProductUnitBoundary extends DefaultDashboardLoggedBoundary
{
	/**
	 * @var string
	 */
	public const BASE_PATH = 'ProductUnit/';

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::init()
	 */
	public function init()
	{
		parent::init();

		$this->setNavSideActive('productUnit');
	}

	/**
	 * {@inheritDoc}
	 * @see \dproject\restful\template\ApiTemplate::callIndex()
	 */
	public function callIndex()
	{
		return $this->onList(System::getDashboardConnection()->getContent());
	}

	/**
	 * @return ApiTemplateResult
	 */
	public function onList(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductUnitList');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @return ApiTemplateResult
	 */
	public function onAdd(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->getApiParent()->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductUnitAdd');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductUnit"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onView(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->getApiParent()->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductUnitView');
		$dashboardTemplate->idProductUnit = $content->getParameters()->getInt('idProductUnit');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductUnit"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onRemove(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->getApiParent()->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductUnitRemove');
		$dashboardTemplate->idProductUnit = $content->getParameters()->getInt('idProductUnit');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}
}

