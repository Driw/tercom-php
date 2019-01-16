<?php

namespace tercom\boundary\dashboard;

use dProject\Primitive\Session;
use dProject\restful\template\ApiTemplateResult;
use tercom\core\System;

/**
 *
 *
 * @see DefaultDashboardBoundary
 *
 * @author Andrew
 */
class LoginBoundary extends DefaultDashboardBoundary
{
	/***
	 * {@inheritDoc}
	 * @see \tercom\boundary\dashboard\DefaultDashboardBoundary::isVerifyLogin()
	 */
	public function isVerifyLogin(): bool
	{
		return false;
	}

	/**
	 * {@inheritDoc}
	 * @see \dproject\restful\template\ApiTemplate::callIndex()
	 */
	public function callIndex()
	{
		$content = System::getDashboardConnection()->getContent();
		$get = $content->getGet();
		$filename = $get->isSetted('tercom') ? 'LoginTercom' : 'LoginCustomer';

		$baseTemplate = $this->newLoginBaseTemplate();
		$baseTemplate->addFile('IncludeLogin', "Login/$filename");

		$result = new ApiTemplateResult();
		$result->add($baseTemplate);

		return $result;
	}

	/**
	 *
	 */
	public function callLogout()
	{
		$session = Session::getInstance();
		$session->destroy();
	}
}

