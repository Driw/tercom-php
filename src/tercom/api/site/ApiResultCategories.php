<?php

namespace tercom\api\site;

use dProject\restful\ApiResult;
use tercom\entities\lists\ProductCategories;

/**
 *
 * @author Andrew
 */

class ApiResultCategories implements ApiResult
{
	/**
	 * @var ProductCategories categoria de produto.
	 */
	private $productCategories;

	/**
	 *
	 */

	public function __construct()
	{
		$this->productCategories = new ProductCategories();
	}

	/**
	 * @param ProductCategories $productCategories categoria de produto.
	 */

	public function setProductCategories(ProductCategories $productCategory)
	{
		$this->productCategories = $productCategory;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */

	public function toApiArray():array
	{
		return $this->productCategories->toArray();
	}
}

