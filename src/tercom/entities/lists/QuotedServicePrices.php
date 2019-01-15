<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\QuotedServicePrice;

/**
 * Preços de Serviço Cotados
 *
 * Classe para especializar a lista no tipo de lista para aceitar apenas preço de serviço cotado.
 *
 * @see ArrayList
 * @see QuotedServicePrice
 *
 * @author Andrew
 */

class QuotedServicePrices extends ArrayList
{
	/**
	 * Cria uma nova lista de preço de serviço cotado definindo o tipo da lista como preço de serviço cotado.
	 */
	public function __construct()
	{
		parent::__construct(QuotedServicePrice::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): QuotedServicePrice
	{
		return parent::current();
	}
}

