<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\Product;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProduct implements ApiResult
{
	/**
	 * @var Product
	 */
	private $product;
	/**
	 * @var string
	 */
	private $message;

	/**
	 * @return Product
	 */

	public function getProduct(): Product
	{
		return $this->product;
	}

	/**
	 * @param Product $product
	 */

	public function setProduct(Product $product)
	{
		$this->product = $product;
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
		$array = $this->product->toArray();

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

