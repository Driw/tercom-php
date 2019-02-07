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
class ProductSubgroupBoundary extends DefaultDashboardLoggedBoundary
{
	/**
	 * @var string
	 */
	public const BASE_PATH = 'ProductSubgroup/';

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
	 * @ApiAnnotation({"params":["idProductGroup"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onList(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductSubgroupList');
		$dashboardTemplate->idProductGroup = $content->getParameters()->getInt('idProductGroup', false);

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductGroup"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onAdd(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductSubgroupAdd');
		$dashboardTemplate->idProductGroup = $content->getParameters()->getInt('idProductGroup', false);

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductSubgroup"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onView(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductSubgroupView');
		$dashboardTemplate->idProductSubgroup = $content->getParameters()->getInt('idProductSubgroup');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductSubgroup"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onRemove(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductSubgroupRemove');
		$dashboardTemplate->idProductSubgroup = $content->getParameters()->getInt('idProductSubgroup');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}
}

