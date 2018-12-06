<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use dProject\Primitive\AdvancedObject;
use tercom\entities\Manufacturer;

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
		$this->minFantasyNameLen = Manufacturer::MIN_FANTASY_NAME_LEN;
		$this->maxFantasyNameLen = Manufacturer::MAX_FANTASY_NAME_LEN;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */
	public function toApiArray(): array
	{
		return $this->toArray(true);
	}
}

