<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\CustomerEmployee;

/**
 * @see CustomerEmployee
 * @see ArrayList
 * @author Andrew
 */
class CustomerEmployees extends ArrayList
{
	/**
	 * Cria uma nova lista de funcionários do cliente definindo o tipo da lista como funcionário de cliente.
	 */
	public function __construct()
	{
		parent::__construct(CustomerEmployee::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): CustomerEmployee
	{
		return parent::current();
	}
}

