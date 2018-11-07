<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\Addresses;

/**
 * @see ApiResult
 * @see Addresses
 * @author Andrew
 */

class ApiResultAddresses implements ApiResult
{
	/**
	 * @var Addresses
	 */
	private $addresses;

	/**
	 *
	 */
	public function __construct()
	{
		$this->addresses = new Addresses();
	}

	/**
	 *
	 * @param Addresses $addresses
	 */
	public function setAddresses(Addresses $addresses)
	{
		$this->addresses = $addresses;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */
	public function toApiArray():array
	{
		return $this->addresses->toArray();
	}
}

