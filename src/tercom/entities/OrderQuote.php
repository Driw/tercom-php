<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;

/**
 * Quotação de Pedido
 *
 * Uma cotação de pedido é feita por um único funcionário da TERCOM a partir de uma solicitação de pedido de cotação.
 * Contém <b>N</b> preços para cada produto que o funcionário de cliente solicitou no pedido.
 * A cotação deve ainda respeitar as preferências do cliente conforme preço, fornecedor e/ou frabricante se houver.
 *
 * @see AdvancedObject
 *
 * @author Andrew
 */
class OrderQuote extends AdvancedObject
{
	/**
	 * @var int código para o estado de <b>cotação em andamento</b>.
	 */
	public const OQS_DOING = 0;
	/**
	 * @var int código para o estado de <b>cotação concluída</b>.
	 */
	public const OQS_DONE = 1;

	/**
	 * @var int código de identificação único da cotação.
	 */
	private $id;
	/**
	 * @var OrderRequest pedido de cotação feita pelo cliente.
	 */
	private $orderRequest;
	/**
	 * @var int código do estado em que se encontra a cotação do pedido.
	 */
	private $status;
	/**
	 * @var \DateTime horário de registro da cotação.
	 */
	private $register;

	/**
	 * Cria uma nova instância de cotação de pedido.
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->status = self::OQS_DOING;
		$this->register = new \DateTime();
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
	 * @return OrderRequest aquisição do pedido de cotação feita pelo cliente.
	 */
	public function getOrderRequest(): OrderRequest
	{
		return $this->orderRequest === null ? ($this->orderRequest = new OrderRequest()) : $this->orderRequest;
	}

	/**
	 * @return int
	 */
	public function getOrderRequestId(): int
	{
		return $this->orderRequest === null ? 0 : $this->orderRequest->getId();
	}

	/**
	 * @param OrderRequest $orderRequest pedido de cotação feita pelo cliente.
	 */
	public function setOrderRequest(OrderRequest $orderRequest): void
	{
		$this->orderRequest = $orderRequest;
	}

	/**
	 * @return int
	 */
	public function getStatus(): int
	{
		return $this->status;
	}

	/**
	 * @param int $status
	 */
	public function setStatus(int $status): void
	{
		$this->status = $status;
	}

	/**
	 * @return \DateTime
	 */
	public function getRegister(): \DateTime
	{
		return $this->register;
	}

	/**
	 * @param \DateTime $register
	 */
	public function setRegister(\DateTime $register): void
	{
		$this->register = $register;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */
	public function getAttributeTypes()
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'orderRequest' => OrderRequest::class,
			'status' => ObjectUtil::TYPE_INTEGER,
			'register' => ObjectUtil::TYPE_DATE,
		];
	}
}

