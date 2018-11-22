<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;

/**
 * <h1>Conjunto de Categorias para Produto</h1>
 *
 * <p>Um produto pode ter apenas uma categoria de cada tipo das categorias: família, grupo, subgrupo e setor.
 * Essa classe é criada para criar esse conjunto que irá categorizar o produto no sistema para filtros.</p>
 *
 * @see AdvancedObject
 * @see ProductCategory
 * @see ProductCategory
 * @see ProductCategory
 * @see ProductCategory
 * @author Andrew
 */

class ProductCategorySet extends AdvancedObject
{
	/**
	 * @var ProductCategory categoria de família do produto.
	 */
	private $family;
	/**
	 * @var ProductCategory categoria de grupo do produto.
	 */
	private $group;
	/**
	 * @var ProductCategory categoria de subgrupo do produto.
	 */
	private $subgroup;
	/**
	 * @var ProductCategory categoria de setor do produto.
	 */
	private $sector;

	/**
	 * Cria uma nova instância de um conjunto de categorias para categorização de um produto.
	 */

	public function __construct()
	{
		$this->family = new ProductCategory();
		$this->group = new ProductCategory();
		$this->subgroup = new ProductCategory();
		$this->sector = new ProductCategory();
	}

	/**
	 * @return ProductCategory aquisição da categoria de família do produto.
	 */

	public function getFamily(): ProductCategory
	{
		return $this->family;
	}

	/**
	 * @param ProductCategory $family categoria de família do produto.
	 * @throws EntityParseException grupo definido e não pertence a família à definir.
	 */

	public function setFamily(ProductCategory $family)
	{
		if ($this->getGroup()->getID() !== 0)
			if ($this->getGroup()->getProductCategoryID() !== $family->getID())
				throw new EntityParseException('conjunto com grupo fora da família à definir');

		$this->family = $family;
	}

	/**
	 * @return ProductCategory aquisição da categoria de grupo do produto.
	 */

	public function getGroup(): ProductCategory
	{
		return $this->group;
	}

	/**
	 * @param ProductCategory $group categoria de grupo do produto.
	 * @throws EntityParseException família definida e não possui o grupo à definir
	 * ou subgrupo definido e não pertence ao grupo à definir.
	 */

	public function setGroup(ProductCategory $group)
	{
		if ($this->getFamily()->getID() !== 0)
			if ($this->getFamily()->getID() !== $group->getProductCategoryID())
				throw new EntityParseException('conjunto com família que não possui o grupo à definir');

		if ($this->getSubgroup()->getID() !== 0)
			if ($this->getSubgroup()->getProductCategoryID() !== $group->getID())
				throw new EntityParseException('conjunto com subgrupo fora do grupo à definir');

		$this->group = $group;
	}

	/**
	 * @return ProductCategory aquisição da categoria de subgrupo do produto.
	 */

	public function getSubgroup(): ProductCategory
	{
		return $this->subgroup;
	}

	/**
	 * @param ProductCategory $subgroup categoria de subgrupo do produto.
	 * @throws EntityParseException grupo definido e não possui o subgrupo à definir
	 * ou setor definido e não pertence ao subgrupo à definir.
	 */

	public function setSubgroup(ProductCategory $subgroup)
	{
		if ($this->getGroup() !== null)
			if ($this->getGroup()->getID() !== $subgroup->getProductCategoryID())
				throw new EntityParseException('conjunto com grupo que não possui o subgrupo à definir');

		if ($this->getSector()->getID() !== 0)
			if ($this->getSector()->getProductCategoryID() !== $subgroup->getID())
				throw new EntityParseException('conjunto com setor fora do subgrupo à definir');

		$this->subgroup = $subgroup;
	}

	/**
	 * @return ProductCategory aquisição da categoria de setor do produto.
	 */

	public function getSector(): ProductCategory
	{
		return $this->sector;
	}

	/**
	 * @param ProductCategory $sector categoria de setor do produto.
	 * @throws EntityParseException setor definido e não pertence ao subgrupo à definir.
	 */

	public function setSector($sector)
	{
		if ($this->getSubgroup()->getID() !== 0)
			if ($this->getSubgroup()->getID() !== $sector->getProductCategoryID())
				throw new EntityParseException('conjunto com subgrupo que não possui o setor à definir');

		$this->sector = $sector;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */

	public function getAttributeTypes(): array
	{
		return [
			'family' => ProductCategory::class,
			'group' => ProductCategory::class,
			'subgroup' => ProductCategory::class,
			'sector' => ProductCategory::class,
		];
	}
}

