<?php

namespace tercom\api\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @see ApiStatusException
 * @author Andrew
 */
class RelationshipException extends ApiStatusException
{
	/**
	 * @param string $relationship
	 */
	public function __construct(string $relationship)
	{
		parent::__construct(sprintf('filtro %s inexistente', $relationship), ApiStatus::RELATIONSHIP);
	}
}

