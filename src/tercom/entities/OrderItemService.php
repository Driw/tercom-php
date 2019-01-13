<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 * Item de Serviço de Serviço
 *
 * Um pedido possui dois tipos de itens, esta classe representa um dos tipos de itens que é o item de serviço.
 * Cada item de serviço é responsável por detalhar as preferências e um dos serviços do pedido para cotação.
 * Os detalhes incluem preferência de fornecedor e se deseja o melhor preço (mais barato).
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
	 * @var int limite de caracteres para observações do item.
	 */
	public const MAX_OBSERVATIONS_LEN = 128;

	/**
	 * @var int código de identificação único do item de serviço de pedido.
	 */
	private $id;
	/**
	 * @var Service serviço do qual deve ser cotado no pedido.
	 */
	private $service;
	/**
	 * @var Provider preferência de fornecedor.
	 */
	private $provider;
	/**
	 * @var bool melhor preço ordena do mais barato para o mais caro.
	 */
	private $betterPrice;
	/**
	 * @var string observações adicionais referente ao item.
	 */
	private $observations;

	/**
	 * Cria uma nova instância de um item de serviço de pedido.
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->betterPrice = false;
	}

	/**
	 * @return int aquisição do código de identificação único do item de serviço de pedido.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do item de serviço de pedido.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return Service aquisição do serviço do qual deve ser cotado no pedido.
	 */
	public function getService(): Service
	{
		return $this->service === null ? ($this->service = new Service()) : $this->service;
	}

	/**
	 * @return int aquisição do código de identificação do serviço do qual deve ser cotado no pedido.
	 */
	public function getServiceId(): int
	{
		return $this->service === null ? 0 : $this->service->getId();
	}

	/**
	 * @param Service $service serviço do qual deve ser cotado no pedido.
	 */
	public function setService(Service $service): void
	{
		$this->service = $service;
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
	 * @param Provider $provider preferência de fornecedor.
	 */
	public function setProvider(Provider $provider): void
	{
		$this->provider = $provider;
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
			'service' => Service::class,
			'provider' => Provider::class,
			'manufacturer' => Manufacturer::class,
			'betterPrice' => ObjectUtil::TYPE_BOOLEAN,
			'observation' => ObjectUtil::TYPE_STRING,
		];
	}
}

