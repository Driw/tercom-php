<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\ProductUnit;

/**
 * <h1>Telefones</h1>
 *
 * <p>Classe para especializar a lista no tipo de lista para aceitar apenas telefones.</p>
 *
 * @see ArrayList
 * @see ProductUnit
 * @author Andrew
 */

class ProductUnits extends ArrayList
{
	/**
	 * Cria uma nova lista de telefones definindo o tipo da lista como telefone.
	 */

	public function __construct()
	{
		parent::__construct(ProductUnit::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */

	public function current():ProductUnit
	{
		return parent::current();
	}
}

?>