<?php

namespace tercom\api;

use Throwable;
use dProject\restful\ApiConnection;
use dProject\restful\ApiConnectionAdapter;
use dProject\restful\ApiResponse;
use tercom\Encryption;

class ApiListener extends ApiConnectionAdapter
{
	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiConnectionAdapter::onGenericException()
	 */

	public function onGenericException($connection, $e)
	{
		$this->showExceptionResponse($connection, $e);
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiConnectionAdapter::onApiException()
	 */

	public function onApiException($connection, $e)
	{
		$this->showExceptionResponse($connection, $e);
	}

	/**
	 * @param ApiConnection $connection
	 * @param Throwable $e
	 */

	private function showExceptionResponse(ApiConnection $connection, Throwable $e)
	{
		$trace = jTraceEx($e);
		$response = new ApiResponse();
		{
			if ($e instanceof ApiStatusException)
				$response->setStatus($e->getCode());
			else
				$response->setStatus(ApiResponse::API_FAILURE);
		}
		$response->setMessage($e->getMessage());
		$response->setResult(DEV ? explode(PHP_EOL, $trace) : [ (new Encryption())->encrypt($trace) ]);

		$connection->showResponse($response);
	}
}

