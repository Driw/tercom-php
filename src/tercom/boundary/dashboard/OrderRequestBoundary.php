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
class OrderRequestBoundary extends DefaultDashboardLoggedBoundary
{
	/**
	 * @var string
	 */
	public const BASE_PATH = 'OrderRequest/';

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::init()
	 */
	public function init()
	{
		parent::init();

		$this->setNavSideActive('quotes', 'orderRequest');
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
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'OrderRequestList');

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
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'OrderRequestAdd');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idOrderRequest"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onView(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'OrderRequestView');
		$dashboardTemplate->idOrderRequest = $content->getParameters()->getInt('idOrderRequest');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}
}

