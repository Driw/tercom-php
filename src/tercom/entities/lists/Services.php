<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\Service;

class Services extends ArrayList
{
	/**
	 *
	 */

	public function __construct()
	{
		parent::__construct(Service::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */

	public function current():Service
	{
		return parent::current();
	}
}

?>