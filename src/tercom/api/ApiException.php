<?php

namespace tercom\api;

use tercom\TercomException;

class ApiException extends TercomException
{
	public function __construct($message = null, $code = null, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}

?>