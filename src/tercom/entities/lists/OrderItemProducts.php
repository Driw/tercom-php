<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\OrderItemProduct;

/**
 * @see Address
 * @see ArrayList
 * @author Andrew
 */
class OrderItemProducts extends ArrayList
{
	public function __construct()
	{
		parent::__construct(OrderItemProduct::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): OrderItemProduct
	{
		return parent::current();
	}

	/**
	 * @param OrderItemProduct $oriderItemProduct
	 * @return bool
	 */
	public function replace(OrderItemProduct $oriderItemProduct): bool
	{
		for ($i = 0; $i < $this->size(); $i++)
			if (($element = $this->elements[$i]) !== null && $element instanceof OrderItemProduct)
				if ($element->getProductId() === $oriderItemProduct->getProductId())
				{
					$this->elements[$i] = $oriderItemProduct;
					return true;
				}

		$this->add($oriderItemProduct);
		return false;
	}
}

