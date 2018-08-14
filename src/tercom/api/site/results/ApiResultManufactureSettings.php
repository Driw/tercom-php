<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use dProject\Primitive\AdvancedObject;
use tercom\entities\Manufacture;

class ApiResultManufactureSettings extends AdvancedObject implements ApiResult
{
	/**
	 * @var int
	 */
	private $minFantasyNameLen;
	/**
	 * @var int
	 */
	private $maxFantasyNameLen;

	/**
	 *
	 */

	public function __construct()
	{
		$this->minFantasyNameLen = Manufacture::MIN_FANTASY_NAME_LEN;
		$this->maxFantasyNameLen = Manufacture::MAX_FANTASY_NAME_LEN;
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

