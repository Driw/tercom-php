<?php

namespace tercom\boundary\dashboard;

use dProject\restful\ApiContent;
use dProject\restful\template\ApiTemplateResult;

/**
 * @see BoundaryManager
 * @author Andrew
 */

class ProductUnitBoundary extends DefaultDashboardLoggedBoundary
{
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
		$dashboardTemplate = $this->prepareInclude('ProductUnitList');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @return ApiTemplateResult
	 */

	public function onAdd(): ApiTemplateResult
	{
		$dashboardTemplate = $this->getApiParent()->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude('ProductUnitAdd');

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
		$dashboardTemplate = $this->prepareInclude('ProductUnitView');
		$dashboardTemplate->idProductUnit = $content->getParameters()->getInt('idProductUnit');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}
}

