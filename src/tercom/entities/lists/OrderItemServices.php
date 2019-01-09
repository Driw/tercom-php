<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\OrderItemService;

/**
 * @see OrderItemService
 * @see ArrayList
 * @author Andrew
 */
class OrderItemServices extends ArrayList
{
	public function __construct()
	{
		parent::__construct(OrderItemService::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): OrderItemService
	{
		return parent::current();
	}

	/**
	 * @param OrderItemService $OrderItemService
	 * @return bool
	 */
	public function replace(OrderItemService $orderItemService): bool
	{
		for ($i = 0; $i < $this->size(); $i++)
			if (($element = $this->elements[$i]) !== null && $element instanceof OrderItemService)
				if ($element->getServiceId() === $orderItemService->getServiceId())
				{
					$this->elements[$i] = $orderItemService;
					return true;
				}

		$this->add($orderItemService);
		return false;
	}
}

