<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\Manufacture;

class ApiResultManufacture implements ApiResult
{
	/**
	 * @var string
	 */
	private $message;
	/**
	 * @var Manufacture
	 */
	private $manufacture;

	/**
	 *
	 */

	public function __construct()
	{
		$this->manufacture = new Manufacture();
	}

	/**
	 * @param Manufacture $manufacture
	 */

	public function setManufacture(Manufacture $manufacture)
	{
		$this->manufacture = $manufacture;
	}

	/**
	 * @param string $message
	 */

	public function setMessage(string $message)
	{
		$this->message = $message;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */

	public function toApiArray():array
	{
		$array = $this->manufacture->toArray();

		if ($this->message !== null)
			$array['message'] = $this->message;

		return $array;
	}
}

