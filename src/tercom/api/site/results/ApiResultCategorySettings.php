<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use dProject\Primitive\AdvancedObject;
use tercom\entities\ProductCategory;

class ApiResultCategorySettings extends AdvancedObject implements ApiResult
{
	/**
	 * @var int
	 */
	private $minCategoryNameLen;
	/**
	 * @var int
	 */
	private $maxCategoryNameLen;

	/**
	 *
	 */

	public function __construct()
	{
		$this->minCategoryNameLen = ProductCategory::MIN_NAME_LEN;
		$this->maxCategoryNameLen = ProductCategory::MAX_NAME_LEN;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */

	public function toApiArray():array
	{
		return $this->toArray(true);
	}
}

