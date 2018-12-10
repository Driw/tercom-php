<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\ProductUnit;

/**
 * Lista para Unidade de Produto
 *
 * Classe para especializar a lista no tipo de lista para aceitar apenas unidades de produto.
 *
 * @see ArrayList
 * @see ProductUnit
 * @author Andrew
 */
class ProductUnits extends ArrayList
{
	/**
	 * Cria uma nova lista para objetos do tipo unidades de produto.
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

