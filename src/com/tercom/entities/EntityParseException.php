<?php

namespace tercom\Entities;

use tercom\TercomException;

class EntityParseException extends TercomException
{
	public function __construct($message, $code = null, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}

?>