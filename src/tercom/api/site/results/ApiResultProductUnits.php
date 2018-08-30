<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\ProductUnits;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProductUnits implements ApiResult
{
	/**
	 * @var ProductUnits
	 */
	private $productUnits;

	/**
	 * @return ProductUnits
	 */

	public function getProductUnits(): ProductUnits
	{
		return $this->productUnits;
	}

	/**
	 * @param ProductUnits $productUnits
	 */

	public function setProductUnits(ProductUnits $productUnits)
	{
		$this->productUnits = $productUnits;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */

	public function toApiArray()
	{
		return $this->productUnits->toArray();
	}
}

