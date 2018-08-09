<?php

namespace tercom\api;

class ApiUnauthorizedException extends ApiException
{
	public function __construct($previous = null)
	{
		parent::__construct('unauthorized', HTTP_UNAUTHORIZED, $previous);
	}
}

?>