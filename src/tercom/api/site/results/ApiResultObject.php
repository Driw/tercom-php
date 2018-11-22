<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use dProject\Primitive\AdvancedObject;
use tercom\core\System;

/**
 * @see ApiResult
 * @see AdvancedObject
 * @author Andrew
 */

class ApiResultObject implements ApiResult
{
	/**
	 * @var string
	 */
	private $message;
	/**
	 * @var AdvancedObject
	 */
	private $object;

	/**
	 * @param string $message
	 */
	public function setMessage(string $message)
	{
		$this->message = format(func_get_args());
	}

	/**
	 * @param AdvancedObject $object
	 */
	public function setObject(AdvancedObject $object)
	{
		$this->object = $object;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */

	public function toApiArray()
	{
		$array = $this->object->toArray(System::isApiOnlyProperties());

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

