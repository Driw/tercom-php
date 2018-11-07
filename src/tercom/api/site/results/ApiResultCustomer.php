<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\Customer;

/**
 * @see ApiResult
 * @see Customer
 * @author Andrew
 */

class ApiResultCustomer implements ApiResult
{
	/**
	 * @var string mensagem personalidade do resultado.
	 */
	private $message;
	/**
	 * @var Customer
	 */
	private $customer;

	/**
	 *
	 */
	public function __construct()
	{
		$this->customer = new Customer();
	}

	/**
	 * @param Customer $customer
	 */
	public function setCustomer(Customer $customer)
	{
		$this->customer = $customer;
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
		$array = $this->customer->toArray();

		if ($this->message !== null)
			$array['message'] = $this->message;

		return $array;
	}
}

