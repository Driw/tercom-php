<?php

namespace tercom\control;

class ControlValidationException extends ControlException
{
	public function __construct($message = null, $code = null, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}

?>