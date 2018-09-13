<?php

namespace tercom\entities;

use tercom\TercomException;

class EntityParseException extends TercomException
{
	public function __construct($message, $code = null, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	public static function new(string $format)
	{
		$args = func_get_args();
		array_shift($args); // Remover format

		return new EntityParseException(vsprintf($format, $args));
	}
}

?>