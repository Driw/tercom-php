<?php

namespace tercom\entities;

use DateTime;
use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use tercom\entities\lists\Addresses;
use tercom\entities\lists\Phones;
use dProject\Primitive\StringUtil;
use tercom\Functions;

/**
 * @see AdvancedObject
 * @see Addresses
 * @see Phones
 * @author Andrew
 */
class Customer extends AdvancedObject
{
	/**
	 * @var int
	 */
	public const MAX_STATE_REGISTRY_LEN = 15;
	/**
	 * @var int
	 */
	public const MAX_EMAIL_LEN = MAX_EMAIL_LEN;
	/**
	 * @var int
	 */
	public const MIN_COMPANY_NAME_LEN = 6;
	/**
	 * @var int
	 */
	public const MAX_COMPANY_NAME_LEN = 72;
	/**
	 * @var int
	 */
	public const MIN_FANTASY_NAME_LEN = 3;
	/**
	 * @var int
	 */
	public const MAX_FANTASY_NAME_LEN = 48;

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var string
	 */
	private $stateRegistry;
	/**
	 * @var string
	 */
	private $cnpj;
	/**
	 * @var string
	 */
	private $companyName;
	/**
	 * @var string
	 */
	private $fantasyName;
	/**
	 * @var string
	 */
	private $email;
	/**
	 * @var Phones
	 */
	private $phones;
	/**
	 * @var Addresses
	 */
	private $addresses;
	/**
	 * @var bool
	 */
	private $inactive;
	/**
	 * @var DateTime
	 */
	private $register;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->stateRegistry = '';
		$this->cnpj = '';
		$this->companyName = '';
		$this->fantasyName = '';
		$this->email = '';
		$this->phones = new Phones();
		$this->addresses = new Addresses();
		$this->inactive = false;
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
	public function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getStateRegistry(): string
	{
		return $this->stateRegistry;
	}

	/**
	 * @param string $stateRegistry
	 */
	public function setStateRegistry(string $stateRegistry)
	{
		if (!StringUtil::hasMaxLength($stateRegistry, self::MAX_STATE_REGISTRY_LEN))
			throw EntityParseException::new('inscrição estadual deve possuir até %d dígitos', self::MAX_STATE_REGISTRY_LEN);

		$this->stateRegistry = $stateRegistry;
	}

	/**
	 * @return string
	 */
	public function getCnpj(): string
	{
		return $this->cnpj;
	}

	/**
	 * @param string $cnpj
	 */
	public function setCnpj(string $cnpj)
	{
		if (!Functions::validateCNPJ($cnpj))
			throw EntityParseException::new('CNPJ inválido');

		$this->cnpj = $cnpj;
	}

	/**
	 * @return string
	 */
	public function getCompanyName(): string
	{
		return $this->companyName;
	}

	/**
	 * @param string $companyName
	 */
	public function setCompanyName(string $companyName)
	{
		if (!StringUtil::hasBetweenLength($companyName, self::MIN_COMPANY_NAME_LEN, self::MAX_COMPANY_NAME_LEN))
			throw EntityParseException::new('razão social deve possuir de %d a %d caracteres', self::MIN_COMPANY_NAME_LEN, self::MAX_COMPANY_NAME_LEN);

		$this->companyName = $companyName;
	}

	/**
	 * @return string
	 */
	public function getFantasyName(): string
	{
		return $this->fantasyName;
	}

	/**
	 * @param string $fantasyName
	 */
	public function setFantasyName(string $fantasyName)
	{
		if (!StringUtil::hasBetweenLength($fantasyName, self::MIN_FANTASY_NAME_LEN, self::MAX_FANTASY_NAME_LEN))
			throw EntityParseException::new('nome fantasia deve possuir de %d a %d caracteres', self::MIN_FANTASY_NAME_LEN, self::MAX_FANTASY_NAME_LEN);

		$this->fantasyName = $fantasyName;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail(string $email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			throw EntityParseException::new('endereço de e-mail inválido');

		if (!StringUtil::hasMaxLength($email, self::MAX_EMAIL_LEN))
			throw EntityParseException::new('endereço de e-mail deve possuir até %d caracteres', self::MAX_EMAIL_LEN);

		$this->email = $email;
	}

	/**
	 * @return Phones
	 */
	public function getPhones(): Phones
	{
		return $this->phones;
	}

	/**
	 * @param Phones $phones
	 */
	public function setPhones(Phones $phones)
	{
		$this->phones = $phones;
	}

	/**
	 * @return Addresses
	 */
	public function getAddresses(): Addresses
	{
		return $this->addresses;
	}

	/**
	 * @param Addresses $addresses
	 */
	public function setAddresses(Addresses $addresses)
	{
		$this->addresses = $addresses;
	}

	/**
	 * @return bool
	 */
	public function isInactive(): bool
	{
		return $this->inactive;
	}

	/**
	 * @param bool $inactive
	 */
	public function setInactive(bool $inactive)
	{
		$this->inactive = $inactive;
	}

	/**
	 * @return DateTime
	 */
	public function getRegister(): DateTime
	{
		return $this->register;
	}

	/**
	 * @param DateTime $register
	 */
	public function setRegister(DateTime $register)
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
			'stateRegistry' => ObjectUtil::TYPE_STRING,
			'cnpj' => ObjectUtil::TYPE_STRING,
			'companyName' => ObjectUtil::TYPE_STRING,
			'fantasyName' => ObjectUtil::TYPE_STRING,
			'phones' => Phones::class,
			'email' => ObjectUtil::TYPE_STRING,
			'register' => ObjectUtil::TYPE_DATE,
			'inactive' => ObjectUtil::TYPE_BOOLEAN,
			'addresses' => Addresses::class,
		];
	}
}

