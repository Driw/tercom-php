<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 * Cotação de Produto de Pedido
 *
 * Classe responsável por armazenar os dados de uma das cotações de um pedido de cotação referente a um item de produto.
 * Cada objeto deste representa a cotação de um único preço de produto e um produto por ter vários preços cotados.
 *
 * @see AdvancedObject
 * @see OrderItemProduct
 * @see QuotedProductPrice
 *
 * @author Andrew
 */
class QuotedOrderProduct extends AdvancedObject
{
	/**
	 * @var int item de produto do pedido de cotação.
	 */
	public const MAX_OBSERVATIONS_LEN = 128;

	/**
	 * @var int código de identificação único da cotação de produto.
	 */
	private $id;
	/**
	 * @var OrderItemProduct item de produto do pedido de cotação.
	 */
	private $orderItemProduct;
	/**
	 * @var QuotedProductPrice preço de produto cotado.
	 */
	private $quotedProductPrice;
	/**
	 * @var string observações adicionais referente a cotação do preço.
	 */
	private $observations;

	/**
	 * Cria uma nova instância de uma cotação de produto de pedido.
	 */
	public function __construct()
	{
		$this->id = 0;
	}

	/**
	 * @return int aquisição do código de identificação único da cotação de produto.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único da cotação de produto.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return OrderItemProduct aquisição do item de produto do pedido de cotação.
	 */
	public function getOrderItemProduct(): OrderItemProduct
	{
		return $this->orderItemProduct === null ? ($this->orderItemProduct = new OrderItemProduct()) : $this->orderItemProduct;
	}

	/**
	 * @return int aquisição do código de identificação do item de produto do pedido de cotação.
	 */
	public function getOrderItemProductId(): int
	{
		return $this->orderItemProduct === null ? 0 : $this->orderItemProduct->getId();
	}

	/**
	 * @param OrderItemProduct $orderItemProduct item de produto do pedido de cotação.
	 */
	public function setOrderItemProduct(OrderItemProduct $orderItemProduct): void
	{
		$this->orderItemProduct = $orderItemProduct;
	}

	/**
	 * @return QuotedProductPrice aquisição do preço de produto cotado.
	 */
	public function getQuotedProductPrice(): QuotedProductPrice
	{
		return $this->quotedProductPrice === null ? ($this->quotedProductPrice = new QuotedProductPrice()) : $this->quotedProductPrice;
	}

	/**
	 * @return int aquisição do código de identificação único do preço de produto cotado.
	 */
	public function getQuotedProductPriceId(): int
	{
		return $this->quotedProductPrice === null ? 0 : $this->quotedProductPrice->getId();
	}

	/**
	 * @param QuotedProductPrice $quotedProductPrice preço de produto cotado.
	 */
	public function setQuotedProductPrice(QuotedProductPrice $quotedProductPrice): void
	{
		if ($quotedProductPrice->getProductId() !== $this->orderItemProduct->getProductId())
			throw EntityParseException::new('produto do preço cotado não confere com o item de produto');

		$this->quotedProductPrice = $quotedProductPrice;
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
			'orderItemProduct' => OrderItemProduct::class,
			'quotedProductPrice' => QuotedProductPrice::class,
			'observations' => ObjectUtil::TYPE_STRING,
		];
	}
}

