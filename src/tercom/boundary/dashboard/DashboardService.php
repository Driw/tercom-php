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
	 * @var DashboardConfigs
	 */
	private $configs;
	/**
	 * @var string
	 */
	private $localJavaScript;

	/**
	 *
	 */

	public function init()
	{
		$this->setNamespaceServices(namespaceOf(BoundaryConfigs::class));
	}

	/**
	 * @param string $localJavaScript
	 */

	public function setLocalJavaScript(string $localJavaScript)
	{
		$this->localJavaScript = $localJavaScript;
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

	/**
	 * @return DashboardTemplate
	 */

	public function newBaseTemplate(): DashboardTemplate
	{
		$configs = $this->getConfigs();
		$dashboardTemplate = new DashboardTemplate('Base');

		if (!empty($this->localJavaScript))
			$dashboardTemplate->setDataArray('JavaScriptPage', [ sprintf('boundaries/%s.min.js', $this->localJavaScript) ]);

		$configs->getHead()->set('BaseURL', sprintf('%s://%s/dashboard/', DEV ? 'http' : 'https', $_SERVER['HTTP_HOST']), true, true);
		$dashboardTemplate->ImportTimestamp = sprintf('?%d', time());
		$dashboardTemplate->setDataConfig('Head', $configs->getHead());
		$dashboardTemplate->setDataConfig('Fonts', $configs->getFonts());
		$dashboardTemplate->setDataConfig('Stylesheet', $configs->getStyleSheets());
		$dashboardTemplate->setDataConfig('JavaScript', $configs->getJavaScripts());
		$dashboardTemplate->setDataConfig('NavSide', $configs->getNavSide());

		// Footer
		$dashboardTemplate->StartYear = 2018;
		$dashboardTemplate->CurrentYear = ($currentYear = date('Y')) == $dashboardTemplate->StartYear ? '' : "-$currentYear";

		return $dashboardTemplate;
	}

	/**
	 * @return DashboardConfigs
	 */

	public function getConfigs(): DashboardConfigs
	{
		if ($this->configs === null)
			$this->configs = new DashboardConfigs();

		return $this->configs;
	}
}

