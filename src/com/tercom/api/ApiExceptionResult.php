<?php

namespace tercom\api;

use Exception;

class ApiExceptionResult extends APiFatalError
{
	public function __construct(Exception $expcetion)
	{
		$this->setExpcetion($expcetion);
	}

	public function setExpcetion(Exception $expcetion)
	{
		if ($expcetion == null)
		{
			$this->setErrorCode(0);
			$this->setTarget(0);
			$this->setSource('');
		}

		else
		{
			$this->setErrorCode($expcetion->getCode());
			$this->setTarget($expcetion->getLine());
			$this->setSource($expcetion->getFile());
		}
	}
}

?>