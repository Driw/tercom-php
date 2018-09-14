<?php

namespace tercom\api\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @see ApiStatusException
 * @author Andrew
 */

class FilterException extends ApiStatusException
{
	/**
	 * @param string $filter
	 */

	public function __construct(string $filter)
	{
		parent::__construct(sprintf('filtro %s inexistente', $filter), ApiStatus::FILTER);
	}
}

