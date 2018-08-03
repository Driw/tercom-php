<?php

namespace tercom\entities;

use tercom\entities\lists\ProductCategories;

/**
 * <h1>Categoria de Produto - Grupo</h1>
 *
 * <p>A categoria de grupo do produto agrupa todos as categorias de subgrupo de um produto.</p>
 *
 * @see ProductCategory
 * @author Andrew
 */

class ProductGroup extends ProductCategory
{
	/**
	 * @var int código de identificação da família do grupo.
	 */
	private $idProductFamily;
	/**
	 * @var ProductCategories lista de subgrupos do grupo.
	 */
	private $productSubGroups;

	/**
	 * Cria um nova categoria de produto do tipo grupo.
	 */

	public function __construct($type = null)
	{
		parent::__construct(self::CATEGORY_GROUP);

		$this->idProductFamily = 0;
		$this->productSubGroups = new ProductCategories();
	}

	/**
	 * @return ProductFamily aquisição do código de identificação da família do grupo.
	 */

	public function getProductFamilyID(): int
	{
		return $this->idProductFamily;
	}

	/**
	 * @param ProductFamily $productFamily código de identificação da família do grupo.
	 */

	public function setProductFamilyID(int $idProductFamily)
	{
		$this->idProductFamily = $idProductFamily;
	}

	/**
	 * @return ProductCategories aquisição da lista de subgrupos do grupo.
	 */

	public function getProductSubGroups(): ProductCategories
	{
		return $this->productSubGroups;
	}

	/**
	 * @param ProductCategories $productSubGroups lista de subgrupos do grupo.
	 */

	public function setProductSubGroups(ProductCategories $productSubGroups)
	{
		$this->productSubGroups = $productSubGroups;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\entities\ProductCategory::getAttributeTypes()
	 */

	public function getAttributeTypes(): array
	{
		return array_merge(parent::getAttributeTypes(), [
			'productFamily' => ProductFamily::class,
		]);
	}
}
