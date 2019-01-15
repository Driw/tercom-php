<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\QuotedOrderService;

/**
 * Cotações de Serviço de Pedido
 *
 * Classe para especializar a lista no tipo de lista para aceitar apenas cotação de serviço pedido.
 *
 * @see ArrayList
 * @see QuotedOrderService
 *
 * @author Andrew
 */

class QuotedOrderServices extends ArrayList
{
	/**
	 * Cria uma nova lista de cotação de serviço de pedido definindo o tipo da lista como cotação de serviço de pedido.
	 */
	public function __construct()
	{
		parent::__construct(QuotedOrderService::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): QuotedOrderService
	{
		return parent::current();
	}
}

