<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\ProductType;
use dProject\Primitive\AdvancedObject;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProductTypeSettings extends AdvancedObject implements ApiResult
{
	/**
	 * @var int
	 */
	private $minNameLen;
	/**
	 * @var int
	 */
	private $maxNameLen;

	/**
	 *
	 */

	public function __construct()
	{
		$this->minNameLen = ProductType::MIN_NAME_LEN;
		$this->maxNameLen = ProductType::MAX_NAME_LEN;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */

	public function toApiArray()
	{
		return $this->toArray();
	}
}

