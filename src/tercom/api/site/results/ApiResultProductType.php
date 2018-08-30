<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\ProductType;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProductType implements ApiResult
{
	/**
	 * @var ProductType
	 */
	private $productType;
	/**
	 * @var string
	 */
	private $message;

	/**
	 * @return ProductType
	 */

	public function getProductType(): ProductType
	{
		return $this->productType;
	}

	/**
	 * @param ProductType $productType
	 */

	public function setProductType(ProductType $productType)
	{
		$this->productType = $productType;
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
		$array = $this->productType->toArray();

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

