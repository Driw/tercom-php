<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\ProductPrice;

class ProductPrices extends ArrayList
{
	/**
	 *
	 */

	public function __construct()
	{
		parent::__construct(ProductPrice::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */

	public function current():ProductPrice
	{
		return parent::current();
	}
}

?>