<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\Manufacturer;

/**
 * Lista de Fabricantes
 *
 * Classe para especializar a lista no tipo de lista para aceitar apenas telefones.
 *
 * @see ArrayList
 * @see Manufacturer
 *
 * @author Andrew
 */

class Manufacturers extends ArrayList
{
	/**
	 * Cria uma nova lista de telefones definindo o tipo da lista como telefone.
	 */
	public function __construct()
	{
		parent::__construct(Manufacturer::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current():Manufacturer
	{
		return parent::current();
	}
}

