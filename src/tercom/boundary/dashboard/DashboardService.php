<?php

namespace tercom\boundary\dashboard;

use dProject\restful\template\ApiTemplate;
use dProject\restful\template\ApiTemplateResult;

/**
 * @see BoundaryManager
 * @author Andrew
 */

class DashboardService extends ApiTemplate
{
	/**
	 * {@inheritDoc}
	 * @see \dproject\restful\template\ApiTemplate::callIndex()
	 */

	public function callIndex()
	{
		$dashboardTemplate = new DashboardTemplate('Index');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}
}

