<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\Customer;

/**
 * @see Customer
 * @see ArrayList
 * @author Andrew
 */
class Customers extends ArrayList
{
	/**
	 * Cria uma nova lista de clientes definindo o tipo da lista como cliente.
	 */
	public function __construct()
	{
		parent::__construct(Customer::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): Customer
	{
		return parent::current();
	}
}

