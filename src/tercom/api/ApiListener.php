<?php

namespace tercom\api;

use dProject\restful\ApiConnectionAdapter;

class ApiListener extends ApiConnectionAdapter
{
	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiConnectionAdapter::onGenericException()
	 */

	public function onGenericException($connection, $e)
	{
		$connection->newResponseException($e, $e->getCode());
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiConnectionAdapter::onApiException()
	 */

	public function onApiException($connection, $e)
	{
		$connection->newResponseException($e, $e->getCode());
	}
}

