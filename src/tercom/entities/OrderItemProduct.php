<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 *
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
	 * @var int
	 */
	public const MAX_OBSERVATIONS_LEN = 128;

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var Product
	 */
	private $product;
	/**
	 * @var Provider
	 */
	private $provider;
	/**
	 * @var Manufacturer
	 */
	private $manufacturer;
	/**
	 * @var bool
	 */
	private $betterPrice;
	/**
	 * @var string
	 */
	private $observations;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->betterPrice = false;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return Product
	 */
	public function getProduct(): Product
	{
		return $this->product === null ? ($this->product = new Product()) : $this->product;
	}

	/**
	 * @return int
	 */
	public function getProductId(): int
	{
		return $this->product === null ? 0 : $this->product->getId();
	}

	/**
	 * @param Product $product
	 * @return OrderItemProduct
	 */
	public function setProduct(Product $product): OrderItemProduct
	{
		$this->product = $product;
		return $this;
	}

	/**
	 * @return Provider
	 */
	public function getProvider(): Provider
	{
		return $this->provider === null ? ($this->provider = new Provider()) : $this->provider;
	}

	/**
	 * @param Provider $provider
	 * @return OrderItemProduct
	 */
	public function setProvider(Provider $provider): OrderItemProduct
	{
		$this->provider = $provider;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getProviderId(): int
	{
		return $this->provider === null ? 0 : $this->provider->getId();
	}

	/**
	 * @return Manufacturer
	 */
	public function getManufacturer(): Manufacturer
	{
		return $this->manufacturer === null ? ($this->manufacturer = new Manufacturer()) : $this->manufacturer;
	}

	/**
	 * @return int
	 */
	public function getManufacturerId(): int
	{
		return $this->manufacturer === null ? 0 : $this->manufacturer->getId();
	}

	/**
	 * @param Manufacturer $manufacturer
	 * @return OrderItemProduct
	 */
	public function setManufacturer(Manufacturer $manufacturer): OrderItemProduct
	{
		$this->manufacturer = $manufacturer;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isBetterPrice(): bool
	{
		return $this->betterPrice;
	}

	/**
	 * @param bool $betterPrice
	 * @return OrderItemProduct
	 */
	public function setBetterPrice(bool $betterPrice): OrderItemProduct
	{
		$this->betterPrice = $betterPrice;
		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getObservations(): ?string
	{
		return $this->observations;
	}

	/**
	 * @param string|NULL $observations
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

