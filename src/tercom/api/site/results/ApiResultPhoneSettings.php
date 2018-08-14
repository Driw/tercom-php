<?php

namespace tercom\api\site\results;

use dProject\Primitive\AdvancedObject;
use dProject\restful\ApiResult;
use tercom\entities\Phone;

class ApiResultPhoneSettings extends AdvancedObject implements ApiResult
{
	private $minDDD;
	private $maxDDD;
	private $minNumberLen;
	private $maxNumberLen;

	public function __construct()
	{
		$this->minDDD = Phone::MIN_DDD;
		$this->maxDDD = Phone::MAX_DDD;
		$this->minNumberLen = Phone::MIN_NUMBER_LEN;
		$this->maxNumberLen = Phone::MAX_NUMBER_LEN;
	}

	public function toApiArray(): array
	{
		return $this->toArray(true);
	}
}

?>