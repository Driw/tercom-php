<?php

namespace tercom\bootstrap\navbar;

use dProject\Primitive\AdvancedObject;

class Navbar extends AdvancedObject
{
	/**
	 * 
	 * @var NavbarBrand
	 */
	private $navbarBrand;
	/**
	 * 
	 * @var NavbarItems
	 */
	private $navbarItems;

	/**
	 * Cria uma nova barra de navegação, inicializa a lista de itens e brand.
	 * @param string $name nome que será dado à barra de navegação.
	 */

	public function __construct($name)
	{
		$this->navbarBrand = new NavbarBrand($name);
		$this->navbarItems = new NavbarItems($name);
	}

	/**
	 * @return NavbarBrand aquisição do brand da barra de navegação.
	 */

	public function getNavbarBrand()
	{
		return $this->navbarBrand;
	}

	/**
	 * @return NavbarItems aquisição da lista de itens da barra de navegação.
	 */

	public function getNavbarItems()
	{
		return $this->navbarItems;
	}
}

?>