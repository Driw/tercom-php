<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\ProductPrice;
use dProject\Primitive\AdvancedObject;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultProductPriceSettings extends AdvancedObject implements ApiResult
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
	private $minAmount;
	/**
	 * @var int
	 */
	private $maxAmount;
	/**
	 * @var int
	 */
	private $minPrice;
	/**
	 * @var int
	 */
	private $maxPrice;

	/**
	 *
	 */

	public function __construct()
	{
		$this->minNameLen = ProductPrice::MIN_NAME_LEN;
		$this->maxNameLen = ProductPrice::MAX_NAME_LEN;
		$this->minAmount = ProductPrice::MIN_AMOUNT;
		$this->maxAmount = ProductPrice::MAX_AMOUNT;
		$this->minPrice = ProductPrice::MIN_PRICE;
		$this->maxPrice = ProductPrice::MAX_PRICE;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */

	public function toApiArray()
	{
		return $this->toArray();
	}
}

