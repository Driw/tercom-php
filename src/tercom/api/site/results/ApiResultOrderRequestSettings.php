<?php

namespace tercom\api\site\results;

use dProject\Primitive\AdvancedObject;
use dProject\restful\ApiResult;
use tercom\entities\OrderRequest;

/**
 * @author Andrew
 */
class ApiResultOrderRequestSettings extends AdvancedObject implements ApiResult
{
	/**
	 * @var int
	 */
	private $minBudget;

	/**
	 *
	 */
	public function __construct()
	{
		$this->minBudget = OrderRequest::MIN_BUDGET;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */
	public function toApiArray(): array
	{
		return $this->toArray(true);
	}
}

