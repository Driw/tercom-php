<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\FloatUtil;

/**
 * Solicitação de Pedido de Cotação
 *
 * Corresponde a uma solicitação feita por um funcionário de cliente afim de criar um pedido de cotação à TERCOM.
 * Um funcionário da TERCOM irá se atribuir a solicitação para realizar o procedimento de cotação do pedido.
 *
 * Um pedido possui um orçamento máximo que será usado posteriormente, estado para determinar sua situação no sistema,
 * vinculo obrigatório com um funcionário de cliente (quem solicitou) e posteriormente pode ter um funcionário TERCOM
 * (quem vai fazer a cotação), horário de registro da solicitação e horário limite aceito para fazer a cotação.
 *
 * @see AdvancedObject
 * @see CustomerEmployee
 * @see TercomEmployee
 *
 * @author Andrew
 */
class OrderRequest extends AdvancedObject
{
	/**
	 * @var float valor mínimo permitido para orçamento do pedido.
	 */
	public const MIN_BUDGET = 0.00;

	/**
	 * @var int código do estado de solicitação criada.
	 */
	public const ORS_NONE = 0;
	/**
	 * @var int código do estado de solicitação cancelada pelo cliente.
	 */
	public const ORS_CANCEL_BY_CUSTOMER = 1;
	/**
	 * @var int código do estado de solicitação cancelada pela TERCOM.
	 */
	public const ORS_CANCEL_BY_TERCOM = 2;
	/**
	 * @var int código do estado de solicitação em fila de espera para cotação.
	 */
	public const ORS_QUEUED = 3;
	/**
	 * @var int código do estado de solicitação em realizçaão da cotação.
	 */
	public const ORS_QUOTING = 4;
	/**
	 * @var int código do estado de solicitação com cotação concluída.
	 */
	public const ORS_QUOTED = 5;
	/**
	 * @var int código do estado de solicitação concluída.
	 */
	public const ORS_DONE = 6;

	/**
	 * @var int código de identificação único da solicitação de pedido de cotação.
	 */
	private $id;
	/**
	 * @var float valor de orçamento para cotação.
	 */
	private $budget;
	/**
	 * @var int estado da solicitação de pedido de cotação.
	 */
	private $status;
	/**
	 * @var string mensagem adicional referente ao estado atual.
	 */
	private $statusMessage;
	/**
	 * @var \DateTime horário de registro da solicitação.
	 */
	private $register;
	/**
	 * @var \DateTime horário de expiração (limite) para realizar a cotação.
	 */
	private $expiration;
	/**
	 * @var CustomerEmployee funcionário de cliente que realizou a solicitação.
	 */
	private $customerEmployee;
	/**
	 * @var TercomEmployee funcionário TERCOM responsável pela cotação da solicitação.
	 */
	private $tercomEmployee;

	/**
	 * Cria uma nova instância de uma solicitação de pedido de cotação.
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->status = self::ORS_NONE;
		$this->register = new \DateTime();
	}

	/**
	 * @return int código de identificação único da solicitação de pedido de cotação.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único da solicitação de pedido de cotação.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return float aquisição do valor de orçamento para cotação.
	 */
	public function getBudget(): ?float
	{
		return $this->budget;
	}

	/**
	 * @param number $budget valor de orçamento para cotação.
	 */
	public function setBudget(?float $budget): void
	{
		if ($budget !== null && !FloatUtil::inMin($budget, self::MIN_BUDGET))
			throw new EntityParseException('orçamento não pode ser inferior a R$ %.2f', self::MIN_BUDGET);

		$this->budget = $budget;
	}

	/**
	 * Conferir os estados disponíveis através de <code>ORS_*</code>.
	 * @return int aquisição estado da solicitação de pedido de cotação.
	 */
	public function getStatus(): int
	{
		return $this->status;
	}

	/**
	 * Conferir os estados disponíveis através de <code>ORS_*</code>.
	 * @param int $status estado da solicitação de pedido de cotação.
	 */
	public function setStatus(int $status): void
	{
		if (!self::hasStatus($status))
			throw EntityParseException::new('estado desconhecido (status: %d)', $status);

		$this->status = $status;
		$this->setStatusMessage(self::getStatusMessageDescription($status));
	}

	/**
	 * @return string aquisição da mensagem adicional referente ao estado atual.
	 */
	public function getStatusMessage(): string
	{
		return $this->statusMessage;
	}

	/**
	 * @param string $statusMessage mensagem adicional referente ao estado atual.
	 */
	public function setStatusMessage(string $statusMessage): void
	{
		$this->statusMessage = $statusMessage;
	}

	/**
	 * @return \DateTime aquisição do horário de registro da solicitação.
	 */
	public function getRegister(): \DateTime
	{
		return $this->register;
	}

	/**
	 * @param \DateTime $register horário de registro da solicitação.
	 */
	public function setRegister(\DateTime $register): void
	{
		$this->register = $register;
	}

	/**
	 * Procedimento para atualizar o horário de registro da solicitação conforme o horário atual.
	 */
	public function setRegisterCurrent(): void
	{
		$register = new \DateTime();
		$register->setTimestamp(time());
		$this->setRegister($register);
	}

	/**
	 * @return \DateTime|NULL aquisição do horário de expiração (limite) para realizar a cotação.
	 */
	public function getExpiration(): ?\DateTime
	{
		return $this->expiration;
	}

	/**
	 * @param \DateTime|NULL $expiration horário de expiração (limite) para realizar a cotação.
	 */
	public function setExpiration(?\DateTime $expiration): void
	{
		if ($expiration->getTimestamp() < strtotime('+24 hour'))
			throw EntityParseException::new('horário de expiração deve ser posterior a 24h');

		$this->expiration = $expiration;
	}

	/**
	 * @return CustomerEmployee aquisição do funcionário de cliente que realizou a solicitação.
	 */
	public function getCustomerEmployee(): CustomerEmployee
	{
		return $this->customerEmployee === null ? ($this->customerEmployee = new CustomerEmployee()) : $this->customerEmployee;
	}

	/**
	 * @return int aquisição do código de identificação do funcionário de cliente que realizou a solicitação.
	 */
	public function getCustomerEmployeeId(): int
	{
		return $this->customerEmployee === null ? 0 : $this->customerEmployee->getId();
	}

	/**
	 * @param number $customerEmployee funcionário de cliente que realizou a solicitação.
	 */
	public function setCustomerEmployee(CustomerEmployee $customerEmployee): void
	{
		$this->customerEmployee = $customerEmployee;
	}

	/**
	 * @return TercomEmployee aquisição do funcionário TERCOM responsável pela cotação da solicitação.
	 */
	public function getTercomEmployee(): TercomEmployee
	{
		return $this->tercomEmployee === null ? ($this->tercomEmployee = new TercomEmployee()) : $this->tercomEmployee;
	}

	/**
	 * @return int aquisição do código de identificação do funcionário TERCOM responsável pela cotação da solicitação.
	 */
	public function getTercomEmployeeId(): int
	{
		return $this->tercomEmployee === null ? 0 : $this->tercomEmployee->getId();
	}

	/**
	 * @param TercomEmployee $tercomEmployee funcionário TERCOM responsável pela cotação da solicitação.
	 */
	public function setTercomEmployee(TercomEmployee $tercomEmployee): void
	{
		$this->tercomEmployee = $tercomEmployee;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */
	public function getAttributeTypes(): array
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'budget' => ObjectUtil::TYPE_FLOAT,
			'register' => ObjectUtil::TYPE_DATE,
			'expiration' => ObjectUtil::TYPE_DATE,
			'customerEmployee' => CustomerEmployee::class,
			'tercomEmployee' => TercomEmployee::class,
		];
	}

	/**
	 * @return array aquisição de um vetor com os estados disponíveis para solicitações de pedido de cotação.
	 */
	public static function getStatus(): array
	{
		return [
			self::ORS_NONE => 'solicitação criada',
			self::ORS_CANCEL_BY_CUSTOMER => 'cancelado pelo cliente',
			self::ORS_CANCEL_BY_TERCOM => 'cancelado pela TERCOM',
			self::ORS_QUEUED => 'solicitação em fila',
			self::ORS_QUOTING => 'solicitação em cotação',
			self::ORS_QUOTED => 'solicitação cotada',
			self::ORS_DONE => 'solicitação concluída',
		];
	}

	/**
	 * Verifica se um determinado estado de solicitação de pedido de cotação existe.
	 * @param int $state código do estado do qual será verificado.
	 * @param array $status [optional] vetor contendo os estados válidos.
	 * @return bool true se existir ou false caso contrário.
	 */
	public static function hasStatus(int $state, ?array $status = null): bool
	{
		if ($status === null) $status = self::getStatus();

		return isset($status[$state]);
	}

	/**
	 * Obtém a mensagem padrão para descrever o estado de uma solicitação de pedido de cotação.
	 * @param int $state código do estado do qual deseja obter a mensagem.
	 * @param array $status [optional] vetor contendo os estados válidos.
	 * @return string aquisição da mensagem padrão referente ao estado informado.
	 */
	public static function getStatusMessageDescription(int $state, ?array $status = null): string
	{
		if ($status === null) $status = self::getStatus();

		return self::hasStatus($state, $status) ? $status[$state] : "Status#$state";
	}
}

