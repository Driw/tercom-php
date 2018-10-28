<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\Service;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultService implements ApiResult
{
	/**
	 * @var Service
	 */
	private $service;
	/**
	 * @var string
	 */
	private $message;

	/**
	 * @return Service
	 */
	public function get(): Service
	{
		return $this->service;
	}

	/**
	 * @param Service $service
	 * @return ApiResultService
	 */
	public function setService(Service $service): ApiResultService
	{
		$this->service = $service;
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
	 * @return ApiResultService
	 */
	public function setMessage(string $message): ApiResultService
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
		$array = $this->service->toArray();

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

