<?php

namespace tercom\entities;

use tercom\entities\lists\ProductCategories;

/**
 * <h1>Categoria de Produto - Família</h1>
 *
 * <p>A categoria de família do produto agrupa todos as categorias de grupo de um produto.</p>
 *
 * @see ProductCategory
 * @author Andrew
 */

class ProductFamily extends ProductCategory
{
	/**
	 * @var ProductCategories lista de grupos da família.
	 */
	private $productGroups;

	/**
	 * Cria um nova categoria de produto do tipo família.
	 */

	public function __construct($type = null)
	{
		parent::__construct(self::CATEGORY_FAMILY);

		$this->productGroups = new ProductCategories();
	}

	/**
	 * @return ProductCategories aquisição da lista de grupos da família.
	 */

	public function getProductGroups(): ProductCategories
	{
		return $this->productGroups;
	}

	/**
	 * @param ProductCategories $productGroups lista de grupos da família.
	 */

	public function setProductGroups(ProductCategories $productGroups)
	{
		$this->productGroups = $productGroups;
	}
}
