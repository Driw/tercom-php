<?php

namespace tercom\boundary\dashboard;

use dProject\Primitive\Session;
use dProject\restful\ApiContent;
use dProject\restful\template\ApiTemplateResult;
use tercom\SessionVar;
use tercom\boundary\BoundaryConfigs;
use tercom\control\LoginCustomerControl;
use tercom\control\LoginTercomControl;
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

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onLogin(ApiContent $content): ApiTemplateResult
	{
		if ($this->hasLogin())
			$this->redirectHome();

		$get = $content->getGet();
		$post = $content->getPost();
		$isTercom = $get->isSetted('tercom') || $post->isSetted('tercom');

		if ($post->isSetted('email') && $post->isSetted('password'))
		{
			$email = $post->getString('email');
			$password = $post->getString('password');
			$session = Session::getInstance();
			$session->start();

			if ($isTercom)
			{
				$loginTercomControl = new LoginTercomControl();
				$loginTercom = $loginTercomControl->newLoginTercom($email, $password, $_SERVER['HTTP_USER_AGENT']);
				$loginTercomControl->add($loginTercom);
				$session->setInt(SessionVar::LOGIN_TERCOM_ID, $loginTercom->getTercomEmployeeId());
				$session->setInt(SessionVar::LOGIN_ID, $loginTercom->getId());
				$session->setString(SessionVar::LOGIN_TOKEN, $loginTercom->getToken());
				$this->redirectHome();
			}

			else
			{
				$loginCustomerControl = new LoginCustomerControl();
				$loginCustomer = $loginCustomerControl->newLoginCustomer($email, $password, $_SERVER['HTTP_USER_AGENT']);
				$loginCustomerControl->add($loginCustomer);
				$session->setInt(SessionVar::LOGIN_CUSTOMER_ID, $loginCustomer->getCustomerEmployeeId());
				$session->setInt(SessionVar::LOGIN_ID, $loginCustomer->getId());
				$session->setString(SessionVar::LOGIN_TOKEN, $loginCustomer->getToken());
				$this->redirectHome();
			}
		}

		return $this->newLoginTemplate($isTercom);
	}

	/**
	 * @param ApiContent $content
	 * @return ApiTemplateresult
	 */
	public function onLogout(ApiContent $content): ApiTemplateresult
	{
		if (!$this->hasLogin())
			$this->redirectLogin(false);

		$session = Session::getInstance();
		$isTercom = $session->isSetted(SessionVar::LOGIN_TERCOM_ID);

		try {
			if ($isTercom)
			{
				$loginTercomControl = new LoginTercomControl();
				$loginTercomControl->logout($this->getLoginTercom());
				$session->destroy();
				$this->redirectLogin(true);
			}

			else
			{
				$loginCustomerControl = new LoginCustomerControl();
				$loginCustomerControl->logout($this->getLoginCustomer());
				$session->destroy();
				$this->redirectLogin(false);
			}

		} catch (\Exception $e) {
			$session->destroy();
			$this->redirectLogin($isTercom);
		}

		$this->redirectHome();

		return $this->newLoginTemplate($isTercom);
	}

	/**
	 *
	 * @param bool $isTercom
	 * @return ApiTemplateResult
	 */
	private function newLoginTemplate(bool $isTercom): ApiTemplateResult
	{
		$filename = $isTercom ? 'LoginTercom' : 'LoginCustomer';
		$this->setLocalJavaScript("Login/$filename");

		$baseTemplate = $this->newLoginBaseTemplate();
		$baseTemplate->addFile('IncludeLogin', "Login/$filename");

		$result = new ApiTemplateResult();
		$result->add($baseTemplate);

		return $result;
	}
}

