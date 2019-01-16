<?php

namespace tercom\boundary\dashboard;

use dProject\restful\template\ApiTemplateResult;
use tercom\boundary\BoundaryConfigs;
use tercom\core\System;

/**
 * @see DefaultDashboardLoggedBoundary
 *
 * @author Andrew
 */
class DashboardService extends DefaultDashboardLoggedBoundary
{
	/**
	 * {@inheritDoc}
	 * @see \tercom\boundary\dashboard\DefaultDashboardBoundary::isVerifyLogin()
	 */
	public function isVerifyLogin(): bool
	{
		$parameters = System::getDashboardConnection()->getContent()->getParameters();

		return !$parameters->isSetted(1) || $parameters->getString(1) !== 'login';
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::init()
	 */
	public function init()
	{
		parent::init();

		$this->setNamespaceServices(namespaceOf(BoundaryConfigs::class));
	}

	/**
	 * {@inheritDoc}
	 * @see \dproject\restful\template\ApiTemplate::callIndex()
	 */
	public function callIndex()
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate->addFile('IncludeDashboard', 'Index');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}
}

