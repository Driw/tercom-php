<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use dProject\Primitive\AdvancedObject;
use tercom\entities\TercomProfile;

/**
 * @see AdvancedObject
 * @see ApiResult
 * @author Andrew
 *
 */
class ApiResultTercomProfileSettings extends AdvancedObject implements ApiResult
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
	private $minAssignmentLevel;
	/**
	 * @var int
	 */
	private $maxAssignmentLevel;

	/**
	 *
	 */
	public function __construct()
	{
		$this->minNameLen = TercomProfile::MIN_NAME_LEN;
		$this->maxNameLen = TercomProfile::MAX_NAME_LEN;
		$this->minAssignmentLevel = TercomProfile::MIN_ASSIGNMENT_LEVEL;
		$this->maxAssignmentLevel = TercomProfile::MAX_ASSIGNMENT_LEVEL;
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

