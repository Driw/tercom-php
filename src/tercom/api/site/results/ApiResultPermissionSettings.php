<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use dProject\Primitive\AdvancedObject;
use tercom\entities\Permission;

/**
 * @see AdvancedObject
 * @see ApiResult
 * @author Andrew
 *
 */
class ApiResultPermissionSettings extends AdvancedObject implements ApiResult
{
	/**
	 * @var int
	 */
	private $maxPacketNameLen;
	/**
	 * @var int
	 */
	private $maxActionNameLen;
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
		$this->maxPacketNameLen = Permission::MAX_PACKET_NAME_LEN;
		$this->maxActionNameLen = Permission::MAX_ACTION_NAME_LEN;
		$this->minAssignmentLevel = Permission::MIN_ASSIGNMENT_LEVEL;
		$this->maxAssignmentLevel = Permission::MAX_ASSIGNMENT_LEVEL;
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

