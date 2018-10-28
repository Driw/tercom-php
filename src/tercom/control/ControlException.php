<?php

namespace tercom\control;

use Exception;

class ControlException extends Exception
{
	public function __construct($message = null, $code = null, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}

?>