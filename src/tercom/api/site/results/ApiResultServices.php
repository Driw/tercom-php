<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\Services;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultServices implements ApiResult
{
	/**
	 * @var Services
	 */
	private $services;
	/**
	 * @var string
	 */
	private $message;

	/**
	 * @return Services
	 */
	public function get(): Services
	{
		return $this->services;
	}

	/**
	 * @param Services $services
	 * @return ApiResultServices
	 */
	public function setServices(Services $services): ApiResultServices
	{
		$this->services = $services;
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
	 * @return ApiResultServices
	 */
	public function setMessage(string $message): ApiResultServices
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
		$array = $this->services->toArray();

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

