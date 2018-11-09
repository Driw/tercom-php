<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\IntegerUtil;
use dProject\Primitive\StringUtil;

/**
 * @see Customer
 * @author Andrew
 */
class CustomerProfile extends AdvancedObject implements Entity
{
	/**
	 * @var int
	 */
	public const MIN_NAME_LEN = 3;
	/**
	 * @var int
	 */
	public const MAX_NAME_LEN = 64;
	/**
	 * @var int
	 */
	public const MIN_ASSIGNMENT_LEVEL = Permission::MIN_ASSIGNMENT_LEVEL;
	/**
	 * @var int
	 */
	public const MAX_ASSIGNMENT_LEVEL = Permission::MAX_ASSIGNMENT_LEVEL;

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var Customer
	 */
	private $customer;
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var int
	 */
	private $assignmentLevel;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->customer = new Customer();
		$this->name = '';
		$this->assignmentLevel = 0;
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
	public function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return Customer
	 */
	public function getCustomer(): Customer
	{
		return $this->customer;
	}

	/**
	 * @param Customer $customer
	 */
	public function setCustomer(Customer $customer)
	{
		$this->customer = $customer;
	}

	/**
	 * @return int
	 */
	public function getCustomerId(): int
	{
		return $this->customer->getId();
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name)
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new('nome deve possuir de %d a %d caracteres', self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getAssignmentLevel(): int
	{
		return $this->assignmentLevel;
	}

	/**
	 * @param int $assignmentLevel
	 */
	public function setAssignmentLevel(int $assignmentLevel)
	{
		if (!IntegerUtil::inInterval($assignmentLevel, self::MIN_ASSIGNMENT_LEVEL, self::MAX_ASSIGNMENT_LEVEL))
			throw EntityParseException::new('nível de assinatura deve ser de %d a %d', self::MIN_ASSIGNMENT_LEVEL, self::MAX_ASSIGNMENT_LEVEL);

		$this->assignmentLevel = $assignmentLevel;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */
	public function getAttributeTypes()
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'customer' => Customer::class,
			'name' => ObjectUtil::TYPE_STRING,
			'assignmentLevel' => ObjectUtil::TYPE_INTEGER,
		];
	}
}

