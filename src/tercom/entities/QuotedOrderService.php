<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 * Cotação de Produto de Pedido
 *
 * Classe responsável por armazenar os dados de uma das cotações de um pedido de cotação referente a um item de serviço.
 * Cada objeto deste representa a cotação de um único preço de serviço e um serviço por ter vários preços cotados.
 *
 * @see AdvancedObject
 * @see OrderItemService
 * @see QuotedServicePrice
 *
 * @author Andrew
 */
class QuotedOrderService extends AdvancedObject
{
	/**
	 * @var int item de serviço do pedido de cotação.
	 */
	public const MAX_OBSERVATIONS_LEN = 128;

	/**
	 * @var int código de identificação único da cotação de serviço.
	 */
	private $id;
	/**
	 * @var OrderItemService item de serviço do pedido de cotação.
	 */
	private $orderItemService;
	/**
	 * @var QuotedServicePrice preço de serviço cotado.
	 */
	private $quotedServicePrice;
	/**
	 * @var string observações adicionais referente a cotação do preço.
	 */
	private $observations;

	/**
	 * Cria uma nova instância de uma cotação de serviço de pedido.
	 */
	public function __construct()
	{
		$this->id = 0;
	}

	/**
	 * @return int aquisição do código de identificação único da cotação de serviço.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único da cotação de serviço.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return OrderItemService aquisição do item de serviço do pedido de cotação.
	 */
	public function getOrderItemService(): OrderItemService
	{
		return $this->orderItemService === null ? ($this->orderItemService = new OrderItemService()) : $this->orderItemService;
	}

	/**
	 * @return int aquisição do código de identificação do item de serviço do pedido de cotação.
	 */
	public function getOrderItemServiceId(): int
	{
		return $this->orderItemService === null ? 0 : $this->orderItemService->getId();
	}

	/**
	 * @param OrderItemService $orderItemService item de serviço do pedido de cotação.
	 */
	public function setOrderItemService(OrderItemService $orderItemService): void
	{
		$this->orderItemService = $orderItemService;
	}

	/**
	 * @return QuotedServicePrice aquisição do preço de serviço cotado.
	 */
	public function getQuotedServicePrice(): QuotedServicePrice
	{
		return $this->quotedServicePrice === null ? ($this->quotedServicePrice = new QuotedServicePrice()) : $this->quotedServicePrice;
	}

	/**
	 * @return int aquisição do código de identificação único do preço de serviço cotado.
	 */
	public function getQuotedServicePriceId(): int
	{
		return $this->quotedServicePrice === null ? 0 : $this->quotedServicePrice->getId();
	}

	/**
	 * @param QuotedServicePrice $quotedServicePrice preço de serviço cotado.
	 */
	public function setQuotedServicePrice(QuotedServicePrice $quotedServicePrice): void
	{
		if ($quotedServicePrice->getServiceId() !== $this->orderItemService->getServiceId())
			throw EntityParseException::new('serviço do preço cotado não confere com o item de serviço');

		$this->quotedServicePrice = $quotedServicePrice;
	}

	/**
	 * @return string|NULL aquisição das observações adicionais referente a cotação do preço.
	 */
	public function getObservations(): ?string
	{
		return $this->observations;
	}

	/**
	 * @param string|NULL $observations observações adicionais referente a cotação do preço.
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
			'orderItemService' => OrderItemService::class,
			'quotedServicePrice' => QuotedServicePrice::class,
			'observations' => ObjectUtil::TYPE_STRING,
		];
	}
}

