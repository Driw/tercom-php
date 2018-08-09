<?php

namespace tercom\entities;

use tercom\entities\lists\ProductCategories;

/**
 * <h1>Categoria de Produto - Subgrupo</h1>
 *
 * <p>A categoria de subgrupo do produto agrupa todos as categorias de setor de um produto.</p>
 *
 * @see ProductCategory
 * @author Andrew
 */

class ProductSubGroup extends ProductCategory
{
	/**
	 * @var ProductGroup categoria de subgrupo do setor.
	 */
	private $idProductGroup;
	/**
	 * @var ProductCategories lista de setores do subgrupo.
	 */
	private $productSectores;

	/**
	 * Cria um nova categoria de produto do tipo subgrupo.
	 */

	public function __construct($type = null)
	{
		parent::__construct(self::CATEGORY_SUBGROUP);

		$this->idProductGroup = 0;
		$this->productSectores = new ProductCategories();
	}

	/**
	 * @return ProductGroup aquisição do código de identificação do grupo desse subgrupo.
	 */

	public function getProductGroupID(): int
	{
		return $this->idProductGroup;
	}

	/**
	 * @param ProductGroup $idProductGroup código de identificação do grupo desse subgrupo.
	 */

	public function setProductGroupID(int $idProductGroup)
	{
		$this->idProductGroup = $idProductGroup;
	}

	/**
	 * @return ProductCategories aquisição da lista de setores do subgrupo.
	 */

	public function getProductSectores(): ProductCategories
	{
		return $this->productSectores;
	}

	/**
	 * @param ProductCategories $productSubGroups lista de setores do subgrupo.
	 */

	public function setProductSectores(ProductCategories $productSectores)
	{
		$this->productSectores = $productSectores;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\entities\ProductCategory::getAttributeTypes()
	 */

	public function getAttributeTypes(): array
	{
		return array_merge(parent::getAttributeTypes(), [
			'idProductGroup' => ProductGroup::class,
		]);
	}
}
