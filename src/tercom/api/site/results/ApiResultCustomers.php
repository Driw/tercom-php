<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\Customers;

/**
 * @see ApiResult
 * @see Customers
 * @author Andrew
 */

class ApiResultCustomers implements ApiResult
{
	/**
	 * @var string mensagem personalidade do resultado.
	 */
	private $message;
	/**
	 * @var Customers
	 */
	private $customers;

	/**
	 *
	 */
	public function __construct()
	{
		$this->customers = new Customers();
	}

	/**
	 *
	 * @param Customers $customers
	 */
	public function setCustomers(Customers $customers)
	{
		$this->customers = $customers;
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
		$array = $this->customers->toArray();

		if ($this->message !== null)
			$array['message'] = $this->message;

		return $array;
	}
}

