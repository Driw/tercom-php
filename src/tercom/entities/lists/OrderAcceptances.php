<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\OrderAcceptance;

/**
 *
 * @see ArrayList
 * @see OrderAcceptanceProduct
 * @author Andrew
 */

class OrderAcceptances extends ArrayList
{
	/**
	 * Cria uma nova lista de permissões definindo o tipo da lista como permissão.
	 */
	public function __construct()
	{
		parent::__construct(OrderAcceptance::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): OrderAcceptance
	{
		return parent::current();
	}
}

