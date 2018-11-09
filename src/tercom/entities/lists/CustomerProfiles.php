<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\CustomerProfile;

/**
 * @see CustomerProfile
 * @see ArrayList
 * @author Andrew
 */
class CustomerProfiles extends ArrayList
{
	/**
	 * Cria uma nova lista de perfis do cliente definindo o tipo da lista como perfil do cliente.
	 */
	public function __construct()
	{
		parent::__construct(CustomerProfile::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): CustomerProfile
	{
		return parent::current();
	}
}

