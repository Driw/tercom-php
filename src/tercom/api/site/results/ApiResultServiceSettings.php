<?php

namespace tercom\api\site\results;

use dProject\Primitive\AdvancedObject;
use dProject\restful\ApiResult;
use tercom\entities\Service;

class ApiResultServiceSettings extends AdvancedObject implements ApiResult
{
	private $minNameLen;
	private $maxNameLen;
	private $maxDescriptionLen;

	public function __construct()
	{
		$this->minNameLen = Service::MIN_NAME_LEN;
		$this->maxNameLen = Service::MAX_NAME_LEN;
		$this->maxDescriptionLen = Service::MAX_DESCRIPTION_LEN;
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

