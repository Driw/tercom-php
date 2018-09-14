<?php

namespace tercom\api\exceptions;

use tercom\api\ApiStatusException;
use tercom\api\ApiStatus;

/**
 * @see ApiStatusException
 * @author Andrew
 */

class ProviderException extends ApiStatusException
{
	/**
	 * @return ProviderException
	 */

	public static function newNotFound(): ProviderException
	{
		return new ProviderException('fornecedor não encontrado', ApiStatus::PROVIDER_NOTFOUND);
	}
}

