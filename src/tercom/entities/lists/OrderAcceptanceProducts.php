<?php

namespace tercom\entities\lists;

use tercom\entities\OrderAcceptanceProduct;
use tercom\ArrayList;

/**
 *
 * @see ArrayList
 * @see OrderAcceptance
 * @author Andrew
 */

class OrderAcceptanceProducts extends ArrayList
{
	/**
	 * Cria uma nova lista de preços de produto aceito.
	 */
	public function __construct()
	{
		parent::__construct(OrderAcceptanceProduct::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): OrderAcceptanceProduct
	{
		return parent::current();
	}
}

