<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\ProductPrice;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProductPrice implements ApiResult
{
	/**
	 * @var ProductPrice
	 */
	private $productPrice;
	/**
	 * @var string
	 */
	private $message;

	/**
	 * @return ProductPrice
	 */

	public function getProductPrice(): ProductPrice
	{
		return $this->productPrice;
	}

	/**
	 * @param ProductPrice $productPrice
	 */

	public function setProductPrice(ProductPrice $productPrice)
	{
		$this->productPrice = $productPrice;
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
		$array = $this->productPrice->toArray();

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

