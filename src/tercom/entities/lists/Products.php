<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\Product;

class Products extends ArrayList
{
	/**
	 *
	 */

	public function __construct()
	{
		parent::__construct(Product::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */

	public function current():Product
	{
		return parent::current();
	}
}

?>