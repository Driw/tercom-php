<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\IntegerUtil;
use dProject\Primitive\StringUtil;

/**
 * Perfil de Cliente
 *
 * Um perfil pode representar um cargo na empresa do cliente possuindo um nome e permissões de acesso.
 * Cada permissão de acesso concede permissão para usufruir de uma ação dentro do sistema.
 * Um perfil de cliente pertence apenas a um cliente, possui um alista de permissões e
 * pode ser vinculado a qualquer funcionário de cliente que esteja registrado no cliente deste perfil.
 *
 * @see Customer
 * @see Entity
 *
 * @author Andrew
 */
class CustomerProfile extends AdvancedObject implements Entity
{
	/**
	 * @var int quantidade mínima de caracteres para o nome do perfil de cliente.
	 */
	public const MIN_NAME_LEN = 3;
	/**
	 * @var int quantidade máxima de caracteres para o nome do perfil de cliente.
	 */
	public const MAX_NAME_LEN = 64;
	/**
	 * @var int nível mínimo de assinatura.
	 */
	public const MIN_ASSIGNMENT_LEVEL = Permission::MIN_ASSIGNMENT_LEVEL;
	/**
	 * @var int nível máximo de assinatura.
	 */
	public const MAX_ASSIGNMENT_LEVEL = Permission::MAX_ASSIGNMENT_LEVEL;

	/**
	 * @var int código de identificação único do perfil de cliente.
	 */
	private $id;
	/**
	 * @var Customer cliente ao qual o perfil pertence.
	 */
	private $customer;
	/**
	 * @var string nome do perfil.
	 */
	private $name;
	/**
	 * @var int nível de assinatura.
	 */
	private $assignmentLevel;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->name = '';
		$this->assignmentLevel = 0;
	}

	/**
	 * @return int aquisição do código de identificação único do perfil de cliente.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do perfil de cliente.
	 */
	public function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return Customer aquisição do cliente ao qual o perfil pertence.
	 */
	public function getCustomer(): Customer
	{
		return $this->customer === null ?($this->customer = new Customer()) : $this->customer;
	}

	/**
	 * @param Customer $customer cliente ao qual o perfil pertence.
	 */
	public function setCustomer(Customer $customer)
	{
		$this->customer = $customer;
	}

	/**
	 * @return int aquisição do código de identificação do perfil de cliente ou zero se não informado.
	 */
	public function getCustomerId(): int
	{
		return $this->customer->getId();
	}

	/**
	 * @return string aquisição do nome do perfil.
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome do perfil.
	 */
	public function setName(string $name)
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new('nome deve possuir de %d a %d caracteres', self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return int aquisição do nível de assinatura.
	 */
	public function getAssignmentLevel(): int
	{
		return $this->assignmentLevel;
	}

	/**
	 * @param int $assignmentLevel nível de assinatura.
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

