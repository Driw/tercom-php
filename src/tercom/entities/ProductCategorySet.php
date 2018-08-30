<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use tercom\Entities\EntityParseException;

/**
 * <h1>Conjunto de Categorias para Produto</h1>
 *
 * <p>Um produto pode ter apenas uma categoria de cada tipo das categorias: família, grupo, subgrupo e setor.
 * Essa classe é criada para criar esse conjunto que irá categorizar o produto no sistema para filtros.</p>
 *
 * @see AdvancedObject
 * @see ProductFamily
 * @see ProductGroup
 * @see ProductSubGroup
 * @see ProductSector
 * @author Andrew
 */

class ProductCategorySet extends AdvancedObject
{
	/**
	 * @var ProductFamily categoria de família do produto.
	 */
	private $family;
	/**
	 * @var ProductGroup categoria de grupo do produto.
	 */
	private $group;
	/**
	 * @var ProductSubGroup categoria de subgrupo do produto.
	 */
	private $subgroup;
	/**
	 * @var ProductSector categoria de setor do produto.
	 */
	private $sector;

	/**
	 * Cria uma nova instância de um conjunto de categorias para categorização de um produto.
	 */

	public function __construct()
	{
		$this->family = new ProductFamily();
		$this->group = new ProductGroup();
		$this->subgroup = new ProductSubGroup();
		$this->sector = new ProductSector();
	}

	/**
	 * @return ProductFamily aquisição da categoria de família do produto.
	 */

	public function getFamily(): ProductFamily
	{
		return $this->family;
	}

	/**
	 * @param ProductFamily $family categoria de família do produto.
	 * @throws EntityParseException grupo definido e não pertence a família à definir.
	 */

	public function setFamily(ProductFamily $family)
	{
		if ($this->getGroup()->getID() !== 0)
			if ($this->getGroup()->getProductFamilyID() !== $family->getID())
				throw new EntityParseException('conjunto com grupo fora da família à definir');

		$this->family = $family;
	}

	/**
	 * @return ProductGroup aquisição da categoria de grupo do produto.
	 */

	public function getGroup(): ProductGroup
	{
		return $this->group;
	}

	/**
	 * @param ProductGroup $group categoria de grupo do produto.
	 * @throws EntityParseException família definida e não possui o grupo à definir
	 * ou subgrupo definido e não pertence ao grupo à definir.
	 */

	public function setGroup(ProductGroup $group)
	{
		if ($this->getFamily()->getID() !== 0)
			if ($this->getFamily()->getID() !== $group->getProductFamilyID())
				throw new EntityParseException('conunto com família que não possui o grupo à definir');

		if ($this->getSubgroup()->getID() !== 0)
			if ($this->getSubgroup()->getProductGroupID() !== $group->getID())
				throw new EntityParseException('conjunto com subgrupo fora do grupo à definir');

		$this->group = $group;
	}

	/**
	 * @return ProductSubGroup aquisição da categoria de subgrupo do produto.
	 */

	public function getSubgroup(): ProductSubGroup
	{
		return $this->subgroup;
	}

	/**
	 * @param ProductSubGroup $subgroup categoria de subgrupo do produto.
	 * @throws EntityParseException grupo definido e não possui o subgrupo à definir
	 * ou setor definido e não pertence ao subgrupo à definir.
	 */

	public function setSubgroup(ProductSubGroup $subgroup)
	{
		if ($this->getGroup() !== null)
			if ($this->getGroup()->getID() !== $subgroup->getProductGroupID())
				throw new EntityParseException('conunto com grupo que não possui o subgrupo à definir');

		if ($this->getSector()->getID() !== 0)
			if ($this->getSector()->getProductSubGroupID() !== $subgroup->getID())
				throw new EntityParseException('conjunto com setor fora do subgrupo à definir');

		$this->subgroup = $subgroup;
	}

	/**
	 * @return ProductSector aquisição da categoria de setor do produto.
	 */

	public function getSector(): ProductSector
	{
		return $this->sector;
	}

	/**
	 * @param ProductSector $sector categoria de setor do produto.
	 * @throws EntityParseException setor definido e não pertence ao subgrupo à definir.
	 */

	public function setSector($sector)
	{
		if ($this->getSubgroup()->getID() !== 0)
			if ($this->getSubgroup()->getID() !== $sector->getProductSubGroupID())
				throw new EntityParseException('conunto com subgrupo que não possui o setor à definir');

		$this->sector = $sector;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */

	public function getAttributeTypes(): array
	{
		return [
			'family' => ProductFamily::class,
			'group' => ProductGroup::class,
			'subgroup' => ProductSubGroup::class,
			'sector' => ProductSector::class,
		];
	}
}

