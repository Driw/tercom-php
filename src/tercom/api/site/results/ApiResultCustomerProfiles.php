<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\CustomerProfiles;

/**
 * @see ApiResult
 * @see CustomerProfiles
 * @author Andrew
 */

class ApiResultCustomerProfiles implements ApiResult
{
	/**
	 * @var string mensagem personalidade do resultado.
	 */
	private $message;
	/**
	 * @var CustomerProfiles
	 */
	private $customerProfiles;

	/**
	 *
	 */
	public function __construct()
	{
		$this->customerProfiles = new CustomerProfiles();
	}

	/**
	 *
	 * @param CustomerProfiles $customerProfiles
	 */
	public function setCustomerProfiles(CustomerProfiles $customerProfiles)
	{
		$this->customerProfiles = $customerProfiles;
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
		$array = $this->customerProfiles->toArray();

		if ($this->message !== null)
			$array['message'] = $this->message;

		return $array;
	}
}

