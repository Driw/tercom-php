<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;
use dProject\Primitive\FloatUtil;

/***
 * @see AdvancedObject
 * @author Andrew
 */
class ServicePrice extends AdvancedObject
{
	/**
	 * @var int
	 */
	public const MIN_NAME_LEN = 2;
	/**
	 * @var int
	 */
	public const MAX_NAME_LEN = 48;
	/**
	 * @var int
	 */
	public const MAX_ADDITIONAL_DESCRIPTION = 256;
	/**
	 * @var float
	 */
	public const MIN_PRICE = 0.0;

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var Service
	 */
	private $service;
	/**
	 * @var Provider
	 */
	private $provider;
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $additionalDescription;
	/**
	 * @var float
	 */
	private $price;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->service = new Service();
		$this->provider = new Provider();
		$this->name = '';
		$this->additionalDescription = '';
		$this->price = 0.0;
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
	 * @return ServicePrice
	 */
	public function setId(int $id): ServicePrice
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return Service
	 */
	public function getService(): Service
	{
		return $this->service;
	}

	/**
	 * @param Service $service
	 * @return ServicePrice
	 */
	public function setService(Service $service): ServicePrice
	{
		$this->service = $service;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getIdService(): int
	{
		return $this->service->getId();
	}

	/**
	 * @return Provider
	 */
	public function getProvider(): Provider
	{
		return $this->provider;
	}

	/**
	 * @param Provider $provider
	 * @return ServicePrice
	 */
	public function setProvider(Provider $provider): ServicePrice
	{
		$this->provider = $provider;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getIdProvider(): int
	{
		return $this->provider->getID();
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return ServicePrice
	 */
	public function setName(string $name): ServicePrice
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres (nome: $name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAdditionalDescription(): string
	{
		return $this->additionalDescription;
	}

	/**
	 * @param string $additionalDescription
	 * @return ServicePrice
	 */
	public function setAdditionalDescription(?string $additionalDescription): ServicePrice
	{
		if ($additionalDescription !== null && !StringUtil::hasMaxLength($additionalDescription, self::MAX_ADDITIONAL_DESCRIPTION))
			throw EntityParseException::new("descrição adicional deve possuir até %d caracteres", self::MAX_ADDITIONAL_DESCRIPTION);

		$this->additionalDescription = $additionalDescription;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getPrice(): float
	{
		return $this->price;
	}

	/**
	 * @param float $price
	 * @return ServicePrice
	 */
	public function setPrice(float $price): ServicePrice
	{
		if (!FloatUtil::inMin($price, self::MIN_PRICE))
			throw EntityParseException::new('preço do serviço deve ser maior ou igual a %.2f', self::MIN_PRICE);

		$this->price = $price;
		return $this;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */
	public function getAttributeTypes()
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'service' => Service::class,
			'provider' => Provider::class,
			'name' => ObjectUtil::TYPE_STRING,
			'additionalDescription' => ObjectUtil::TYPE_STRING,
			'price' => ObjectUtil::TYPE_INTEGER,
		];
	}
}

