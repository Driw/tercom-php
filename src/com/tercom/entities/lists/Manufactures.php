<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\Manufacture;

/**
 * <h1>Telefones</h1>
 *
 * <p>Classe para especializar a lista no tipo de lista para aceitar apenas telefones.</p>
 *
 * @see ArrayList
 * @see Manufacture
 * @author Andrew
 */

class Manufactures extends ArrayList
{
	/**
	 * Cria uma nova lista de telefones definindo o tipo da lista como telefone.
	 */

	public function __construct()
	{
		parent::__construct(Manufacture::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */

	public function current():Manufacture
	{
		return parent::current();
	}
}

?>