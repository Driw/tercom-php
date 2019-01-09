<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\FloatUtil;

/**
 * @author Andrew
 */
class OrderRequest extends AdvancedObject
{
	/**
	 * @var float
	 */
	public const MIN_BUDGET = 0.00;
	/**
	 * @var int
	 */
	public const ORS_NONE = 0;
	/**
	 * @var int
	 */
	public const ORS_CANCEL_BY_CUSTOMER = 1;
	/**
	 * @var int
	 */
	public const ORS_CANCEL_BY_TERCOM = 2;
	/**
	 * @var int
	 */
	public const ORS_QUEUED = 3;
	/**
	 * @var int
	 */
	public const ORS_QUOTING = 4;
	/**
	 * @var int
	 */
	public const ORS_QUOTED = 5;
	/**
	 * @var int
	 */
	public const ORS_DONE = 6;

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var float
	 */
	private $budget;
	/**
	 * @var int
	 */
	private $status;
	/**
	 * @var string
	 */
	private $statusMessage;
	/**
	 * @var \DateTime
	 */
	private $register;
	/**
	 * @var \DateTime
	 */
	private $expiration;
	/**
	 * @var CustomerEmployee
	 */
	private $customerEmployee;
	/**
	 * @var TercomEmployee
	 */
	private $tercomEmployee;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->budget = 0.0;
		$this->status = self::ORS_NONE;
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
	 * @return OrderRequest
	 */
	public function setId(int $id): OrderRequest
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getBudget(): float
	{
		return $this->budget;
	}

	/**
	 * @param number $budget
	 * @return OrderRequest
	 */
	public function setBudget(float $budget): OrderRequest
	{
		if (!FloatUtil::inMin($budget, self::MIN_BUDGET))
			throw new EntityParseException('orçamento não pode ser inferior a R$ %.2f', self::MIN_BUDGET);

		$this->budget = $budget;
		return $this;
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
	 * @return OrderRequest
	 */
	public function setStatus(int $status): OrderRequest
	{
		$this->status = $status;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getStatusMessage(): string
	{
		return $this->statusMessage;
	}

	/**
	 * @param string $statusMessage
	 * @return OrderRequest
	 */
	public function setStatusMessage(string $statusMessage): OrderRequest
	{
		$this->statusMessage = $statusMessage;
		return $this;
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
	 * @return OrderRequest
	 */
	public function setRegister(\DateTime $register): OrderRequest
	{
		$this->register = $register;
		return $this;
	}

	/**
	 *
	 */
	public function setRegisterCurrent(): void
	{
		$register = new \DateTime();
		$register->setTimestamp(time());
		$this->setRegister($register);
	}

	/**
	 * @return \DateTime|NULL
	 */
	public function getExpiration(): ?\DateTime
	{
		return $this->expiration;
	}

	/**
	 * @param \DateTime|NULL $expiration
	 * @return OrderRequest
	 */
	public function setExpiration(?\DateTime $expiration): OrderRequest
	{
		if ($expiration->getTimestamp() < strtotime('+24 hour'))
			throw EntityParseException::new('horário de expiração deve ser posterior a 24h');

		$this->expiration = $expiration;
		return $this;
	}

	/**
	 * @return CustomerEmployee
	 */
	public function getCustomerEmployee(): CustomerEmployee
	{
		return $this->customerEmployee === null ? ($this->customerEmployee = new CustomerEmployee()) : $this->customerEmployee;
	}

	/**
	 * @return int
	 */
	public function getCustomerEmployeeId(): int
	{
		return $this->customerEmployee === null ? 0 : $this->customerEmployee->getId();
	}

	/**
	 * @param number $customerEmployee
	 * @return OrderRequest
	 */
	public function setCustomerEmployee(CustomerEmployee $customerEmployee): OrderRequest
	{
		$this->customerEmployee = $customerEmployee;
		return $this;
	}

	/**
	 * @return TercomEmployee
	 */
	public function getTercomEmployee(): TercomEmployee
	{
		return $this->tercomEmployee === null ? ($this->tercomEmployee = new TercomEmployee()) : $this->tercomEmployee;
	}

	/**
	 * @return int
	 */
	public function getTercomEmployeeId(): int
	{
		return $this->tercomEmployee === null ? 0 : $this->tercomEmployee->getId();
	}

	/**
	 * @param TercomEmployee $tercomEmployee
	 * @return OrderRequest
	 */
	public function setTercomEmployee(TercomEmployee $tercomEmployee): OrderRequest
	{
		$this->tercomEmployee = $tercomEmployee;
		return $this;
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
}

