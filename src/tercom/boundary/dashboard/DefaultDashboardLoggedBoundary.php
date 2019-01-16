<?php

namespace tercom\boundary\dashboard;

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
}

