<?php

namespace tercom\entities;

use tercom\TercomException;

class EntityParseException extends TercomException
{
	public static function new(string $format)
	{
		$args = func_get_args();
		array_shift($args); // Remover format

		return new EntityParseException(vsprintf($format, $args));
	}
}

?>