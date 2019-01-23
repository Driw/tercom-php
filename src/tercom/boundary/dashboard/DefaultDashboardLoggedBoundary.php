<?php

namespace tercom\boundary\dashboard;

use dProject\restful\ApiContent;

/**
 *
 *
 * @see DefaultDashboardBoundary
 *
 * @author Andrew
 */
abstract class DefaultDashboardLoggedBoundary extends DefaultDashboardBoundary
{
	/**
	 * {@inheritDoc}
	 * @see \tercom\boundary\dashboard\DefaultDashboardBoundary::isVerifyLogin()
	 */
	public function isVerifyLogin(): bool
	{
		return true;
	}

	/**
	 * @param ApiContent $content
	 * @return int
	 */
	protected function getCustomerId(ApiContent $content): int
	{
		return $this->isLoginTercom() ? $content->getParameters()->getInt('idCustomer') : $this->getLoginCustomer()->getCustomerEmployee()->getCustomerProfile()->getCustomerId();
	}
}

