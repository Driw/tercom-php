<?php

namespace tercom\entities\lists;

use tercom\entities\Permission;
use tercom\ArrayListEntity;

/**
 *
 * @see ArrayList
 * @see Permission
 * @author Andrew
 */

class Permissions extends ArrayListEntity
{
	/**
	 * Cria uma nova lista de permissões definindo o tipo da lista como permissão.
	 */
	public function __construct()
	{
		parent::__construct(Permission::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): Permission
	{
		return parent::current();
	}
}

