<?php

namespace tercom\entities\lists;

use tercom\entities\OrderAcceptanceService;
use tercom\ArrayList;

/**
 *
 * @see ArrayList
 * @see OrderAcceptance
 * @author Andrew
 */

class OrderAcceptanceServices extends ArrayList
{
	/**
	 * Cria uma nova lista de preços de serviço aceito.
	 */
	public function __construct()
	{
		parent::__construct(OrderAcceptanceService::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): OrderAcceptanceService
	{
		return parent::current();
	}
}

