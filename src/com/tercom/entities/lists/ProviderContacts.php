<?php

namespace tercom\entities\lists;

use Iterator;
use tercom\ArrayList;
use tercom\entities\ProviderContact;

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
	public function __construct()
	{
		parent::__construct(ProviderContact::class);
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