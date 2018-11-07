<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use dProject\Primitive\AdvancedObject;
use tercom\entities\Customer;

/**
 * @see AdvancedObject
 * @see ApiResult
 * @author Andrew
 *
 */
class ApiResultCustomerSettings extends AdvancedObject implements ApiResult
{
	/**
	 * @var int
	 */
	private $maxStateRegistryLen;
	/**
	 * @var int
	 */
	private $maxEmailLen;
	/**
	 * @var int
	 */
	private $minCompanyNameLen;
	/**
	 * @var int
	 */
	private $maxCompanyNameLen;
	/**
	 * @var int
	 */
	private $minFantasyNameLen;
	/**
	 * @var int
	 */
	private $maxFantasyNameLen;

	/**
	 *
	 */
	public function __construct()
	{
		$this->maxStateRegistryLen = Customer::MAX_STATE_REGISTRY_LEN;
		$this->maxEmailLen = Customer::MAX_EMAIL_LEN;
		$this->minCompanyNameLen = Customer::MIN_COMPANY_NAME_LEN;
		$this->maxCompanyNameLen = Customer::MAX_COMPANY_NAME_LEN;
		$this->minFantasyNameLen = Customer::MIN_FANTASY_NAME_LEN;
		$this->maxFantasyNameLen = Customer::MAX_FANTASY_NAME_LEN;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */
	public function toApiArray():array
	{
		return $this->toArray(true);
	}
}

