<?php

namespace tercom\bootstrap\navbar;

class NavbarBrand extends NavbarItem
{
	/**
	 * Nome de exibição do item na barra de navegação.
	 * @var string
	 */
	private $text;
	/**
	 * Endereço de link ao clicar sobre o item.
	 * @var string
	 */
	private $link;
	/**
	 * 
	 * @var string
	 */
	private $image;

	/**
	 * @param string $name nome de exibição do item na barra de navegação.
	 * @param string $link endereço de link ao clicar sobre o item.
	 */

	public function __construct($name, $link = '#')
	{
		$this->link = strval($link);
		$this->text = strval($name);
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
		return $this->text;
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
		$this->text = $name;
	}
}

?>