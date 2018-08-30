<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\ProductTypes;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProductTypes implements ApiResult
{
	/**
	 * @var ProductTypes
	 */
	private $productTypes;

	/**
	 * @return ProductTypes
	 */

	public function getProductTypes(): ProductTypes
	{
		return $this->productTypes;
	}

	/**
	 * @param ProductTypes $productTypes
	 */

	public function setProductTypes(ProductTypes $productTypes)
	{
		$this->productTypes = $productTypes;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */

	public function toApiArray()
	{
		return $this->productTypes->toArray();
	}
}

