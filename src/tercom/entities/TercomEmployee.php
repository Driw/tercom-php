<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;
use tercom\Functions;

/**
 * Funcionário TERCOM
 *
 * Os funcionários TERCOM são vinculados a um perfil TERCOM para especificar suas permissões no sistema.
 * Além disso possui informações para identificação do funcionário como pessoa física e de credenciais de acesso.
 * As credenciais de acesso consiste em endereço de e-mail, palavra chave e habilitado/desabilitado.
 *
 * @see AdvancedObject
 *
 * @author Andrew
 */
class TercomEmployee extends AdvancedObject
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
	 * @var int quantidade máxima de caracteres no endereço de e-mail.
	 */
	public const MAX_EMAIL_LEN = MAX_EMAIL_LEN;
	/**
	 * @var int quantidade mínima da caracteres na senha de acesso.
	 */
	public const MIN_PASSWORD_LEN = MIN_PASSWORD_LEN;
	/**
	 * @var int quantidade máxima da caracteres na senha de acesso.
	 */
	public const MAX_PASSWORD_LEN = MAX_PASSWORD_LEN;

	/**
	 * @var int código de identificação único do funcionário TERCOM.
	 */
	private $id;
	/**
	 * @var TercomProfile perfil do funcionário de acesso no sistema.
	 */
	private $tercomProfile;
	/**
	 * @var string número de cadastro de pessoa física.
	 */
	private $cpf;
	/**
	 * @var string nome do funcionário.
	 */
	private $name;
	/**
	 * @var string endereço de e-mail para acesso e notificação.
	 */
	private $email;
	/**
	 * @var string senha criptografada para acesso.
	 */
	private $password;
	/**
	 * @var Phone telefone residêncial.
	 */
	private $phone;
	/**
	 * @var Phone telefone celular.
	 */
	private $cellphone;
	/**
	 * @var bool funcionário habilitado para uso do sistema.
	 */
	private $enabled;
	/**
	 * @var \DateTime horário de registro do funcionário.
	 */
	private $register;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->cpf = '';
		$this->name = '';
		$this->email = '';
		$this->password = '';
		$this->register = new \DateTime();
		$this->enabled = false;
	}

	/**
	 * @return int aquisição do código de identificação único do funcionário TERCOM.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do funcionário TERCOM.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return TercomProfile aquisição do perfil do funcionário de acesso no sistema.
	 */
	public function getTercomProfile(): TercomProfile
	{
		return $this->tercomProfile === null ? ($this->tercomProfile = new TercomProfile()) : $this->tercomProfile;
	}

	/**
	 * @param TercomProfile $tercomProfile perfil do funcionário de acesso no sistema.
	 */
	public function setTercomProfile(TercomProfile $tercomProfile): void
	{
		$this->tercomProfile = $tercomProfile;
	}

	/**
	 *
	 * @return int aquisição do código de identificação do perfil do funcionário.
	 */
	public function getTercomProfileId(): int
	{
		return $this->tercomProfile === null ? 0 : $this->tercomProfile->getId();
	}

	/**
	 * @return string aquisição do número de cadastro de pessoa física.
	 */
	public function getCpf(): string
	{
		return $this->cpf;
	}

	/**
	 * @param string $cpf número de cadastro de pessoa física.
	 */
	public function setCpf(string $cpf): void
	{
		if (!Functions::validateCPF($cpf))
			throw new EntityParseException("CPF inválido (cpf: $cpf)");

		$this->cpf = $cpf;
	}

	/**
	 * @return string aquisição do nome do funcionário.
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome do funcionário.
	 */
	public function setName(string $name): void
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres ($name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return string endereço de e-mail para acesso e notificação.
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email endereço de e-mail para acesso e notificação.
	 */
	public function setEmail(string $email): void
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			throw new EntityParseException("endereço de e-mail inválido (email: $email)");

		if (!StringUtil::hasMaxLength($email, self::MAX_EMAIL_LEN))
			throw EntityParseException::new("endereço de e-mail deve possuir até %d caracteres", self::MAX_EMAIL_LEN);

		$this->email = $email;
	}

	/**
	 * @return string aquisição da senha criptografada para acesso.
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password senha de acesso.
	 * @param bool $hash true se já criptografado ou false caso contrário.
	 */
	public function setPassword(string $password, bool $hash = true): void
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
	 * @return Phone aquisição do telefone residêncial.
	 */
	public function getPhone(): Phone
	{
		return $this->phone === null ? ($this->phone = new Phone()) : $this->phone;
	}

	/**
	 * @param Phone $phone telefone residêncial.
	 */
	public function setPhone(Phone $phone): void
	{
		$this->phone = $phone;
	}

	/**
	 * @return Phone aquisição do telefone celular.
	 */
	public function getCellphone(): Phone
	{
		return $this->cellphone === null ? ($this->cellphone = new Phone()) : $this->cellphone;
	}

	/**
	 * @param Phone $cellphone telefone celular.
	 */
	public function setCellphone(Phone $cellphone): void
	{
		$this->cellphone = $cellphone;
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
	public function setEnabled(bool $enabled): void
	{
		$this->enabled = $enabled;
	}

	/**
	 * @return \DateTime horário de registro do funcionário.
	 */
	public function getRegister(): \DateTime
	{
		return $this->register;
	}

	/**
	 * @param \DateTime $register horário de registro do funcionário.
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
			'tercomProfile' => TercomProfile::class,
			'cpf' => ObjectUtil::TYPE_STRING,
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

