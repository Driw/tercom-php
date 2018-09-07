<?php

namespace tercom\boundary\dashboard;

use dProject\restful\template\ApiTemplateResult;
use tercom\boundary\BoundaryConfigs;

/**
 * @see BoundaryManager
 * @author Andrew
 */

class DashboardService extends DefaultDashboardBoundary
{
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

