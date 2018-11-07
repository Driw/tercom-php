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
	 * @var string mensagem personalidade do resultado.
	 */
	private $message;
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
	 * @param string $message mensagem personalidade do resultado.
	 */
	public function setMessage($message)
	{
		$this->message = format(func_get_args());
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */
	public function toApiArray():array
	{
		$array = $this->addresses->toArray();

		if ($this->message !== null)
			$array['message'] = $this->message;

		return $array;
	}
}

