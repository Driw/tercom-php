<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\ProductPackages;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProductPackages implements ApiResult
{
	/**
	 * @var ProductPackages
	 */
	private $productPackages;

	/**
	 * @return ProductPackages
	 */

	public function getProductPackages(): ProductPackages
	{
		return $this->productPackages;
	}

	/**
	 * @param ProductPackages $productPackages
	 */

	public function setProductPackages(ProductPackages $productPackages)
	{
		$this->productPackages = $productPackages;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */

	public function toApiArray()
	{
		return $this->productPackages->toArray();
	}
}

