<?php

namespace tercom\entities;

use dProject\Primitive\ObjectUtil;
use tercom\entities\lists\ProductValues;
use dProject\Primitive\AdvancedObject;
use dProject\Primitive\StringUtil;
use tercom\Entities\EntityParseException;

/**
 * @see AdvancedObject
 * @see ProductUnit
 * @author Andrew
 */

class Product extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres permitido no nome.
	 */
	public const MIN_NAME_LEN = 2;
	/**
	 * @var int quantidade máxima de caracteres permitido no nome.
	 */
	public const MAX_NAME_LEN = 48;
	/**
	 * @var int quantidade mínima de caracteres permitido da descrição.
	 */
	public const MIN_DESCRIPTION_LEN = 8;
	/**
	 * @var int quantidade máxima de caracteres permitido da descrição.
	 */
	public const MAX_DESCRIPTION_LEN = 128;
	/**
	 * @var int quantidade máxima de caracteres permitido na utilidade.
	 */
	public const MAX_UTILITY_LEN = 128;

	/**
	 * @var int código de identificação único.
	 */
	private $id;
	/**
	 * @var ProductUnit unidade de produto padrão.
	 */
	private $unit;
	/**
	 * @var string nome do produto.
	 */
	private $name;
	/**
	 * @var string descrição sobre o produto.
	 */
	private $description;
	/**
	 * @var string descrição de como utilizar o produto.
	 */
	private $utility;
	/**
	 * @var bool produto inativo.
	 */
	private $inactive;
	/**
	 * @var ProductCategorySet conjunto de categorias para filtro.
	 */
	private $category;
	/**
	 * @var ProductValues lista de valores do produto.
	 */
	private $values;

	/**
	 * Cria uma nova instância de um produto.
	 */

	public function __construct()
	{
		$this->id = 0;
		$this->name = '';
		$this->description = '';
		$this->utility = '';
		$this->inactive = false;
		$this->category = new ProductCategorySet();
		$this->values = new ProductValues();
		$this->unit = new ProductUnit();
	}

	/**
	 * @return int aquisição do código de identificação.
	 */

	public function getID(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação.
	 */

	public function setID(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return ProductUnit aquisição da unidade de produto padrão.
	 */

	public function getUnit(): ProductUnit
	{
		return $this->unit;
	}

	/**
	 * @param ProductUnit $unit unidade de produto padrão.
	 */

	public function setUnit(ProductUnit $unit)
	{
		$this->unit = $unit;
	}

	/**
	 * @return string aquisição do nome do produto.
	 */

	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome do produto.
	 */

	public function setName(string $name)
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres (nome: $name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return string aquisição da descrição sobre o produto.
	 */

	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description descrição sobre o produto
	 */

	public function setDescription(string $description)
	{
		if (!StringUtil::hasBetweenLength($description, self::MIN_DESCRIPTION_LEN, self::MAX_DESCRIPTION_LEN))
			throw EntityParseException::new("descrição deve possuir de %d a %d caracteres (descrição: $description)", self::MIN_DESCRIPTION_LEN, self::MAX_DESCRIPTION_LEN);

		$this->description = $description;
	}


	/**
	 * @return string aquisição da descrição de como utilizar o produto.
	 */

	public function getUtility(): string
	{
		return $this->utility;
	}

	/**
	 * @param string $utility descrição de como utilizar o produto.
	 */

	public function setUtility(string $utility)
	{
		if (!StringUtil::hasMaxLength($utility, self::MAX_UTILITY_LEN))
			throw EntityParseException::new("utilidade deve possuir até %d caracteres (utilidade: $utility)", self::MAX_UTILITY_LEN);

		$this->utility = $utility;
	}

	/**
	 * @return boolean produto está inativo.
	 */

	public function isInactive(): bool
	{
		return $this->inactive;
	}

	/**
	 * @param boolean $inactive inativar produto.
	 */

	public function setInactive(bool $inactive)
	{
		$this->inactive = $inactive;
	}

	/**
	 * @return ProductCategorySet aquisição do conjunto de categorias para filtro.
	 */

	public function getCategory(): ProductCategorySet
	{
		return $this->category;
	}

	/**
	 * @param ProductCategorySet $category conjunto de categorias para filtro.
	 */

	public function setCategory(ProductCategorySet $category)
	{
		$this->category = $category;
	}

	/**
	 * @return ProductValues aquisição da lista de valores do produto.
	 */

	public function getValues(): ProductValues
	{
		return $this->values;
	}

	/**
	 * @param ProductValues $values lista de valores do produto.
	 */

	public function setValues(ProductValues $values)
	{
		$this->values = $values;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */

	public function getAttributeTypes(): array
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'unit' => ProductUnit::class,
			'name' => ObjectUtil::TYPE_STRING,
			'description' => ObjectUtil::TYPE_STRING,
			'utility' => ObjectUtil::TYPE_STRING,
			'inactive' => ObjectUtil::TYPE_BOOLEAN,
			'category' => ProductCategorySet::class,
			'values' => ProductValues::class,
		];
	}
}

