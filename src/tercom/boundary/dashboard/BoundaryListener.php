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
		die(str_replace(PHP_EOL, "<br>".PHP_EOL, jTraceEx($e)));
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiConnectionAdapter::onGenericException()
	 */

	public function onGenericException($connection, $e)
	{
		die(str_replace(PHP_EOL, "<br>".PHP_EOL, jTraceEx($e)));
	}
}

