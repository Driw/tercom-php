<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use dProject\Primitive\AdvancedObject;
use tercom\entities\OrderItemProduct;

/**
 * @see ApiResult
 * @author Andrew
 */
class ApiOrderItemProductSettings extends AdvancedObject implements ApiResult
{
	/**
	 * @var int
	 */
	private $maxObservationsLen;

	/**
	 *
	 */

	public function __construct()
	{
		$this->maxObservationsLen = OrderItemProduct::MAX_OBSERVATIONS_LEN;
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

