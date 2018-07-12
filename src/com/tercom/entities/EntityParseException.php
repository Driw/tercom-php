<?php

namespace tercom\Entities;

use ParseError;

class EntityParseException extends ParseError
{
	public function __construct($message, $code = null, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}

?>