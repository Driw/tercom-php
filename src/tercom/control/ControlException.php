<?php

namespace tercom\control;

use tercom\TercomException;

/**
 * @see TercomException
 * @author andrews
 */
class ControlException extends TercomException
{
	/**
	 *
	 * @param string $format
	 * @param array ...$args
	 * @return ControlException
	 */
	public static function new($format, ... $args): ControlException
	{
		return new ControlException(vsprintf($format, $args));
	}
}

