<?php

namespace tercom\entities;

/**
 * <h1>Categoria de Produto - Setor</h1>
 *
 * <p>A categoria de setor do produto é a maior especificação possível de um produto.</p>
 *
 * @see ProductCategory
 * @author Andrew
 */

class ProductSector extends ProductCategory
{
	/**
	 * @var int código de identificação do subgrupo do setor.
	 */
	private $idProductSubGroup;

	/**
	 * Cria um nova categoria de produto do tipo setor.
	 */

	public function __construct($type = null)
	{
		parent::__construct(self::CATEGORY_SECTOR);

		$this->idProductSubGroup = 0;
	}

	/**
	 * @return ProductSubGroup aquisição do código de identificação do subgrupo do setor.
	 */

	public function getProductSubGroupID(): int
	{
		return $this->idProductSubGroup;
	}

	/**
	 * @param ProductSubGroup $idProductSubGroup código de identificação do subgrupo do setor.
	 */

	public function setProductSubGroupID(int $idProductSubGroup)
	{
		$this->idProductSubGroup = $idProductSubGroup;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\entities\ProductCategory::getAttributeTypes()
	 */

	public function getAttributeTypes(): array
	{
		return array_merge(parent::getAttributeTypes(), [
			'idProductSubGroup' => ProductSubGroup::class,
		]);
	}
}
