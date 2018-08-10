<?php

namespace tercom\boundary\dashboard;

use dProject\Primitive\Config;
use tercom\boundary\BoundaryConfigs;

/**
 * @see Config
 * @see BoundaryConfigs
 * @author Andrew
 */

class DashboardConfigs extends BoundaryConfigs
{
	/**
	 * {@inheritDoc}
	 * @see \tercom\boundary\BoundaryConfigs::getHead()
	 */

	public function getHead(): Config
	{
		return $this->getConfigByName('dashboard-head');
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\boundary\BoundaryConfigs::getJavaScripts()
	 */

	public function getJavaScripts(): Config
	{
		return $this->getConfigByName('dashboard-javascripts');
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\boundary\BoundaryConfigs::getStyleSheets()
	 */

	public function getStyleSheets(): Config
	{
		return $this->getConfigByName('dashboard-stylesheets');
	}
}

