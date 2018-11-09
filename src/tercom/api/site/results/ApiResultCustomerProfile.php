<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\CustomerProfile;

/**
 * @see ApiResult
 * @see CustomerProfile
 * @author Andrew
 */

class ApiResultCustomerProfile implements ApiResult
{
	/**
	 * @var string mensagem personalidade do resultado.
	 */
	private $message;
	/**
	 * @var CustomerProfile
	 */
	private $customerProfile;

	/**
	 *
	 */
	public function __construct()
	{
		$this->customerProfile = new CustomerProfile();
	}

	/**
	 * @param CustomerProfile $customerProfile
	 */
	public function setCustomerProfile(CustomerProfile $customerProfile)
	{
		$this->customerProfile = $customerProfile;
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
		$array = $this->customerProfile->toArray();

		if ($this->message !== null)
			$array['message'] = $this->message;

		return $array;
	}
}

