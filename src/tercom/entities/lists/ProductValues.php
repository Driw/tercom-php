<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\ProductValue;

class ProductValues extends ArrayList
{
	/**
	 *
	 */

	public function __construct()
	{
		parent::__construct(ProductValue::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */

	public function current():ProductValue
	{
		return parent::current();
	}
}

?>