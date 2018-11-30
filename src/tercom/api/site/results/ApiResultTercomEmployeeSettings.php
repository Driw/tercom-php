<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use dProject\Primitive\AdvancedObject;
use tercom\entities\TercomEmployee;

/**
 * @see AdvancedObject
 * @see ApiResult
 * @author Andrew
 *
 */
class ApiResultTercomEmployeeSettings extends AdvancedObject implements ApiResult
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
	private $maxEmailLen;
	/**
	 * @var int
	 */
	private $minPasswordLen;
	/**
	 * @var int
	 */
	private $maxPasswordLen;

	/**
	 *
	 */
	public function __construct()
	{
		$this->minNameLen = TercomEmployee::MIN_NAME_LEN;
		$this->maxNameLen = TercomEmployee::MAX_NAME_LEN;
		$this->maxEmailLen = TercomEmployee::MAX_EMAIL_LEN;
		$this->minPasswordLen = TercomEmployee::MIN_PASSWORD_LEN;
		$this->maxPasswordLen = TercomEmployee::MAX_PASSWORD_LEN;
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

