<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\Phone;

/**
 * <h1>Telefones</h1>
 *
 * <p>Classe para especializar a lista no tipo de lista para aceitar apenas telefones.</p>
 *
 * @see ArrayList
 * @see Phone
 * @author Andrew
 */

class Phones extends ArrayList
{
	/**
	 * Cria uma nova lista de telefones definindo o tipo da lista como telefone.
	 */

	public function __construct()
	{
		parent::__construct(Phone::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */

	public function current():Phone
	{
		return parent::current();
	}
}

?>