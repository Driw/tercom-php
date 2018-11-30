<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;
use tercom\Functions;

/**
 * @author Andrew
 */
class TercomEmployee extends AdvancedObject
{
	/**
	 * @var int
	 */
	public const MIN_NAME_LEN = MIN_NAME_LEN;
	/**
	 * @var int
	 */
	public const MAX_NAME_LEN = MAX_NAME_LEN;
	/**
	 * @var int
	 */
	public const MAX_EMAIL_LEN = MAX_EMAIL_LEN;
	/**
	 * @var int
	 */
	public const MIN_PASSWORD_LEN = MIN_PASSWORD_LEN;
	/**
	 * @var int
	 */
	public const MAX_PASSWORD_LEN = MAX_PASSWORD_LEN;

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var TercomProfile
	 */
	private $tercomProfile;
	/**
	 * @var string
	 */
	private $cpf;
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $email;
	/**
	 * @var string
	 */
	private $password;
	/**
	 * @var Phone
	 */
	private $phone;
	/**
	 * @var Phone
	 */
	private $cellphone;
	/**
	 * @var bool
	 */
	private $enable;
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
		$this->tercomProfile = new TercomProfile();
		$this->cpf = '';
		$this->name = '';
		$this->email = '';
		$this->password = '';
		$this->register = new \DateTime();
		$this->phone = new Phone();
		$this->cellphone = new Phone();
		$this->enable = false;
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
	 * @return TercomProfile
	 */
	public function getTercomProfile(): TercomProfile
	{
		return $this->tercomProfile;
	}

	/**
	 * @param TercomProfile $tercomProfile
	 */
	public function setTercomProfile(TercomProfile $tercomProfile)
	{
		$this->tercomProfile = $tercomProfile;
	}

	/**
	 *
	 * @return int
	 */
	public function getTercomProfileId(): int
	{
		return $this->tercomProfile->getId();
	}

	/**
	 * @return string
	 */
	public function getCpf(): string
	{
		return $this->cpf;
	}

	/**
	 * @param string $cpf
	 */
	public function setCpf(string $cpf)
	{
		if (!Functions::validateCPF($cpf))
			throw new EntityParseException("CPF inválido (cpf: $cpf)");

		$this->cpf = $cpf;
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
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres ($name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
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
		if (!StringUtil::hasMaxLength($email, self::MAX_EMAIL_LEN))
			throw EntityParseException::new("endereço de e-mail deve possuir até %d caracteres", self::MAX_EMAIL_LEN);

		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 * @param bool $hash
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
	 * @return Phone
	 */
	public function getPhone(): Phone
	{
		return $this->phone;
	}

	/**
	 * @param Phone $phone
	 */
	public function setPhone(Phone $phone)
	{
		$this->phone = $phone;
	}

	/**
	 * @return Phone
	 */
	public function getCellphone(): Phone
	{
		return $this->cellphone;
	}

	/**
	 * @param Phone $cellphone
	 */
	public function setCellphone(Phone $cellphone)
	{
		$this->cellphone = $cellphone;
	}

	/**
	 * @return bool
	 */
	public function isEnable(): bool
	{
		return $this->enable;
	}

	/**
	 * @param bool $enable
	 */
	public function setEnable(bool $enable)
	{
		$this->enable = $enable;
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
			'enable' => ObjectUtil::TYPE_BOOLEAN,
			'register' => ObjectUtil::TYPE_DATE,
		];
	}
}

