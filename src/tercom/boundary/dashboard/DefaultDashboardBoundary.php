<?php

namespace tercom\boundary\dashboard;

use dProject\restful\template\ApiTemplate;

/**
 * @see ApiTemplate
 * @author Andrew
 */

abstract class DefaultDashboardBoundary extends ApiTemplate
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
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::patterClassService()
	 */

	protected function patterClassService($serviceName)
	{
		return sprintf('%sBoundary', $serviceName);
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::patterMethodAction()
	 */

	protected function patterMethodAction($actionName)
	{
		return sprintf('on%s', ucfirst($actionName));
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::init()
	 */

	public function init()
	{
		$this->setLocalJavaScript('');
	}

	/**
	 * @return string
	 */

	public function getLocalJavaScript(): string
	{
		return $this->localJavaScript;
	}

	/**
	 * @param string $localJavaScript
	 */

	public function setLocalJavaScript(string $localJavaScript)
	{
		$this->localJavaScript = $localJavaScript;
	}

	/**
	 * @return DashboardTemplate
	 */

	public function newBaseTemplate(): DashboardTemplate
	{
		$configs = $this->getConfigs();
		$dashboardTemplate = new DashboardTemplate('Base');

		if (!empty($this->getLocalJavaScript()))
			$dashboardTemplate->setDataArray('JavaScriptPage', [ sprintf('boundaries/%s.min.js', $this->getLocalJavaScript()) ]);

		$configs->getHead()->set('BaseURL', sprintf('%sdashboard/', DOMAIN), true, true);
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

	/**
	 * @param string $serviceName
	 * @return bool
	 */

	protected function setNavSideActive(string $serviceName): bool
	{
		$navSide = $this->getConfigs()->getNavSide();
		$items = &$navSide->toArray();

		foreach ($items as &$item)
		{
			if (isset($item['Item']) && $item['Item']['service'] === $serviceName)
			{
				$item['Item']['Class'] = trim(sprintf('%s active', $item['Item']['Class']));
				return true;
			}

			else if (isset($item['Dropdown']) && $item['Dropdown']['service'] === $serviceName)
			{
				$item['Dropdown']['Class'] = trim(sprintf('%s active', $item['Dropdown']['Class']));
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $name
	 * @param bool $hasJavaScript
	 * @return DashboardTemplate
	 */

	protected function prepareInclude(string $name, bool $hasJavaScript = true): DashboardTemplate
	{
		if ($hasJavaScript)
			$this->setLocalJavaScript($name);

		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate->addFile('IncludeDashboard', $name);

		return $dashboardTemplate;
	}

	/**
	 * @param array $array
	 * @return array
	 */

	public static function parseOptions(array $array): array
	{
		$options = [];

		foreach ($array as $value => $option)
			$options[] = [
				'Value' => $value,
				'Option' => $option,
			];

		return $options;
	}
}

