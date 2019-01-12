<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\QuotedProductPrice;

/**
 * Preços de Produto Cotados
 *
 * Classe para especializar a lista no tipo de lista para aceitar apenas preço de produto cotado.
 *
 * @see ArrayList
 * @see QuotedProductPrice
 *
 * @author Andrew
 */

class QuotedProductPrices extends ArrayList
{
	/**
	 * Cria uma nova lista de preço de produto cotado definindo o tipo da lista como preço de produto cotado.
	 */
	public function __construct()
	{
		parent::__construct(QuotedProductPrice::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): QuotedProductPrice
	{
		return parent::current();
	}
}

