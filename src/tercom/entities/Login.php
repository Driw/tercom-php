<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;

/**
 * @author Andrew
 */
class Login extends AdvancedObject
{
	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var string
	 */
	private $token;
	/**
	 * @var bool
	 */
	private $logout;
	/**
	 * @var string
	 */
	private $ipAddress;
	/**
	 * @var string
	 */
	private $browser;
	/**
	 * @var \DateTime
	 */
	private $register;
	/**
	 * @var \DateTime
	 */
	private $expiration;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->token = '';
		$this->ipAddress = '';
		$this->browser = '';
		$this->register = new \DateTime();
		$this->expiration = new \DateTime();
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
	public function getToken(): string
	{
		return $this->token;
	}

	/**
	 * @param string $token
	 */
	public function setToken(string $token)
	{
		$this->token = $token;
	}

	/**
	 * @return bool
	 */
	public function isLogout(): bool
	{
		return $this->logout;
	}

	/**
	 * @param bool $logout
	 */
	public function setLogout(bool $logout)
	{
		$this->logout = $logout;
	}

	/**
	 * @return string
	 */
	public function getIpAddress(): string
	{
		return $this->ipAddress;
	}

	/**
	 * @param string $ipAddress
	 */
	public function setIpAddress(string $ipAddress)
	{
		$this->ipAddress = $ipAddress;
	}

	/**
	 * @return string
	 */
	public function getBrowser(): string
	{
		return $this->browser;
	}

	/**
	 * @param string $browser
	 */
	public function setBrowser(string $browser)
	{
		$this->browser = $browser;
	}

	/**
	 * @return \DateTime
	 */
	public function getExpiration(): \DateTime
	{
		return $this->expiration;
	}

	/**
	 * @param \DateTime $datetimeExpiration
	 */
	public function setExpiration(\DateTime $expiration)
	{
		$this->expiration = $expiration;
	}

	/**
	 * @return \DateTime
	 */
	public function getRegister(): \DateTime
	{
		return $this->register;
	}

	/**
	 * @param \DateTime $datetime
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
			'id' => ObjectUtil::TYPE_INTEGER,
			'token' => ObjectUtil::TYPE_STRING,
			'logout' => ObjectUtil::TYPE_BOOLEAN,
			'ipAddress' => ObjectUtil::TYPE_STRING,
			'browser' => ObjectUtil::TYPE_STRING,
			'expiration' => ObjectUtil::TYPE_DATE,
			'register' => ObjectUtil::TYPE_DATE,
		];
	}
}

