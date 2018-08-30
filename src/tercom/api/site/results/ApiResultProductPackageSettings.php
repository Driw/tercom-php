<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\ProductPackage;
use dProject\Primitive\AdvancedObject;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProductPackageSettings extends AdvancedObject implements ApiResult
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
		$this->minNameLen = ProductPackage::MIN_NAME_LEN;
		$this->maxNameLen = ProductPackage::MAX_NAME_LEN;
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

