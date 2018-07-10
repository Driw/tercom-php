<?php

namespace tercom\api;

class ApiMethodNotAllowedException extends ApiException
{
	public function __construct($previous = null)
	{
		parent::__construct('method not allowed', HTTP_METHOD_NOT_ALLOWED, $previous);
	}
}

?>