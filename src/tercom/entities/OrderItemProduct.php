<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 * Item de Produto de Pedido
 *
 * Um pedido possui dois tipos de itens, esta classe representa um dos tipos de itens que é o item de produto.
 * Cada item de produto é responsável por detalhar as preferências e um dos produtos do pedido para cotação.
 * Os detalhes incluem preferência de fornecedor, fabricante e se deseja o melhor preço (mais barato).
 *
 * @see AdvancedObject
 * @see Product
 * @see Provider
 * @see Manufacturer
 *
 * @author Andrew
 */
class OrderItemProduct extends AdvancedObject
{
	/**
	 * @var int limite de caracteres para observações do item.
	 */
	public const MAX_OBSERVATIONS_LEN = 128;

	/**
	 * @var int código de identificação único do item de produto de pedido.
	 */
	private $id;
	/**
	 * @var Product produto do qual deve ser cotado no pedido.
	 */
	private $product;
	/**
	 * @var Provider preferência de fornecedor.
	 */
	private $provider;
	/**
	 * @var Manufacturer preferência de fabricante.
	 */
	private $manufacturer;
	/**
	 * @var bool melhor preço ordena do mais barato para o mais caro.
	 */
	private $betterPrice;
	/**
	 * @var string observações adicionais referente ao item.
	 */
	private $observations;

	/**
	 * Cria uma nova instância de um item de produto de pedido.
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->betterPrice = false;
	}

	/**
	 * @return int aquisição do código de identificação único do item de produto de pedido.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do item de produto de pedido.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return Product aquisição do produto do qual deve ser cotado no pedido.
	 */
	public function getProduct(): Product
	{
		return $this->product === null ? ($this->product = new Product()) : $this->product;
	}

	/**
	 * @return int aquisição do código de identificação do produto do qual deve ser cotado no pedido.
	 */
	public function getProductId(): int
	{
		return $this->product === null ? 0 : $this->product->getId();
	}

	/**
	 * @param Product $product produto do qual deve ser cotado no pedido.
	 */
	public function setProduct(Product $product): void
	{
		$this->product = $product;
	}

	/**
	 * @return Provider aquisição da preferência de fornecedor.
	 */
	public function getProvider(): Provider
	{
		return $this->provider === null ? ($this->provider = new Provider()) : $this->provider;
	}

	/**
	 * @return int aquisição do código de identificação da preferência de fornecedor.
	 */
	public function getProviderId(): int
	{
		return $this->provider === null ? 0 : $this->provider->getId();
	}

	/**
	 * @param NULL|Provider $provider preferência de fornecedor.
	 */
	public function setProvider(?Provider $provider): void
	{
		$this->provider = $provider;
	}

	/**
	 * @return Manufacturer aquisição da preferência de fabricante.
	 */
	public function getManufacturer(): Manufacturer
	{
		return $this->manufacturer === null ? ($this->manufacturer = new Manufacturer()) : $this->manufacturer;
	}

	/**
	 * @return int aquisição do código de identificação da preferência de fabricante.
	 */
	public function getManufacturerId(): int
	{
		return $this->manufacturer === null ? 0 : $this->manufacturer->getId();
	}

	/**
	 * @param NULL|Manufacturer $manufacturer preferência de fabricante.
	 */
	public function setManufacturer(?Manufacturer $manufacturer): void
	{
		$this->manufacturer = $manufacturer;
	}

	/**
	 * @return bool aquisição do melhor preço ordena do mais barato para o mais caro.
	 */
	public function isBetterPrice(): bool
	{
		return $this->betterPrice;
	}

	/**
	 * @param bool $betterPrice melhor preço ordena do mais barato para o mais caro.
	 */
	public function setBetterPrice(bool $betterPrice): void
	{
		$this->betterPrice = $betterPrice;
	}

	/**
	 * @return string|NULL aquisição das observações adicionais referente ao item.
	 */
	public function getObservations(): ?string
	{
		return $this->observations;
	}

	/**
	 * @param string|NULL $observations observações adicionais referente ao item.
	 */
	public function setObservations(?string $observations): void
	{
		if ($observations !== null && !StringUtil::hasMaxLength($observations, self::MAX_OBSERVATIONS_LEN))
			throw EntityParseException::new('observações deve possuir até %d caracteres', self::MAX_OBSERVATIONS_LEN);

		$this->observations = $observations;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */
	public function getAttributeTypes()
	{
		return [
			'product' => Product::class,
			'provider' => Provider::class,
			'manufacturer' => Manufacturer::class,
			'betterPrice' => ObjectUtil::TYPE_BOOLEAN,
			'observation' => ObjectUtil::TYPE_STRING,
		];
	}
}

