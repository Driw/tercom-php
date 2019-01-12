<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\QuotedOrderProduct;

/**
 * Cotações de Produto de Pedido
 *
 * Classe para especializar a lista no tipo de lista para aceitar apenas cotação de produto de pedido.
 *
 * @see ArrayList
 * @see QuotedOrderProduct
 *
 * @author Andrew
 */

class QuotedOrderProducts extends ArrayList
{
	/**
	 * Cria uma nova lista de cotação de produto de pedido definindo o tipo da lista como cotação de produto de pedido.
	 */
	public function __construct()
	{
		parent::__construct(QuotedOrderProduct::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): QuotedOrderProduct
	{
		return parent::current();
	}
}

