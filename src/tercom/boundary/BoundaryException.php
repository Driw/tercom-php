<?php

namespace tercom\boundary;

use Exception;

/**
 * @see Exception
 * @author Andrew
 */

class BoundaryException extends Exception
{
	/**
	 * @var int
	 */
	const CONFIG_NOT_FOUND = 1;
	/**
	 * @var int
	 */
	const CONFIG_PARSE = 2;

	/**
	 * @param string $message [optional]
	 * @param int $code [optional]
	 * @param Exception $previous [optional]
	 */

	public function __construct (string $message = null, int $code = null, ?Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}

