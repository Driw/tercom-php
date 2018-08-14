<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\Manufactures;

class ApiResultManufactures implements ApiResult
{
	/**
	 * @var string
	 */
	private $message;
	/**
	 * @var Manufactures
	 */
	private $manufactures;

	/**
	 *
	 */

	public function __construct()
	{
		$this->manufactures = new Manufactures();
	}

	/**
	 * @param Manufactures $manufactures
	 */

	public function setManufactures(Manufactures $manufactures)
	{
		$this->manufactures = $manufactures;
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
		$array = $this->manufactures->toArray();

		if ($this->message !== null)
			$array['message'] = $this->message;

		return $array;
	}
}

