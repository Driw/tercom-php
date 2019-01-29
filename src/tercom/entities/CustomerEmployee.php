<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;
use tercom\entities\lists\Phones;

/**
 * Funcionário de Cliente
 *
 * Um cliente pode ter diversos funcionários e o acesso no sistema é feito por um funcionário.
 * Funcionários estão vinculados a um perfil de cliente e este perfil possui as permissões.
 * As permissões vão definir o que um funcionário pode ou não fazer no sistema.
 *
 * Cada funcionário possui um endereço de e-mail e senha usadas para acesso, nome do funcionário,
 * dois números de telefone (um telefone qualquer e um número de celular), habilitado ou não
 * (quando desabilitado não permite o acesso no sistema).
 *
 * @see AdvancedObject
 *
 * @author Andrew
 */
class CustomerEmployee extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres no nome do funcionário.
	 */
	public const MIN_NAME_LEN = MIN_NAME_LEN;
	/**
	 * @var int quantidade máxima de caracteres no nome do funcionário.
	 */
	public const MAX_NAME_LEN = MAX_NAME_LEN;
	/**
	 * @var int quantiadade máxima de caracteres no endereço de e-mail.
	 */
	public const MAX_EMAIL_LEN = MAX_EMAIL_LEN;
	/**
	 * @var int quantidade mínima de caracteres na senha de acesso.
	 */
	public const MIN_PASSWORD_LEN = MIN_PASSWORD_LEN;
	/**
	 * @var int quantidade máxima de caracteres na senha de acesso.
	 */
	public const MAX_PASSWORD_LEN = MAX_PASSWORD_LEN;


	/**
	 * @var int código de identificação único do funcionário de cliente.
	 */
	private $id;
	/**
	 * @var CustomerProfile perfil do clinte para uso de permissões no sistema.
	 */
	private $customerProfile;
	/**
	 * @var string nome completo do funcionáiro.
	 */
	private $name;
	/**
	 * @var string endereço de e-mail para acesso e notificações.
	 */
	private $email;
	/**
	 * @var string senha de acesso no sistema criptografada.
	 */
	private $password;
	/**
	 * @var Phone dados do telefone principal para contato.
	 */
	private $phone;
	/**
	 * @var Phone dados do telefone celular para contato.
	 */
	private $cellphone;
	/**
	 * @var bool
	 */
	private $enabled;
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
		$this->name = '';
		$this->email = '';
		$this->password = '';
		$this->enable = false;
		$this->register = new \DateTime();
	}

	/**
	 * @return int aquisição do código de identificação único do funcionário de cliente.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do funcionário de cliente.
	 */
	public function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return CustomerProfile aquisição do perfil do clinte para uso de permissões no sistema.
	 */
	public function getCustomerProfile(): CustomerProfile
	{
		return $this->customerProfile === null ? ($this->customerProfile = new CustomerProfile()) : $this->customerProfile;
	}

	/**
	 * @param CustomerProfile $customerProfile perfil do clinte para uso de permissões no sistema.
	 */
	public function setCustomerProfile(CustomerProfile $customerProfile)
	{
		$this->customerProfile = $customerProfile;
	}

	/**
	 *
	 * @return int aquisição do código de identificação do perfil de cliente ou zero se não definido.
	 */
	public function getCustomerProfileId(): int
	{
		return $this->customerProfile->getId();
	}

	/**
	 * @return string aquisição do nome completo do funcionáiro.
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome completo do funcionáiro.
	 */
	public function setName(string $name)
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres ($name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return string aquisição do endereço de e-mail para acesso e notificações.
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email endereço de e-mail para acesso e notificações.
	 */
	public function setEmail(string $email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			throw new EntityParseException("endereço de e-mail inválido (email: $email)");

		if (!StringUtil::hasMaxLength($email, self::MAX_EMAIL_LEN))
			throw EntityParseException::new("endereço de e-mail deve possuir até %d caracteres", self::MAX_EMAIL_LEN);

		$this->email = $email;
	}

	/**
	 * @return string aquisição da senha de acesso no sistema criptografada.
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password senha de acesso no sistema.
	 * @param bool $hash true se já exiver criptografada ou false caso contrário.
	 */
	public function setPassword(string $password, bool $hash = true)
	{
		if ($hash)
			$this->password = $password;
		else
		{
			if (!StringUtil::hasBetweenLength($password, self::MIN_PASSWORD_LEN, self::MAX_PASSWORD_LEN))
				throw EntityParseException::new('sua senha deve possuir de %d a %d caracteres', self::MIN_PASSWORD_LEN, self::MAX_PASSWORD_LEN);

			if (preg_match(PATTERN_PASSWORD, $password) !== 1)
				throw new EntityParseException('sua senha deve possuir ao menos um caracter minúsculo, maiúsculo e um número');

			$this->password = password_hash($password, PASSWORD_BCRYPT);
		}
	}

	/**
	 * @return Phone aquisição dos dados do telefone principal para contato.
	 */
	public function getPhone(): Phone
	{
		return $this->phone === null ? ($this->phone = new Phone()) : $this->phone;
	}

	/**
	 * @param Phone $phone dados do telefone principal para contato.
	 */
	public function setPhone(?Phone $phone)
	{
		$this->phone = $phone;
	}

	/**
	 * @return int aquisição do código de identificação do telefone principal ou zero se não definido.
	 */
	public function getPhoneId(): int
	{
		return $this->phone === null ? 0 : $this->phone->getId();
	}

	/**
	 * @return Phone aquisição dos dados do telefone celular para contato.
	 */
	public function getCellphone(): Phone
	{
		return $this->cellphone === null ? ($this->cellphone = new Phone()) : $this->cellphone;
	}

	/**
	 * @param Phone $cellphone dados do telefone celular para contato.
	 */
	public function setCellphone(?Phone $cellphone)
	{
		$this->cellphone = $cellphone;
	}

	/**
	 * @return int aquisição do código de identificação do telefone celular ou zero se não definido.
	 */
	public function getCellphoneId(): int
	{
		return $this->cellphone === null ? 0 : $this->cellphone->getId();
	}

	/**
	 * @return Phones aquisição da lista contendo os dois telefones do funcionário.
	 */
	public function getPhones(): Phones
	{
		$phones = new Phones();
		$phones->add($this->getPhone());
		$phones->add($this->getCellphone());

		return $phones;
	}

	/**
	 * @return bool funcionário habilitado para uso do sistema.
	 */
	public function isEnabled(): bool
	{
		return $this->enabled;
	}

	/**
	 * @param bool $enable funcionário habilitado para uso do sistema.
	 */
	public function setEnabled(bool $enabled)
	{
		$this->enabled = $enabled;
	}

	/**
	 * @return \DateTime aquisição do horário de registro do funcionário no sistema.
	 */
	public function getRegister(): \DateTime
	{
		return $this->register;
	}

	/**
	 * @param \DateTime $register horário de registro do funcionário no sistema.
	 */
	public function setRegister(\DateTime $register)
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
			'customerProfile' => CustomerProfile::class,
			'name' => ObjectUtil::TYPE_STRING,
			'email' => ObjectUtil::TYPE_STRING,
			'password' => ObjectUtil::TYPE_STRING,
			'phone' => Phone::class,
			'cellphone' => Phone::class,
			'enabled' => ObjectUtil::TYPE_BOOLEAN,
			'register' => ObjectUtil::TYPE_DATE,
		];
	}
}

