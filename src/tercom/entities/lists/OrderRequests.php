<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\OrderRequest;

/**
 * @see OrderRequest
 * @see ArrayList
 * @author Andrew
 */
class OrderRequests extends ArrayList
{
	/**
	 * Cria uma nova lista de solicitações de pedidos definindo o tipo da lista como solicitação de pedido.
	 */
	public function __construct()
	{
		parent::__construct(OrderRequest::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): OrderRequest
	{
		return parent::current();
	}
}

