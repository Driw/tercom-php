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
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->name = '';
		$this->additionalDescription = '';
		$this->price = 0.0;
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
	 * @return ServicePrice aquisição do objeto de preço ser serviço usado.
	 */
	public function setId(int $id): ServicePrice
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
	 * @return ServicePrice aquisição do objeto de preço ser serviço usado.
	 */
	public function setService(Service $service): ServicePrice
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
	 * @param Provider $provider objeto do tipo fornecedor que fornece o preço.
	 * @return ServicePrice aquisição do objeto de preço ser serviço usado.
	 */
	public function setProvider(Provider $provider): ServicePrice
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
	 * @return ServicePrice aquisição do objeto de preço ser serviço usado.
	 */
	public function setName(string $name): ServicePrice
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
	 * @return ServicePrice aquisição do objeto de preço ser serviço usado.
	 */
	public function setAdditionalDescription(?string $additionalDescription): ServicePrice
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
	 * @return ServicePrice aquisição do objeto de preço ser serviço usado.
	 */
	public function setPrice(float $price): ServicePrice
	{
		if (!FloatUtil::inMin($price, self::MIN_PRICE))
			throw EntityParseException::new('preço do serviço deve ser maior ou igual a %.2f', self::MIN_PRICE);

		$this->price = $price;
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
		];
	}
}

