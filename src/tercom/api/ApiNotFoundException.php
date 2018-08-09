<?php

namespace tercom\api;

class ApiNotFoundException extends ApiException
{
	public function __construct($previous = null)
	{
		parent::__construct('bad request', HTTP_BAD_REQUEST, $previous);
	}
}

?>