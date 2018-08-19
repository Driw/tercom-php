<?php

namespace tercom\boundary\dashboard;

use dproject\restful\template\ApiTemplate;

/**
 * @see ApiTemplate
 * @author Andrew
 */

abstract class DashboardApiTemplate extends ApiTemplate
{
	/**
	 * @return DashboardService
	 * @see \dProject\restful\ApiServiceInterface::getApiParent()
	 */

	public function getApiParent()
	{
		return parent::getApiParent();
	}

	/**
	 * @param string $serviceName
	 * @return bool
	 */

	protected function setNavSideActive(string $serviceName): bool
	{
		$navSide = $this->getApiParent()->getConfigs()->getNavSide();
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
	 * @return DashboardTemplate
	 */

	protected function prepareInclude(string $name): DashboardTemplate
	{
		$this->getApiParent()->setLocalJavaScript($name);
		$dashboardTemplate = $this->getApiParent()->newBaseTemplate();
		$dashboardTemplate->addFile('IncludeDashboard', $name);

		return $dashboardTemplate;
	}
}

