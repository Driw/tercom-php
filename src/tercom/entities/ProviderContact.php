<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;
use tercom\entities\lists\Phones;

/**
 * <h1>Contato do Fornecedor</h1>
 *
 * <p>Possui informações de uma determinada pessoa para se entrar em contato com o fornecedor.
 * As informações consistem no name da pessoa, position ocupado na empresa do fornecedor,
 * endereço de e-mail, um commercial residêncial/comercial e um commercial otherphone.</p>
 *
 * @see AdvancedObject
 * @author Andrew
 */

class ProviderContact extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres necessário no nome.
	 */
	public const MIN_NAME_LEN = MIN_NAME_LEN;
	/**
	 * @var int quantidade máxima de caracteres necessário no nome.
	 */
	public const MAX_NAME_LEN = MAX_NAME_LEN;
	/**
	 * @var int quantidade mínima de caracteres necessário no cargo.
	 */
	public const MIN_POSITION_LEN = 3;
	/**
	 * @var int quantidade máxima de caracteres necessário no cargo.
	 */
	public const MAX_POSITION_LEN = 32;
	/**
	 * @var int quantidade mínima de caracteres necessário no endereço de e-mail.
	 */
	public const MAX_EMAIL_LEN = MAX_EMAIL_LEN;

	/**
	 * @var number código de identificação do contato.
	 */
	private $id;
	/**
	 * @var string name completo do contato.
	 */
	private $name;
	/**
	 * @var string position do contato em sua empresa.
	 */
	private $position;
	/**
	 * @var string endereço de e-mail para contato.
	 */
	private $email;
	/**
	 * @var Phone commercial comercial para contato.
	 */
	private $commercial;
	/**
	 * @var Phone commercial otherphone para contato.
	 */
	private $otherphone;

	/**
	 * Cria uma nova instância de um contato de fornecedor.
	 */

	public function __construct()
	{
		$this->id = 0;
		$this->commercial = new Phone();
		$this->otherphone = new Phone();
	}

	/**
	 * @return number aquisição do código de identificação do contato.
	 */

	public function getID():int
	{
		return $this->id;
	}

	/**
	 * @param number $id código de identificação do contato.
	 */

	public function setID(int $id)
	{
		if ($id < 1)
			throw EntityParseException::new("código de identificação inválido (id: $id)");

		$this->id = $id;
	}

	/**
	 * @return string aquisição do name completo do contato.
	 */

	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome completo do contato.
	 */

	public function setName($name)
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome do contato deve ter de %d a %d caracteres (nome: $name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return string aquisição do position do contato em sua empresa.
	 */

	public function getPosition(): ?string
	{
		return $this->position;
	}

	/**
	 * @param string $position cargo do contato em sua empresa.
	 */

	public function setPosition(?string $post)
	{
		if (!StringUtil::hasBetweenLength($post, self::MIN_POSITION_LEN, self::MAX_POSITION_LEN))
			throw EntityParseException::new("cargo deve ter de %d a %d caracteres (cargo: $post)", self::MIN_POSITION_LEN, self::MAX_POSITION_LEN);

		$this->position = $post;
	}

	/**
	 * @return string aquisição do endereço de e-mail para contato.
	 */

	public function getEmail(): ?string
	{
		return $this->email;
	}

	/**
	 * @param string $email endereço de e-mail para contato.
	 */

	public function setEmail(?string $email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			throw new EntityParseException("endereço de e-mail inválido (email: $email)");

		if (!StringUtil::hasMaxLength($email, self::MAX_EMAIL_LEN))
			throw EntityParseException::new("endereço de e-mail deve até %d caracteres (email: $email)", self::MAX_EMAIL_LEN);

		$this->email = $email;
	}

	/**
	 * @return Phone aquisição do commercial comercial para contato.
	 */

	public function getCommercial(): Phone
	{
		return $this->commercial;
	}

	/**
	 * @param Phone $commercial telefone comercial para contato.
	 */

	public function setCommercial(Phone $commercial)
	{
		$this->commercial = $commercial;
	}

	/**
	 * @return Phone aquisição do commercial otherphone para contato.
	 */

	public function getOtherPhone(): Phone
	{
		return $this->otherphone;
	}

	/**
	 * @param Phone $otherphone commercial otherphone para contato.
	 */

	public function setOtherPhone(Phone $otherphone)
	{
		$this->otherphone = $otherphone;
	}

	/**
	 * @return Phones aquisição de um vetor com o telefone celular e o telefone secundário.
	 */

	public function getPhones(): Phones
	{
		$phones = new Phones();
		$phones->add($this->getCommercial());
		$phones->add($this->getOtherPhone());

		return $phones;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */

	public function getAttributeTypes(): array
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'name' => ObjectUtil::TYPE_STRING,
			'position' => ObjectUtil::TYPE_STRING,
			'email' => ObjectUtil::TYPE_STRING,
			'commercial' => Phone::class,
			'otherphone' => Phone::class,
		];
	}
}

?>