<?php

namespace tercom\boundary\dashboard;

use dProject\restful\ApiContent;
use dProject\restful\template\ApiTemplateResult;
use tercom\core\System;
use tercom\entities\ProductCategory;

/**
 * @see BoundaryManager
 * @author Andrew
 */
class ProductFamilyBoundary extends DefaultDashboardLoggedBoundary
{
	/**
	 * @var string
	 */
	public const BASE_PATH = 'ProductFamily/';

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::init()
	 */
	public function init()
	{
		parent::init();

		$this->setNavSideActive('productCategory');
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
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onList(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductFamilyList');

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
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductFamilyAdd');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductFamily"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onView(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductFamilyView');
		$dashboardTemplate->idProductFamily = $content->getParameters()->getInt('idProductFamily');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductFamily"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onRemove(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductFamilyRemove');
		$dashboardTemplate->idProductFamily = $content->getParameters()->getInt('idProductFamily');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}
}

