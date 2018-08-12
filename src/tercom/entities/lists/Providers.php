<?php

namespace tercom\entities\lists;

use Iterator;
use tercom\ArrayList;
use tercom\entities\Provider;

/**
 * <h1>Fornecedores</h1>
 *
 * <p>A lista de fornecedores é utilizada durante buscas/consultas que podem conter diversos fornecedores.</p>
 *
 * @see ArrayList
 * @author Andrew
 */

class Providers extends ArrayList
{
	/**
	 *
	 */

	public function __construct()
	{
		parent::__construct(Provider::class);
	}

	/**
	 * {@inheritDoc}
	 * @see Iterator::current()
	 */

	public function current(): Provider
	{
		return parent::current();
	}
}

?>