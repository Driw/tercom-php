<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 *
 *
 * @see AdvancedObject
 * @see OrderQuote
 * @see CustomerEmployee
 * @see TercomEmployee
 * @see Address
 * @see OrderAcceptanceProducts
 * @see OrderAcceptanceServices
 *
 * @author Andrew
 */
class OrderAcceptance extends AdvancedObject
{
	/**
	 * @var int
	 */
	public const MAX_OBSERVATIONS_LEN = TINYTEXT_LEN;
	/**
	 * @var int
	 */
	public const OAS_APPROVING = 0;
	/**
	 * @var int
	 */
	public const OAS_APPROVED = 1;
	/**
	 * @var int
	 */
	public const OAS_REQUEST = 2;
	/**
	 * @var int
	 */
	public const OAS_PAID = 3;
	/**
	 * @var int
	 */
	public const OAS_ON_THE_WAY = 4;

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var OrderQuote
	 */
	private $orderQuote;
	/**
	 * @var CustomerEmployee
	 */
	private $customerEmployee;
	/**
	 * @var TercomEmployee
	 */
	private $tercomEmployee;
	/**
	 * @var Address
	 */
	private $address;
	/**
	 * @var int
	 */
	private $status;
	/**
	 * @var string
	 */
	private $statusDescription;
	/**
	 * @var string
	 */
	private $observations;
	/**
	 * @var \DateTime
	 */
	private $register;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->status = self::OAS_APPROVING;
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
	 * @return OrderQuote
	 */
	public function getOrderQuote(): OrderQuote
	{
		return $this->orderQuote === null ? ($this->orderQuote = new OrderQuote()) : $this->orderQuote;
	}

	/**
	 * @return int
	 */
	public function getOrderQuoteId(): int
	{
		return $this->orderQuote === null ? 0 : $this->orderQuote->getId();
	}

	/**
	 * @param OrderQuote $orderQuote
	 */
	public function setOrderQuote(OrderQuote $orderQuote): void
	{
		$this->orderQuote = $orderQuote;
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
	 * @param CustomerEmployee $customerEmployee
	 */
	public function setCustomerEmployee(CustomerEmployee $customerEmployee): void
	{
		$this->customerEmployee = $customerEmployee;
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
	 */
	public function setTercomEmployee(TercomEmployee $tercomEmployee): void
	{
		$this->tercomEmployee = $tercomEmployee;
	}

	/**
	 * @return Address
	 */
	public function getAddress(): Address
	{
		return $this->address === null ? ($this->address = new Address()) : $this->address;
	}

	/**
	 * @return int
	 */
	public function getAddressId(): int
	{
		return $this->address === null ? 0 : $this->address->getId();
	}

	/**
	 * @param Address $address
	 */
	public function setAddress(Address $address): void
	{
		$this->address = $address;
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
		if (!self::hasOrderAcceptanceState($status))
			throw EntityParseException::new('estado não encontrado (status: %d)', $status);

		$this->status = $status;
		$this->updateStatusDescription();
	}

	/**
	 * @return string
	 */
	public function getStatusDescription()
	{
		return $this->statusDescription;
	}

	/**
	 *
	 */
	public function updateStatusDescription(): void
	{
		$this->statusDescription = self::getOrderAcceptanceState($this->status);
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
		if ($observations !== null && !StringUtil::hasMaxLength($observations, self::MAX_OBSERVATIONS_LEN))
			throw EntityParseException::new('observações devem possuir até %d caracteres', self::MAX_OBSERVATIONS_LEN);

		$this->observations = $observations;
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
	public function setRegister(\DateTime $register)
	{
		$this->register = $register;
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
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::fromArray()
	 */
	public function fromArray($array)
	{
		parent::fromArray($array);

		$this->updateStatusDescription();
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */
	public function getAttributeTypes()
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'orderQuote' => OrderQuote::class,
			'customerEmployee' => CustomerEmployee::class,
			'tercomEmployee' => TercomEmployee::class,
			'address' => Address::class,
			'status' => ObjectUtil::TYPE_INTEGER,
			'observations' => ObjectUtil::TYPE_STRING,
			'register' => ObjectUtil::TYPE_DATE,
		];
	}

	/**
	 * @return array
	 */
	public static function getOrderAcceptanceStatus(): array
	{
		return [
			self::OAS_APPROVING => 'em aprovamento',
			self::OAS_APPROVED => 'aprovada',
			self::OAS_REQUEST => 'solicitado ao fornecedor',
			self::OAS_PAID => 'pagamento efetuado',
			self::OAS_ON_THE_WAY => 'para entrega',
		];
	}

	/**
	 *
	 * @param int $state
	 * @param array $status
	 * @return bool
	 */
	public static function hasOrderAcceptanceState(int $state, ?array $status = null): bool
	{
		if ($status === null) $status = self::getOrderAcceptanceStatus();

		return isset($status[$state]);
	}

	/**
	 *
	 * @param int $state
	 * @param array $status
	 * @return string
	 */
	public static function getOrderAcceptanceState(int $state, ?array $status = null): string
	{
		if ($status === null) $status = self::getOrderAcceptanceStatus();

		return self::hasOrderAcceptanceState($state, $status) ? $status[$state] : "state#$status";
	}
}

