<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\FloatUtil;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 *
 *
 * @see QuotedServicePrice
 *
 * @author Andrew
 */
class OrderAcceptanceService extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres para o nome do preço de serviço.
	 */
	public const MIN_NAME_LEN = ServicePrice::MIN_NAME_LEN;
	/**
	 * @var int quantidade máxima de caracteres para o nome do preço de serviço.
	 */
	public const MAX_NAME_LEN = ServicePrice::MAX_NAME_LEN;
	/**
	 * @var int quantidade máxima de caracteres para a descrição adicional.
	 */
	public const MAX_ADDITIONAL_DESCRIPTION = ServicePrice::MAX_ADDITIONAL_DESCRIPTION;
	/**
	 * @var float valor mínimo permitido para definir o preço do serviço.
	 */
	public const MIN_PRICE = ServicePrice::MIN_PRICE;
	/**
	 * @var float valor máximo permitido para definir o preço do serviço.
	 */
	public const MAX_PRICE = ServicePrice::MAX_PRICE;

	/**
	 * @var int código de identificação único do preço de serviço aceito.
	 */
	private $id;
	/**
	 * @var int código de identificação único do preço de serviço cotado.
	 */
	private $idQuotedServicePrice;
	/**
	 * @var Service objeto do tipo serviço do qual pertence o preço de serviço cotado.
	 */
	private $service;
	/**
	 * @var Provider objeto do tipo fornecedor que fornece o preço cotado.
	 */
	private $provider;
	/**
	 * @var string nome do preço de serviço cotado.
	 */
	private $name;
	/**
	 * @var string dascrição adicional.
	 */
	private $additionalDescription;
	/**
	 * @var float preço do serviço cotado.
	 */
	private $price;
	/**
	 * @var int quantidade de vezes solicitado.
	 */
	private $amountRequest;
	/**
	 * @var float preço total para aquisição da quantidade do produto.
	 */
	private $subprice;
	/**
	 * @var string observações referente ao preço de serviço cotado.
	 */
	private $observations;
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
	 * @return int aquisição do código de identificação único do preço de serviço cotado.
	 */
	public function getIdQuotedServicePrice(): int
	{
		return $this->idQuotedServicePrice;
	}

	/**
	 * @param int $idQuotedServicePrice código de identificação único do preço de serviço cotado.
	 */
	public function setIdQuotedServicePrice(int $idQuotedServicePrice): void
	{
		$this->idQuotedServicePrice = $idQuotedServicePrice;
	}

	/**
	 * @param QuotedServicePrice $quotedServicePrice objeto do tipo preço de serviço baseado.
	 */
	public function setQuotedServicePrice(QuotedServicePrice $quotedServicePrice): void
	{
		$array = $quotedServicePrice->toArray(true);
		$array['idQuotedServicePrice'] = $array['id'];
		unset($array['id']);

		$this->fromArray($array);
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
	 * @return string|NULL aquisição da dascrição adicional.
	 */
	public function getAdditionalDescription(): ?string
	{
		return $this->additionalDescription;
	}

	/**
	 * @param string|NULL $additionalDescription dascrição adicional.
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
		if (!FloatUtil::inMin($price, self::MIN_PRICE, self::MAX_PRICE))
			throw EntityParseException::new('preço do serviço deve ser de R$ %.2f a R$ %.2f', self::MIN_PRICE, self::MAX_PRICE);

		$this->price = $price;
		$this->updateSubPrice();
	}

	/**
	 * @return int aquisição da quantidade de vezes solicitado.
	 */
	public function getAmountRequest(): int
	{
		return $this->amountRequest;
	}

	/**
	 * @param int $amountRequest quantidade de vezes solicitado.
	 */
	public function setAmountRequest(int $amountRequest): void
	{
		$this->amountRequest = $amountRequest;
		$this->updateSubPrice();
	}

	/**
	 * @return int aquisição do preço total para aquisição da quantidade do produto.
	 */
	public function getSubprice(): float
	{
		return $this->subprice;
	}

	/**
	 * @param float $subprice preço total para aquisição da quantidade do produto.
	 */
	public function setSubprice(float $subprice): void
	{
		if (!FloatUtil::inInterval($subprice, self::MIN_PRICE, self::MAX_PRICE))
			throw EntityParseException::new("subpreço deve ser de R$ %.2f a R$ %.2f (subpreço: $subprice)", self::MIN_PRICE, self::MAX_PRICE);

		$this->subprice = floatval(sprintf('%.2f', $subprice));
	}

	/**
	 *
	 */
	public function updateSubprice(): void
	{
		$this->setSubprice($this->amountRequest * $this->price);
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
	public function setObservations(?string $observations)
	{
		$this->observations = $observations;
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
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */
	public function getAttributeTypes(): array
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'idQuotedServicePrice' => ObjectUtil::TYPE_INTEGER,
			'service' => Service::class,
			'provider' => Provider::class,
			'name' => ObjectUtil::TYPE_STRING,
			'price' => ObjectUtil::TYPE_FLOAT,
			'amountRequest' => ObjectUtil::TYPE_INTEGER,
			'subprice' => ObjectUtil::TYPE_FLOAT,
			'observations' => ObjectUtil::TYPE_STRING,
			'lastUpdate' => ObjectUtil::TYPE_DATE,
		];
	}
}

