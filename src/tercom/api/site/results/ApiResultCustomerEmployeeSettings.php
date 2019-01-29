<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use dProject\Primitive\AdvancedObject;
use tercom\entities\CustomerEmployee;

/**
 * @see AdvancedObject
 * @see ApiResult
 * @author Andrew
 *
 */
class ApiResultCustomerEmployeeSettings extends AdvancedObject implements ApiResult
{
	/**
	 * @var int
	 */
	private $minNameLen;
	/**
	 * @var int
	 */
	private $maxNameLen;
	/**
	 * @var int
	 */
	private $maxEmailLen;
	/**
	 * @var int
	 */
	private $minPasswordLen;
	/**
	 * @var int
	 */
	private $maxPasswordLen;
	/**
	 * @var ApiResultPhoneSettings
	 */
	private $phoneSettings;

	/**
	 *
	 */
	public function __construct()
	{
		$this->minNameLen = CustomerEmployee::MIN_NAME_LEN;
		$this->maxNameLen = CustomerEmployee::MAX_NAME_LEN;
		$this->maxEmailLen = CustomerEmployee::MAX_EMAIL_LEN;
		$this->minPasswordLen = CustomerEmployee::MIN_PASSWORD_LEN;
		$this->maxPasswordLen = CustomerEmployee::MAX_PASSWORD_LEN;
		$this->phoneSettings = new ApiResultPhoneSettings();
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

