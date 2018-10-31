<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\ServicePrice;

class ServicePrices extends ArrayList
{
	/**
	 *
	 */

	public function __construct()
	{
		parent::__construct(ServicePrice::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */

	public function current():ServicePrice
	{
		return parent::current();
	}
}

?>