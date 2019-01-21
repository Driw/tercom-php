<?php

namespace tercom\entities;

use DateTime;
use dProject\Primitive\AdvancedObject;
use dProject\Primitive\FloatUtil;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 * Preço de Serviço Cotado
 *
 * Um serviço pode possuir diversos presços e cada preço irá possuir algumas informações como:
 * fornecedor do qual oferece o serviço no preço e quantidade informada e opcionalmente descrição adicional.
 *
 * @see AdvancedObject
 * @author Andrew
 */

class QuotedServicePrice extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres no nome do preço.
	 */
	public const MIN_NAME_LEN = ServicePrice::MIN_NAME_LEN;
	/**
	 * @var int quantidade máxima de caracteres no nome do preço.
	 */
	public const MAX_NAME_LEN = ServicePrice::MAX_NAME_LEN;
	/**
	 * @var int quantidade máxima de caracteres para a descrição adicional.
	 */
	public const MAX_ADDITIONAL_DESCRIPTION = ServicePrice::MAX_ADDITIONAL_DESCRIPTION;
	/**
	 * @var int valor mínimo permitido por preço de serviço cotado.
	 */
	public const MIN_PRICE = ServicePrice::MIN_PRICE;
	/**
	 * @var int valor máximo permitido por preço de serviço cotado.
	 */
	public const MAX_PRICE = ServicePrice::MAX_PRICE;

	/**
	 * @var int código de identificação único do preço de serviço cotado.
	 */
	private $id;
	/**
	 * @var Service objeto do tipo serviço do qual o preço pertence.
	 */
	private $service;
	/**
	 * @var Provider objeto do tipo fornecedor que oferece o preço.
	 */
	private $provider;
	/**
	 * @var string nome de exibição no preço do serviço.
	 */
	private $name;
	/**
	 * @var string dascrição adicional.
	 */
	private $additionalDescription;
	/**
	 * @var float preço total para aquisição do serviço.
	 */
	private $price;
	/**
	 * @var DateTime horário da última atualização do preço de serviço cotado.
	 */
	private $lastUpdate;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->price = 0.0;
		$this->lastUpdate = new \DateTime();
	}

	/**
	 * @return int aquisição do código de identificação único do preço de serviço cotado.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do preço de serviço cotado.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return Service aquisição do objeto do tipo serviço do qual o preço pertence.
	 */
	public function getService(): Service
	{
		return $this->service;
	}

	/**
	 * @return int aquisição do código de identificação do serviço do preço ou zero se inválido.
	 */
	public function getServiceId(): int
	{
		return $this->service === null ? 0 : $this->service->getId();
	}

	/**
	 * @param Service $service objeto do tipo serviço do qual o preço pertence.
	 */
	public function setService(Service $service): void
	{
		$this->service = $service;
	}

	/**
	 * @return Provider aquisição do objeto do tipo fornecedor que oferece o preço.
	 */
	public function getProvider(): Provider
	{
		return $this->provider;
	}

	/**
	 * @return int aquisição do código de identificação do fornecedor ou zero se inválido.
	 */
	public function getProviderId(): int
	{
		return $this->provider === null ? 0 : $this->provider->getId();
	}

	/**
	 * @param Provider $providerProvider objeto do tipo fornecedor que oferece o preço.
	 */
	public function setProvider(Provider $provider): void
	{
		$this->provider = $provider;
	}

	/**
	 * @return string|NULL aquisição do nome de exibição no preço do serviço.
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome de exibição no preço do serviço.
	 */
	public function setName(?string $name): void
	{
		if (!empty($name) && !StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres (nome: $name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return string aquisição da dascrição adicional.
	 */
	public function getAdditionalDescription(): string
	{
		return $this->additionalDescription;
	}

	/**
	 * @param string $additionalDescription dascrição adicional.
	 */
	public function setAdditionalDescription(?string $additionalDescription): void
	{
		if ($additionalDescription !== null && !StringUtil::hasMaxLength($additionalDescription, self::MAX_ADDITIONAL_DESCRIPTION))
			throw EntityParseException::new("descrição adicional deve possuir até %d caracteres", self::MAX_ADDITIONAL_DESCRIPTION);

		$this->additionalDescription = $additionalDescription;
	}

	/**
	 * @return int aquisição do preço total para aquisição do serviço.
	 */
	public function getPrice(): float
	{
		return $this->price;
	}

	/**
	 * @param int $price preço total para aquisição do serviço.
	 */
	public function setPrice(float $price): void
	{
		if (!FloatUtil::inInterval($price, self::MIN_PRICE, self::MAX_PRICE))
			throw EntityParseException::new("preço deve ser de R$ %.2f a R$ %.2f (preço: $price)", self::MIN_PRICE, self::MAX_PRICE);

		$this->price = $price;
	}

	/**
	 * @return DateTime aquisição do horário da última atualização do preço de serviço cotado.
	 */
	public function getLastUpdate(): DateTime
	{
		return $this->lastUpdate;
	}

	/**
	 * @param DateTime $datetime horário da última atualização do preço de serviço cotado.
	 */
	public function setLastUpdate(DateTime $lastUpdate): void
	{
		$this->lastUpdate = $lastUpdate;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */
	public function getAttributeTypes(): array
	{
		return [
			'service' => Service::class,
			'provider' => Provider::class,
			'name' => ObjectUtil::TYPE_STRING,
			'additionalDescription' => ObjectUtil::TYPE_STRING,
			'price' => ObjectUtil::TYPE_FLOAT,
			'lastUpdate' => ObjectUtil::TYPE_DATE,
		];
	}
}

