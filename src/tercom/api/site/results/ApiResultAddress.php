<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\Address;

/**
 * @see ApiResult
 * @see Address
 * @author Andrew
 */

class ApiResultAddress implements ApiResult
{
	/**
	 * @var string mensagem personalidade do resultado.
	 */
	private $message;
	/**
	 * @var Address
	 */
	private $address;

	/**
	 *
	 */
	public function __construct()
	{
		$this->address = new Address();
	}

	/**
	 * @param Address $address
	 */
	public function setAddress(Address $address)
	{
		$this->address = $address;
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
		$array = $this->address->toArray();

		if ($this->message !== null)
			$array['message'] = $this->message;

		return $array;
	}
}

