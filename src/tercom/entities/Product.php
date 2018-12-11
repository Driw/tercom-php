<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;
use tercom\entities\lists\ProductPrices;

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
	public const MAX_DESCRIPTION_LEN = TINYTEXT_LEN;
	/**
	 * @var int quantidade máxima de caracteres permitido na utilidade.
	 */
	public const MAX_UTILITY_LEN = TINYTEXT_LEN;

	/**
	 * @var int código de identificação único.
	 */
	private $id;
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
	 * @var ProductUnit unidade de produto padrão.
	 */
	private $productUnit;
	/**
	 * @var ProductCategory categoria do produto.
	 */
	private $productCategory;
	/**
	 * @var ProductPrices lista de preços do produto.
	 */
	private $productPrices;

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
	}

	/**
	 * @return int aquisição do código de identificação.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
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
	public function setName(string $name): void
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
	public function setDescription(string $description): void
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
	public function setUtility(string $utility): void
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
	public function setInactive(bool $inactive): void
	{
		$this->inactive = $inactive;
	}

	/**
	 * @return ProductUnit aquisição da unidade de produto padrão.
	 */
	public function getProductUnit(): ProductUnit
	{
		return $this->productUnit === null ? ($this->productUnit = new ProductUnit()) : $this->productUnit;
	}

	/**
	 * @return int aquisição do código de identificação da unidade do produto.
	 */
	public function getProductUnitId(): int
	{
		return $this->productUnit === null ? 0 : $this->productUnit->getId();
	}

	/**
	 * @param ProductUnit $unit unidade de produto padrão.
	 */
	public function setProductUnit(ProductUnit $productUnit): void
	{
		$this->productUnit = $productUnit;
	}

	/**
	 * @return ProductCategory aquisição do conjunto de categorias para filtro.
	 */
	public function getProductCategory(): ProductCategory
	{
		return $this->productCategory === null ? ($this->productCategory = new ProductCategory()) : $this->productCategory;
	}

	/**
	 * @return int aquisição do código de identificação da categoria do produto.
	 */
	public function getProductCategoryId(): int
	{
		return $this->productCategory === null ? 0 : $this->productCategory->getId();
	}

	/**
	 * @param ProductCategory $productCategory conjunto de categorias para filtro.
	 */
	public function setProductCategory(ProductCategory $productCategory): void
	{
		$this->productCategory = $productCategory;
	}

	/**
	 * @return ProductPrices aquisição da lista de preços do produto.
	 */
	public function getProductPrices(): ProductPrices
	{
		return $this->productPrices === null ? ($this->productPrices = new ProductPrices()) : $this->productPrices;
	}

	/**
	 * @param ProductPrices $prices lista de preços do produto.
	 */
	public function setProductPrices(ProductPrices $prices): void
	{
		$this->productPrices = $prices;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */
	public function getAttributeTypes(): array
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'name' => ObjectUtil::TYPE_STRING,
			'description' => ObjectUtil::TYPE_STRING,
			'utility' => ObjectUtil::TYPE_STRING,
			'inactive' => ObjectUtil::TYPE_BOOLEAN,
			'productUnit' => ProductUnit::class,
			'productCategory' => ProductCategory::class,
			'productPrices' => ProductPrices::class,
		];
	}
}

