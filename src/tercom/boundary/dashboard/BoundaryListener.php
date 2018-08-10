<?php

namespace tercom\boundary\dashboard;

use dProject\restful\ApiConnectionAdapter;

/**
 * @see ApiConnectionAdapter
 * @author Andrew
 */

class BoundaryListener extends ApiConnectionAdapter
{
	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiConnectionAdapter::onApiException()
	 */

	public function onApiException($connection, $e)
	{
		$connection->newResponseException($e, $e->getCode());
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiConnectionAdapter::onGenericException()
	 */

	public function onGenericException($connection, $e)
	{
		$connection->newResponseException($e, $e->getCode());
	}
}

