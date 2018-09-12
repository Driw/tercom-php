<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\ProductPrices;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProductPrices implements ApiResult
{
	/**
	 * @var ProductPrices
	 */
	private $productPrices;

	/**
	 * @return ProductPrices
	 */

	public function getProductPrices(): ProductPrices
	{
		return $this->productPrices;
	}

	/**
	 * @param ProductPrices $productPrices
	 */

	public function setProductPrices(ProductPrices $productPrices)
	{
		$this->productPrices = $productPrices;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */

	public function toApiArray()
	{
		return $this->productPrices->toArray();
	}
}

