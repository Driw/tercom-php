<?php

namespace tercom\api;

use Throwable;
use dProject\restful\ApiConnection;
use dProject\restful\ApiConnectionAdapter;
use dProject\restful\ApiResponse;
use tercom\Encryption;
use dProject\Primitive\ArrayDataException;
use tercom\TercomException;

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
		$response->setMessage($e->getMessage());
		$response->setResult(DEV ? explode(PHP_EOL, $trace) : [ (new Encryption())->encrypt($trace) ]);

		if ($e instanceof ApiStatusException || $e instanceof TercomException)
			$response->setStatus($e->getCode());

		else if ($e instanceof ArrayDataException)
		{
			switch ($e->getCode())
			{
				case ArrayDataException::MISS_PARAM:
					$response->setStatus(ApiResponse::API_MISS_PARAM);
					$response->setMessage(format('parâmetro %s não informado', $e->getMessage()));
					break;

				case ArrayDataException::PARSE_TYPE:
					$response->setStatus(ApiResponse::API_MISS_PARAM);
					$response->setMessage(format('parâmetro %s', $e->getMessage()));
					break;
			}
		}
		else
			$response->setStatus(ApiResponse::API_FAILURE);

		$connection->showResponse($response);
	}
}

