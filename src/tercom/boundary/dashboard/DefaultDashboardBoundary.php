<?php

namespace tercom\boundary\dashboard;

use dProject\Primitive\Session;
use dProject\restful\template\ApiTemplate;
use tercom\SessionVar;
use tercom\control\LoginCustomerControl;
use tercom\control\LoginTercomControl;
use tercom\entities\Customer;
use tercom\entities\LoginCustomer;
use tercom\entities\LoginTercom;
use tercom\TercomException;

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
	 * @var string
	 */
	public const JS_CONST_LOGIN_ID = 'LOGIN_ID';
	/**
	 * @var string
	 */
	public const JS_CONST_LOGIN_TOKEN = 'LOGIN_TOKEN';
	/**
	 * @var string
	 */
	public const JS_CONST_LOGIN_EMPLOYEE_ID = 'LOGIN_EMPLOYEE_ID';
	/**
	 * @var string
	 */
	public const JS_CONST_LOGIN_PROFILE_ID = 'LOGIN_PROFILE_ID';
	/**
	 * @var string
	 */
	public const JS_CONST_IS_LOGIN_TERCOM = 'IS_LOGIN_TERCOM';

	/**
	 * @var LoginTercom
	 */
	private static $loginTercom;
	/**
	 * @var LoginCustomer
	 */
	private static $loginCustomer;

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
	 * @var array
	 */
	private $javaScriptConstants;

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
		$this->javaScriptConstants = [];

		if ($this->isVerifyLogin() && !$this->doLogin())
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
	 * @param string $name
	 * @param string $value
	 */
	public function addJavaScriptConstant(string $name, string $value, bool $quotes = false): void
	{
		$this->javaScriptConstants[] = [
			'Name' => $name,
			'Value' => $quotes ? "\"$value\"" : $value,
		];
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
		$dashboardTemplate->setDataArray('JsConstants', $this->javaScriptConstants);
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

		if (!empty($this->getLocalJavaScript()))
			$dashboardTemplate->setDataArray('JavaScriptPage', [ sprintf('boundaries/%s%s.js', $this->getLocalJavaScript(), DEV ? '' : '.min') ]);

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
	 * @return LoginTercom
	 */
	public function getLoginTercom(): LoginTercom
	{
		if (self::$loginTercom === null)
			throw TercomException::newLoginTercomNotFound();

		return self::$loginTercom;
	}

	/**
	 * @return LoginCustomer
	 */
	public function getLoginCustomer(): LoginCustomer
	{
		if (self::$loginCustomer === null)
			throw TercomException::newLoginCustomerNotFound();

		return self::$loginCustomer;
	}

	/**
	 * @return boolean
	 */
	public function isLoginTercom(): bool
	{
		return self::$loginTercom !== null;
	}

	/**
	 * @return Customer
	 */
	public function getCustomerLogged(): Customer
	{
		return $this->getLoginCustomer()->getCustomerEmployee()->getCustomerProfile()->getCustomer();
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
	 * Verifica se existe uma sessão ativa do qual possua acesso no sistema efetuado, seja ele qual for.
	 * @return bool true se existir um acesso ou false caso contrário.
	 */
	protected function hasLogin(): bool
	{
		return self::$loginCustomer !== null || self::$loginTercom !== null;
	}

	/**
	 * Verifica se existe uma sessão ativa do qual possua acesso no sistema efetuado, seja ele qual for.
	 * @return bool true se existir um acesso ou false caso contrário.
	 */
	protected function doLogin(): bool
	{
		$session = Session::getInstance();
		$session->start();

		if ($session->isSetted(SessionVar::LOGIN_ID) && $session->isSetted(SessionVar::LOGIN_TOKEN) && $session->isSetted(SessionVar::LOGIN_TERCOM_ID))
		{
			$loginTercomControl = new LoginTercomControl();
			self::$loginTercom = $loginTercomControl->getCurrent();
			$this->addJavaScriptConstant(self::JS_CONST_LOGIN_ID, self::$loginTercom->getId());
			$this->addJavaScriptConstant(self::JS_CONST_LOGIN_TOKEN, self::$loginTercom->getToken(), true);
			$this->addJavaScriptConstant(self::JS_CONST_LOGIN_EMPLOYEE_ID, self::$loginTercom->getTercomEmployeeId());
			$this->addJavaScriptConstant(self::JS_CONST_LOGIN_PROFILE_ID, self::$loginTercom->getTercomEmployee()->getTercomProfileId());
			$this->addJavaScriptConstant(self::JS_CONST_IS_LOGIN_TERCOM, 'true');
			return true;
		}

		else if ($session->isSetted(SessionVar::LOGIN_ID) && $session->isSetted(SessionVar::LOGIN_TOKEN) && $session->isSetted(SessionVar::LOGIN_CUSTOMER_ID))
		{
			$loginCustomerControl = new LoginCustomerControl();
			self::$loginCustomer = $loginCustomerControl->getCurrent();
			$this->addJavaScriptConstant(self::JS_CONST_LOGIN_ID, self::$loginCustomer->getId());
			$this->addJavaScriptConstant(self::JS_CONST_LOGIN_TOKEN, self::$loginCustomer->getToken(), true);
			$this->addJavaScriptConstant(self::JS_CONST_LOGIN_EMPLOYEE_ID, self::$loginCustomer->getCustomerEmployeeId());
			$this->addJavaScriptConstant(self::JS_CONST_LOGIN_PROFILE_ID, self::$loginCustomer->getCustomerEmployee()->getCustomerProfileId());
			$this->addJavaScriptConstant(self::JS_CONST_IS_LOGIN_TERCOM, 'false');
			return true;
		}

		return false;
	}

	/**
	 * Redireciona para a página relativamente ao Dashboard.
	 */
	protected function redirectRelative(string $path): void
	{
		header("Location: /dashboard/$path");
	}

	/**
	 * Redireciona para a página inicial do Dashboard.
	 */
	protected function redirectHome(): void
	{
		header('Location: /dashboard');
	}

	/**
	 * Redireciona para a página de acesso.
	 * @param bool $tercom para funcionário TERCOM.
	 */
	protected function redirectLogin(bool $tercom): void
	{
		if ($tercom)
			header('Location: /dashboard/login?tercom');
		else
			header('Location: /dashboard/login');
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

