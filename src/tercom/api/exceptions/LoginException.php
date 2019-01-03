<?php

namespace tercom\api\exceptions;

use dProject\restful\exception\ApiException;
use tercom\api\ApiStatus;

/**
 * @author Andrew
 */
class LoginException extends ApiException
{
	public static function newNotLogged(): LoginException
	{
		return new LoginException('acesso negado por falta de acesso', ApiStatus::NOT_LOGGED);
	}
}

