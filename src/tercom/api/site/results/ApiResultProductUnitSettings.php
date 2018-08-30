<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\ProductUnit;
use dProject\Primitive\AdvancedObject;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProductUnitSettings extends AdvancedObject implements ApiResult
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
		$this->minNameLen = ProductUnit::MIN_NAME_LEN;
		$this->maxNameLen = ProductUnit::MAX_NAME_LEN;
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

