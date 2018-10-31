<?php

namespace tercom\api\site\results;

use dProject\Primitive\AdvancedObject;
use dProject\restful\ApiResult;
use tercom\entities\ServicePrice;

class ApiResultServicePriceSettings extends AdvancedObject implements ApiResult
{
	private $minNameLen;
	private $maxNameLen;
	private $maxAdditionalDescriptionLen;
	private $minPrice;

	public function __construct()
	{
		$this->minNameLen = ServicePrice::MIN_NAME_LEN;
		$this->maxNameLen = ServicePrice::MAX_NAME_LEN;
		$this->maxAdditionalDescriptionLen = ServicePrice::MAX_ADDITIONAL_DESCRIPTION;
		$this->minPrice = ServicePrice::MIN_PRICE;
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

