<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\OrderQuote;

/**
 * Lista de Cotação de Pedidos
 *
 * Classe para especializar a lista no tipo de lista para aceitar apenas cotação de pedido.
 *
 * @see ArrayList
 * @see OrderQuote
 *
 * @author Andrew
 */

class OrderQuotes extends ArrayList
{
	/**
	 * Cria uma nova lista de cotação de pedidos definindo o tipo da lista como cotação de pedido.
	 */
	public function __construct()
	{
		parent::__construct(OrderQuote::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): OrderQuote
	{
		return parent::current();
	}
}

