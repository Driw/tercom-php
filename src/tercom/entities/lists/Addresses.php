<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\Address;

/**
 * @see Address
 * @see ArrayList
 * @author Andrew
 */
class Addresses extends ArrayList
{
	/**
	 * Cria uma nova lista de endereços definindo o tipo da lista como endereço.
	 */
	public function __construct()
	{
		parent::__construct(Address::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */
	public function current(): Address
	{
		return parent::current();
	}

	/**
	 *
	 * @param Address $address
	 * @return bool
	 */
	public function replace(Address $address): bool
	{
		for ($i = 0; $i < $this->size(); $i++)
			if (($element = $this->elements[$i]) !== null && $element instanceof Address)
				if ($element->getId() === $address->getId())
				{
					$this->elements[$i] = $address;
					return true;
				}

		$this->add($address);
		return false;
	}
}

