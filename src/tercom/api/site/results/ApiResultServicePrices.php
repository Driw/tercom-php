<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\ServicePrices;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultServicePrices implements ApiResult
{
	/**
	 * @var ServicePrices
	 */
	private $servicePrices;
	/**
	 * @var string
	 */
	private $message;

	/**
	 * @return ServicePrices
	 */
	public function get(): ServicePrices
	{
		return $this->servicePrices;
	}

	/**
	 * @param ServicePrices $servicePrices
	 * @return ApiResultServicePrices
	 */
	public function setServicePrices(ServicePrices $servicePrices): ApiResultServicePrices
	{
		$this->servicePrices = $servicePrices;
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
	 * @return ApiResultServicePrices
	 */
	public function setMessage(string $message): ApiResultServicePrices
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
		$array = $this->servicePrices->toArray();

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

