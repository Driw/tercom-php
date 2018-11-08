<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\Phone;

/**
 * @see ApiResult
 * @see Phone
 * @author Andrew
 */

class ApiResultPhone implements ApiResult
{
	/**
	 * @var string mensagem personalidade do resultado.
	 */
	private $message;
	/**
	 * @var Phone
	 */
	private $phone;

	/**
	 *
	 */
	public function __construct()
	{
		$this->phone = new Phone();
	}

	/**
	 * @param Phone $phone
	 */
	public function setPhone(Phone $phone)
	{
		$this->phone = $phone;
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
		$array = $this->phone->toArray();

		if ($this->message !== null)
			$array['message'] = $this->message;

		return $array;
	}
}

