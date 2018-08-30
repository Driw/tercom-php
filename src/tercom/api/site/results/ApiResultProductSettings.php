<?php

namespace tercom\api\site\results;

use dProject\Primitive\AdvancedObject;
use dProject\restful\ApiResult;
use tercom\entities\Product;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProductSettings extends AdvancedObject implements ApiResult
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
	 * @var int
	 */
	private $minDescriptionLen;
	/**
	 * @var int
	 */
	private $maxDescriptionLen;
	/**
	 * @var int
	 */
	private $maxUtilityLen;

	/**
	 *
	 */

	public function __construct()
	{
		$this->minNameLen = Product::MIN_NAME_LEN;
		$this->maxNameLen = Product::MAX_NAME_LEN;
		$this->minDescriptionLen = Product::MIN_DESCRIPTION_LEN;
		$this->maxDescriptionLen = Product::MAX_DESCRIPTION_LEN;
		$this->maxUtilityLen = Product::MAX_UTILITY_LEN;
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

