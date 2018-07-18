<?php

namespace tercom\entities;

use Iterator;
use tercom\ArrayList;

/**
 * <h1>Contatos do Fornecedor</h1>
 *
 * <p>A lista de contatos do fornecedor permite gerenciar quais os contatos diponíveis para cada fornecedor.
 * Através da lista de contatos um fornecedor pode possuir diversos números de telefone e denreços de e-mail.</p>
 *
 * @see ArrayList
 * @author Andrew
 */

class ProviderContacts extends ArrayList
{
	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::add()
	 */

	public function add(object $element)
	{
		if ($element instanceof ProviderContact)
			parent::add($element);
	}

	/**
	 * {@inheritDoc}
	 * @see Iterator::current()
	 */

	public function current():ProviderContact
	{
		return parent::current();
	}
}

?>