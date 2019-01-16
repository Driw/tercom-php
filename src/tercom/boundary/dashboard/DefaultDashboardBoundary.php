<?php

namespace tercom\boundary\dashboard;

use dProject\Primitive\Session;
use dProject\restful\template\ApiTemplate;
use tercom\SessionVar;

/**
 * @see ApiTemplate
 * @author Andrew
 */

abstract class DefaultDashboardBoundary extends ApiTemplate
{
	/**
	 * @var int
	 */
	public const START_YEAR = 2018;

	/**
	 * @var DashboardConfigs
	 */
	private $configs;
	/**
	 * @var string
	 */
	private $localJavaScript;
	/**
	 * @var bool
	 */
	private $verifyLogin;

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
		if ($this->isVerifyLogin() && !$this->hasLogin())
			header('Location: /dashboard/login');

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
			$dashboardTemplate->setDataArray('JavaScriptPage', [ sprintf('boundaries/%s%s.js', $this->getLocalJavaScript(), DEV ? '' : '.min') ]);

		$configs->getHead()->set('BaseURL', sprintf('%sdashboard/', DOMAIN), true, true);
		$dashboardTemplate->ImportTimestamp = sprintf('?%d', time());
		$dashboardTemplate->setDataConfig('Head', $configs->getHead());
		$dashboardTemplate->setDataConfig('Fonts', $configs->getFonts());
		$dashboardTemplate->setDataConfig('Stylesheet', $configs->getStyleSheets());
		$dashboardTemplate->setDataConfig('JavaScript', $configs->getJavaScripts());
		$dashboardTemplate->setDataConfig('NavSide', $configs->getNavSide());

		// Footer
		$dashboardTemplate->StartYear = self::START_YEAR;
		$dashboardTemplate->CurrentYear = ($currentYear = date('Y')) == $dashboardTemplate->StartYear ? '' : "-$currentYear";

		return $dashboardTemplate;
	}

	/**
	 * @return DashboardTemplate
	 */
	public function newErrorBaseTemplate(): DashboardTemplate
	{
		$configs = $this->getConfigs();
		$dashboardTemplate = new DashboardTemplate('BaseError');

		$configs->getHead()->set('BaseURL', sprintf('%sdashboard/', DOMAIN), true, true);
		$dashboardTemplate->ImportTimestamp = sprintf('?%d', time());
		$dashboardTemplate->setDataConfig('Head', $configs->getHead());
		$dashboardTemplate->setDataConfig('Fonts', $configs->getFonts());
		$dashboardTemplate->setDataConfig('Stylesheet', $configs->getStyleSheets());
		$dashboardTemplate->setDataConfig('JavaScript', $configs->getJavaScripts());

		// Footer
		$dashboardTemplate->StartYear = self::START_YEAR;
		$dashboardTemplate->CurrentYear = ($currentYear = date('Y')) == $dashboardTemplate->StartYear ? '' : "-$currentYear";

		return $dashboardTemplate;
	}

	/**
	 * @return DashboardTemplate
	 */
	public function newLoginBaseTemplate(): DashboardTemplate
	{
		$configs = $this->getConfigs();
		$dashboardTemplate = new DashboardTemplate('Login/Base');

		$configs->getHead()->set('BaseURL', sprintf('%sdashboard/', DOMAIN), true, true);
		$dashboardTemplate->ImportTimestamp = sprintf('?%d', time());
		$dashboardTemplate->setDataConfig('Head', $configs->getHead());
		$dashboardTemplate->setDataConfig('Fonts', $configs->getFonts());
		$dashboardTemplate->setDataConfig('Stylesheet', $configs->getStyleSheets());
		$dashboardTemplate->setDataConfig('JavaScript', $configs->getJavaScripts());

		// Footer
		$dashboardTemplate->StartYear = self::START_YEAR;
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
	 * @return boolean
	 */
	public abstract function isVerifyLogin(): bool;

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
	 * Verifica se existe uma sessão ativa do qual possua acesso no sistema efetuado, seja ele qual for.
	 * @return bool true se existir um acesso ou false caso contrário.
	 */
	protected function hasLogin(): bool
	{
		$session = Session::getInstance();
		$session->start();
		$hasLogin = $session->isSetted(SessionVar::LOGIN_ID) && $session->isSetted(SessionVar::LOGIN_TOKEN) &&
		($session->isSetted(SessionVar::LOGIN_CUSTOMER_ID) || $session->isSetted(SessionVar::LOGIN_TERCOM_ID));

		return $hasLogin;
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

