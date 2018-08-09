<?php

namespace tercom\bootstrap\navbar;

use dProject\Primitive\AdvancedObject;

class NavbarItem extends AdvancedObject
{
	/**
	 * Nome de exibição do item na barra de navegação.
	 * @var string
	 */
	private $name;
	/**
	 * Endereço de link ao clicar sobre o item.
	 * @var string
	 */
	private $link;

	/**
	 * @param string $name nome de exibição do item na barra de navegação.
	 * @param string $link endereço de link ao clicar sobre o item.
	 */

	public function __construct($name, $link = '#')
	{
		$this->link = strval($link);
		$this->name = strval($name);
	}

	/**
	 * @return string aquisição do nome de exibição do item na barra de navegação.
	 */

	public function getLink()
	{
		return $this->link;
	}

	/**
	 * @return string nome de exibição do item na barra de navegação.
	 */

	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $link endereço de link ao clicar sobre o item.
	 */

	public function setLink($link)
	{
		$this->link = $link;
	}

	/**
	 * @param string $name endereço de link ao clicar sobre o item.
	 */

	public function setName($name)
	{
		$this->name = $name;
	}
}

?>