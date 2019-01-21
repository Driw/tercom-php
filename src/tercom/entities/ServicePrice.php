<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;
use dProject\Primitive\FloatUtil;

/***
 * Preço de Serviço
 *
 * O preço de serviço possui informações do que o cliente irá escolher como serviço final na sua cotação.
 * Um serviço possui vários preços de serviço e cada preço de serviço é fornecido por um único fornecedor.
 * Atualmente as especificações de funcionalidade e/ou categorização dos serviços é feito descritivamente.
 *
 * @see AdvancedObject
 *
 * @author Andrew
 */
class ServicePrice extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres para o nome do preço de serviço.
	 */
	public const MIN_NAME_LEN = 2;
	/**
	 * @var int quantidade máxima de caracteres para o nome do preço de serviço.
	 */
	public const MAX_NAME_LEN = 48;
	/**
	 * @var int quantidade máxima de caracteres para a descrição adicional.
	 */
	public const MAX_ADDITIONAL_DESCRIPTION = 256;
	/**
	 * @var float valor mínimo permitido para definir o preço do serviço.
	 */
	public const MIN_PRICE = 0.0;
	/**
	 * @var float valor máximo permitido para definir o preço do serviço.
	 */
	public const MAX_PRICE = 99999.99;

	/**
	 * @var int código de identificação único do preço de serviço.
	 */
	private $id;
	/**
	 * @var Service objeto do tipo serviço do qual pertence o preço de serviço.
	 */
	private $service;
	/**
	 * @var Provider objeto do tipo fornecedor que fornece o preço.
	 */
	private $provider;
	/**
	 * @var string nome do preço de serviço.
	 */
	private $name;
	/**
	 * @var string dascrição adicional.
	 */
	private $additionalDescription;
	/**
	 * @var float preço do serviço.
	 */
	private $price;
	/**
	 * @var \DateTime horário da última atualização do preço de produto.
	 */
	private $lastUpdate;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->name = '';
		$this->additionalDescription = '';
		$this->price = 0.0;
		$this->lastUpdate = new \DateTime();
	}

	/**
	 * @return int aquisição do código de identificação único do preço de serviço.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do preço de serviço.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return Service objeto do tipo serviço do qual pertence o preço de serviço.
	 */
	public function getService(): Service
	{
		return $this->service === null ? ($this->service = new Service()) : $this->service;
	}

	/**
	 * @param Service $service aquisição do objeto do tipo serviço do qual pertence o preço de serviço.
	 */
	public function setService(Service $service): void
	{
		$this->service = $service;
	}

	/**
	 * @return int aquisição do código de identificação do serviço ou zero se não definido.
	 */
	public function getServiceId(): int
	{
		return $this->service === null ? 0 : $this->service->getId();
	}

	/**
	 * @return Provider aquisição do objeto do tipo fornecedor que fornece o preço.
	 */
	public function getProvider(): Provider
	{
		return $this->provider === null ? ($this->provider = new Provider()) : $this->provider;
	}

	/**
	 * @return int aquisição do código de identificação único do fornecedor que fornece o preço.
	 */
	public function getProviderId(): int
	{
		return $this->provider->getId();
	}

	/**
	 * @param Provider $provider objeto do tipo fornecedor que fornece o preço.
	 */
	public function setProvider(Provider $provider): void
	{
		$this->provider = $provider;
	}

	/**
	 * @return int aquisição do código de identificação do fornecedor ou zero se não definido.
	 */
	public function getIdProvider(): int
	{
		return $this->provider === null ? 0 : $this->provider->getID();
	}

	/**
	 * @return string aquisição do nome do preço de serviço.
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome do preço de serviço.
	 */
	public function setName(string $name): void
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
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
	 * @return float aquisição do preço do serviço.
	 */
	public function getPrice(): float
	{
		return $this->price;
	}

	/**
	 * @param float $price preço do serviço.
	 */
	public function setPrice(float $price): void
	{
		if (!FloatUtil::inInterval($price, self::MIN_PRICE, self::MAX_PRICE))
			throw EntityParseException::new('preço do serviço deve ser de R$ %.2f a R$ %.2f', self::MIN_PRICE, self::MAX_PRICE);

		$this->price = $price;
	}

	/**
	 * @return \DateTime aquisição do horário da última atualização do preço de serviço.
	 */
	public function getLastUpdate(): \DateTime
	{
		return $this->lastUpdate;
	}

	/**
	 * @param \DateTime $datetime horário da última atualização do preço de serviço.
	 */
	public function setLastUpdate(\DateTime $lastUpdate): void
	{
		$this->lastUpdate = $lastUpdate;
	}

	/**
	 * O sistema por padrão considera o preço do produto desatualizado após 7 dias.
	 * @return bool true se for necessário atualizar o preço do produto ou false caso contrário.
	 */
	public function isNeedUpdate(): bool
	{
		$nextUpdateTimestamp = strtotime('+7 days', $this->lastUpdate->getTimestamp());

		return $nextUpdateTimestamp < time();
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
			'price' => ObjectUtil::TYPE_FLOAT,
			'lastUpdate' => ObjectUtil::TYPE_DATE,
		];
	}
}

