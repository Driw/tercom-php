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
	 * Procura na lista um contato de fornecedor através do seu código de identificação.
	 * @param int $provideContactID código de identificação do contato de fornecedor.
	 * @return ProviderContact|NULL aquisição do contato de fornecedor ou null caso não encontrado.
	 */

	public function getContactByID(int $provideContactID):?ProviderContact
	{
		foreach ($this as $providerContact)
			if ($providerContact->getID() == $provideContactID)
				return $providerContact;

		return null;
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