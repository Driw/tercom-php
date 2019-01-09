<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 *
 *
 * @see AdvancedObject
 * @see Service
 * @see Provider
 *
 * @author Andrew
 */
class OrderItemService extends AdvancedObject
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
	 * @var Service
	 */
	private $service;
	/**
	 * @var Provider
	 */
	private $provider;
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
	 * @return Service
	 */
	public function getService(): Service
	{
		return $this->service === null ? ($this->service = new Service()) : $this->service;
	}

	/**
	 * @return int
	 */
	public function getServiceId(): int
	{
		return $this->service === null ? 0 : $this->service->getId();
	}

	/**
	 * @param Service $Service
	 * @return OrderItemService
	 */
	public function setService(Service $Service): OrderItemService
	{
		$this->service = $Service;
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
	 * @return int
	 */
	public function getProviderId(): int
	{
		return $this->provider === null ? 0 : $this->provider->getId();
	}

	/**
	 * @param Provider $provider
	 * @return OrderItemService
	 */
	public function setProvider(Provider $provider): OrderItemService
	{
		$this->provider = $provider;
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
	 * @return OrderItemService
	 */
	public function setBetterPrice(bool $betterPrice): OrderItemService
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
			'service' => Service::class,
			'provider' => Provider::class,
			'betterPrice' => ObjectUtil::TYPE_BOOLEAN,
			'observations' => ObjectUtil::TYPE_STRING,
		];
	}
}

