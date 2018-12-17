<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;

/**
 * Acesso
 *
 * Um acesso possui informações para identificar quando e quem fez um acesso no sistema.
 * Através do seu código de identificação única e token único é possível melhorar o acesso.
 * A melhoria do acesso consiste em não trafegar a senha do usuário para validação do acesso.
 * Com um token também é possível validar acessos por API já que não é possível criar sessão HTTP.
 *
 * @see AdvancedObject
 *
 * @author Andrew
 */
abstract class Login extends AdvancedObject
{
	/**
	 * @var int código de identificação único do aceso.
	 */
	private $id;
	/**
	 * @var string token para validar e manter acesso.
	 */
	private $token;
	/**
	 * @var bool acesso encerrado.
	 */
	private $logout;
	/**
	 * @var string endereço de IP do qual iniciou o acesso.
	 */
	private $ipAddress;
	/**
	 * @var string navegador utilizado para fazer o acesso.
	 */
	private $browser;
	/**
	 * @var \DateTime horário limite do acesso e/ou renovar.
	 */
	private $expiration;
	/**
	 * @var \DateTime horário em qua foi feito o acesso.
	 */
	private $register;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->token = '';
		$this->ipAddress = '';
		$this->browser = '';
		$this->expiration = new \DateTime();
		$this->register = new \DateTime();
	}

	/**
	 * @return int aquisição do token para validar e manter acesso.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id token para validar e manter acesso.
	 */
	public function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return string aquisição do token para validar e manter acesso.
	 */
	public function getToken(): string
	{
		return $this->token;
	}

	/**
	 * @param string $token token para validar e manter acesso.
	 */
	public function setToken(string $token)
	{
		$this->token = $token;
	}

	/**
	 * @return bool acesso encerrado.
	 */
	public function isLogout(): bool
	{
		return $this->logout;
	}

	/**
	 * @param bool $logout acesso encerrado.
	 */
	public function setLogout(bool $logout)
	{
		$this->logout = $logout;
	}

	/**
	 * @return string aquisição do endereço de IP do qual iniciou o acesso.
	 */
	public function getIpAddress(): string
	{
		return $this->ipAddress;
	}

	/**
	 * @param string $ipAddress endereço de IP do qual iniciou o acesso.
	 */
	public function setIpAddress(string $ipAddress)
	{
		$this->ipAddress = $ipAddress;
	}

	/**
	 * @return string aquisição do navegador utilizado para fazer o acesso.
	 */
	public function getBrowser(): string
	{
		return $this->browser;
	}

	/**
	 * @param string $browser navegador utilizado para fazer o acesso.
	 */
	public function setBrowser(string $browser)
	{
		$this->browser = $browser;
	}

	/**
	 * @return \DateTime aquisição do horário limite do acesso e/ou renovar.
	 */
	public function getExpiration(): \DateTime
	{
		return $this->expiration;
	}

	/**
	 * @param \DateTime $datetimeExpiration horário limite do acesso e/ou renovar.
	 */
	public function setExpiration(\DateTime $expiration)
	{
		$this->expiration = $expiration;
	}

	/**
	 * @return \DateTime aquisição do horário em qua foi feito o acesso.
	 */
	public function getRegister(): \DateTime
	{
		return $this->register;
	}

	/**
	 * @param \DateTime $datetime horário em qua foi feito o acesso.
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

