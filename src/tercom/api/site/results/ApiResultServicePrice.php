<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\ServicePrice;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultServicePrice implements ApiResult
{
	/**
	 * @var ServicePrice
	 */
	private $servicePrice;
	/**
	 * @var string
	 */
	private $message;

	/**
	 * @return ServicePrice
	 */
	public function get(): ServicePrice
	{
		return $this->servicePrice;
	}

	/**
	 * @param ServicePrice $servicePrice
	 * @return ApiResultServicePrice
	 */
	public function setServicePrice(ServicePrice $servicePrice): ApiResultServicePrice
	{
		$this->servicePrice = $servicePrice;
		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getMessage(): ?string
	{
		return $this->message;
	}

	/**
	 * @param string $message
	 * @return ApiResultServicePrice
	 */
	public function setMessage(string $message): ApiResultServicePrice
	{
		$this->message = format(func_get_args());
		return $this;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */
	public function toApiArray()
	{
		$array = $this->servicePrice->toArray();

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

