<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\Phones;

/**
 * @see ApiResult
 * @see Phones
 * @author Andrew
 */

class ApiResultPhones implements ApiResult
{
	/**
	 * @var string mensagem personalidade do resultado.
	 */
	private $message;
	/**
	 * @var Phones
	 */
	private $phones;

	/**
	 *
	 */
	public function __construct()
	{
		$this->phones = new Phones();
	}

	/**
	 *
	 * @param Phones $phones
	 */
	public function setPhones(Phones $phones)
	{
		$this->phones = $phones;
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
		$array = $this->phones->toArray();

		if ($this->message !== null)
			$array['message'] = $this->message;

		return $array;
	}
}

