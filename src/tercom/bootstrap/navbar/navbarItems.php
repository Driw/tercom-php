<?php

namespace tercom\bootstrap\navbar;

class NavbarItems extends NavbarItem
{
	/**
	 * Vetor com os itens da barra de navegação.
	 * @var NavbarItem[]
	 */
	private $navbarItems;

	/**
	 * Cria uma nova lista de itens para a barra de navegação.
	 * @param string $name nome de exibição do item na barra de navegação.
	 */

	public function __construct($name)
	{
		parent::__construct($name);

		$this->navbarItems = array();
	}

	/**
	 * Adiciona um nvo item a lista de itens da barra de navegação.
	 * Caso o item já esteja adicionado não irá adicioná-lo novamente.
	 * @param NavbarItem $navbarItem item da barra de navegação à adicionar.
	 */

	public function add(NavbarItem $navbarItem)
	{
		if (!in_array($navbarItem, $this->navbarItems))
			array_push($this->navbarItems, $navbarItem);
	}

	/**
	 * @return NavbarItem[] aquisição dos itens da barra de navegação.
	 */

	public function getItems()
	{
		return $this->navbarItems;
	}
}

?>