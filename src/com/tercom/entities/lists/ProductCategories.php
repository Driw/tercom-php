<?php

namespace tercom\entities\lists;

use tercom\ArrayList;
use tercom\entities\ProductCategory;

/**
 * <h1>Lista de Categorias</h1>
 *
 * <p>Lista usada para armazenar objetos apenas do tipo <code>ProductCategory</code>.</p>
 *
 * @see ArrayList
 * @see ProductCategory
 * @author Andrew
 */

class ProductCategories extends ArrayList
{
	/**
	 * Cria uma nova lista de categorias.
	 */

	public function __construct()
	{
		parent::__construct(ProductCategory::class);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ArrayList::current()
	 */

	public function current():ProductCategory
	{
		return parent::current();
	}
}

