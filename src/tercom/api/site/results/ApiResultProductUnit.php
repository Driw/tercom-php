<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\ProductUnit;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProductUnit implements ApiResult
{
	/**
	 * @var ProductUnit
	 */
	private $productUnit;
	/**
	 * @var string
	 */
	private $message;

	/**
	 * @return ProductUnit
	 */

	public function getProductUnit(): ProductUnit
	{
		return $this->productUnit;
	}

	/**
	 * @param ProductUnit $productUnit
	 */

	public function setProductUnit(ProductUnit $productUnit)
	{
		$this->productUnit = $productUnit;
	}

	/**
	 * @return string|NULL
	 */

	public function getMessage(): ?string
	{
		return $this->message;
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

	public function toApiArray()
	{
		$array = $this->productUnit->toArray();

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

