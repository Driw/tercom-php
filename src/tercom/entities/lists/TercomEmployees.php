<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\TercomEmployee;

/**
 * @see ArrayList
 * @see TercomEmployee
 * @author Andrew
 */
class TercomEmployees extends ArrayList
{
	/**
	 * Cria uma nova lista de funcionários da TERCOM definindo o tipo da lista como funcionário da TERCOM.
	 */
	public function __construct()
	{
		parent::__construct(TercomEmployee::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): TercomEmployee
	{
		return parent::current();
	}
}

