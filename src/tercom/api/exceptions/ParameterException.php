<?php

namespace tercom\api\exceptions;

use dProject\Primitive\ArrayDataException;
use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @see ApiException
 * @author Andrew
 */

class ParameterException extends ApiStatusException
{
	/**
	 * @param ArrayDataException $e
	 */

	public function __construct(ArrayDataException $e)
	{
		switch ($e->getCode())
		{
			case ArrayDataException::MISS_PARAM:
				parent::__construct(sprintf('parâmetro %s não informado', $e->getMessage()), ApiStatus::PARAMETER, $e);
				break;

			case ArrayDataException::PARSE_TYPE:
				parent::__construct(sprintf('parâmetro %s inválido', $e->getMessage()), ApiStatus::PARSE_PARAMETER, $e); // FIXME devemos possuir um código diferente?
				break;
		}
	}
}

