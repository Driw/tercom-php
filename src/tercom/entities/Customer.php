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
 * Cliente
 *
 * Um cliente possui diversos perfis de funcionário e diversos funcionários que podem acessar o sistema.
 * As solicitações de cotação são por cliente mas feitas por funcionários conforme os dados cadastrados.
 *
 * @see AdvancedObject
 * @see Addresses
 * @see Phones
 *
 * @author Andrew
 */
class Customer extends AdvancedObject
{
	/**
	 * @var int quantidade máxima de caracteres na inscrição estadual.
	 */
	public const MAX_STATE_REGISTRY_LEN = 15;
	/**
	 * @var int quantidade máxima de caracteres no endereço de e-mail.
	 */
	public const MAX_EMAIL_LEN = MAX_EMAIL_LEN;
	/**
	 * @var int quantidade mínima de caracteres na razão social.
	 */
	public const MIN_COMPANY_NAME_LEN = 6;
	/**
	 * @var int quantidade máxima de caracteres na razão social.
	 */
	public const MAX_COMPANY_NAME_LEN = 72;
	/**
	 * @var int quantidade mínima de caracteres no nome fantasia.
	 */
	public const MIN_FANTASY_NAME_LEN = 3;
	/**
	 * @var int quantidade máxima de caracteres no nome fantasia.
	 */
	public const MAX_FANTASY_NAME_LEN = 48;

	/**
	 * @var int código de identificação único do cliente.
	 */
	private $id;
	/**
	 * @var string número da inscrição estadual.
	 */
	private $stateRegistry;
	/**
	 * @var string número de cadastro nacional de pessoa jurídica.
	 */
	private $cnpj;
	/**
	 * @var string razão social da empresa do cliente.
	 */
	private $companyName;
	/**
	 * @var string nome fantasia da empresa do cliente.
	 */
	private $fantasyName;
	/**
	 * @var string endereço de e-mail para notificações e contato.
	 */
	private $email;
	/**
	 * @var Phones lista de telefones disponíveis para contato.
	 */
	private $phones;
	/**
	 * @var Addresses lista de endereços disponíveis para entregas.
	 */
	private $addresses;
	/**
	 * @var bool determina se o cliente está inativo.
	 */
	private $inactive;
	/**
	 * @var DateTime horário de registor do cliente.
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
		$this->inactive = false;
		$this->register = new \DateTime();
	}

	/**
	 * @return int aquisição do código de identificação único do cliente.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do cliente.
	 */
	public function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return string aquisição do número da inscrição estadual.
	 */
	public function getStateRegistry(): string
	{
		return $this->stateRegistry;
	}

	/**
	 * @param string $stateRegistry número da inscrição estadual.
	 */
	public function setStateRegistry(string $stateRegistry)
	{
		if (!StringUtil::hasMaxLength($stateRegistry, self::MAX_STATE_REGISTRY_LEN))
			throw EntityParseException::new('inscrição estadual deve possuir até %d dígitos', self::MAX_STATE_REGISTRY_LEN);

		$this->stateRegistry = $stateRegistry;
	}

	/**
	 * @return string aquisição do número de cadastro nacional de pessoa jurídica.
	 */
	public function getCnpj(): string
	{
		return $this->cnpj;
	}

	/**
	 * @param string $cnpj número de cadastro nacional de pessoa jurídica.
	 */
	public function setCnpj(string $cnpj)
	{
		if (!Functions::validateCNPJ($cnpj))
			throw EntityParseException::new('CNPJ inválido');

		$this->cnpj = $cnpj;
	}

	/**
	 * @return string aquisição da razão social da empresa do cliente.
	 */
	public function getCompanyName(): string
	{
		return $this->companyName;
	}

	/**
	 * @param string $companyName razão social da empresa do cliente.
	 */
	public function setCompanyName(string $companyName)
	{
		if (!StringUtil::hasBetweenLength($companyName, self::MIN_COMPANY_NAME_LEN, self::MAX_COMPANY_NAME_LEN))
			throw EntityParseException::new('razão social deve possuir de %d a %d caracteres', self::MIN_COMPANY_NAME_LEN, self::MAX_COMPANY_NAME_LEN);

		$this->companyName = $companyName;
	}

	/**
	 * @return string aquisição do nome fantasia da empresa do cliente.
	 */
	public function getFantasyName(): string
	{
		return $this->fantasyName;
	}

	/**
	 * @param string $fantasyName nome fantasia da empresa do cliente.
	 */
	public function setFantasyName(string $fantasyName)
	{
		if (!StringUtil::hasBetweenLength($fantasyName, self::MIN_FANTASY_NAME_LEN, self::MAX_FANTASY_NAME_LEN))
			throw EntityParseException::new('nome fantasia deve possuir de %d a %d caracteres', self::MIN_FANTASY_NAME_LEN, self::MAX_FANTASY_NAME_LEN);

		$this->fantasyName = $fantasyName;
	}

	/**
	 * @return string aquisição do endereço de e-mail para notificações e contato.
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email endereço de e-mail para notificações e contato.
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
	 * @return Phones aquisição da lista de telefones disponíveis para contato.
	 */
	public function getPhones(): Phones
	{
		return $this->phones === null ? ($this->phones = new Phones) : $this->phones;
	}

	/**
	 * @param Phones $phones lista de telefones disponíveis para contato.
	 */
	public function setPhones(Phones $phones)
	{
		$this->phones = $phones;
	}

	/**
	 * @return Addresses aquisição da lista de endereços disponíveis para entregas.
	 */
	public function getAddresses(): Addresses
	{
		return $this->addresses === null ? ($this->addresses = new Address()) : $this->addresses;
	}

	/**
	 * @param Addresses $addresses lista de endereços disponíveis para entregas.
	 */
	public function setAddresses(Addresses $addresses)
	{
		$this->addresses = $addresses;
	}

	/**
	 * @return bool aquisição de se o cliente está ativo ou inativo.
	 */
	public function isInactive(): bool
	{
		return $this->inactive;
	}

	/**
	 * @param bool $inactive ativar ou inativar um cliente.
	 */
	public function setInactive(bool $inactive)
	{
		$this->inactive = $inactive;
	}

	/**
	 * @return DateTime aquisição do horário de registor do cliente.
	 */
	public function getRegister(): DateTime
	{
		return $this->register;
	}

	/**
	 * @param DateTime $register horário de registor do cliente.
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
			'email' => ObjectUtil::TYPE_STRING,
			'phones' => Phones::class,
			'addresses' => Addresses::class,
			'inactive' => ObjectUtil::TYPE_BOOLEAN,
			'register' => ObjectUtil::TYPE_DATE,
		];
	}
}

