<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\ProductPackage;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProductPackage implements ApiResult
{
	/**
	 * @var ProductPackage
	 */
	private $productPackage;
	/**
	 * @var string
	 */
	private $message;

	/**
	 * @return ProductPackage
	 */

	public function getProductPackage(): ProductPackage
	{
		return $this->productPackage;
	}

	/**
	 * @param ProductPackage $productPackage
	 */

	public function setProductPackage(ProductPackage $productPackage)
	{
		$this->productPackage = $productPackage;
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
		$array = $this->productPackage->toArray();

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

