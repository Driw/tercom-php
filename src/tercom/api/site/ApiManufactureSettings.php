<?php

namespace tercom\api\site;

use dProject\restful\ApiResult;
use dProject\Primitive\AdvancedObject;

class ApiManufactureSettings extends AdvancedObject implements ApiResult
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
		$this->minFantasyNameLen = MIN_FANTASY_NAME_LEN;
		$this->maxFantasyNameLen = MAX_FANTASY_NAME_LEN;
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

