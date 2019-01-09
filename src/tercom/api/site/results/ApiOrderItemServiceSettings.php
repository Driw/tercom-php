<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use dProject\Primitive\AdvancedObject;
use tercom\entities\OrderItemService;

/**
 * @see ApiResult
 * @author Andrew
 */
class ApiOrderItemServiceSettings extends AdvancedObject implements ApiResult
{
	/**
	 * @var int
	 */
	private $maxObservations;

	/**
	 *
	 */

	public function __construct()
	{
		$this->maxObservations = OrderItemService::MAX_OBSERVATIONS_LEN;
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

