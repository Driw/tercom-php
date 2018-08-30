<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\Products;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProducts implements ApiResult
{
	/**
	 * @var Products
	 */
	private $products;

	/**
	 * @return Products
	 */

	public function getProducts(): Products
	{
		return $this->products;
	}

	/**
	 * @param Products $products
	 */

	public function setProducts(Products $products)
	{
		$this->products = $products;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */

	public function toApiArray()
	{
		return $this->products->toArray();
	}
}

