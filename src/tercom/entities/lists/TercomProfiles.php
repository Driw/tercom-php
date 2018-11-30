<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\TercomProfile;

/**
 * @see ArrayList
 * @see TercomProfile
 * @author Andrew
 */
class TercomProfiles extends ArrayList
{
	/**
	 * Cria uma nova lista de perfil da TERCOM definindo o tipo da lista como perfil da TERCOM.
	 */
	public function __construct()
	{
		parent::__construct(TercomProfile::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): TercomProfile
	{
		return parent::current();
	}
}

