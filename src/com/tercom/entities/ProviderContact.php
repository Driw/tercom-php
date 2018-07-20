<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use tercom\Entities\EntityParseException;
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
			throw new EntityParseException('código de identificação inválido (id: %d)', $id);

		$this->id = $id;
	}

	/**
	 * @return string aquisição do name completo do contato.
	 */

	public function getName():?string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome completo do contato.
	 */

	public function setName($name)
	{
		if (!StringUtil::hasBetweenLength($name, MIN_CONTACT_NAME_LEN, MAX_CONTACT_NAME_LEN))
			throw new EntityParseException(sprintf('nome do contato deve ter de %d a %d caracteres (nome: %s)', MIN_CONTACT_NAME_LEN, MAX_NAME_LEN, $name));

		$this->name = $name;
	}

	/**
	 * @return string aquisição do position do contato em sua empresa.
	 */

	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @param string $position cargo do contato em sua empresa.
	 */

	public function setPosition($post)
	{
		if (!StringUtil::hasBetweenLength($post, MIN_CONTACT_POST_LEN, MAX_CONTACT_POST_LEN))
			throw new EntityParseException(sprintf('cargo deve ter de %d a %d caracteres (cargo: %s)', MIN_CONTACT_POST_LEN, MAX_CONTACT_POST_LEN, $post));

		$this->position = $post;
	}

	/**
	 * @return string aquisição do endereço de e-mail para contato.
	 */

	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string $email endereço de e-mail para contato.
	 */

	public function setEmail($email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			throw new EntityParseException(sprintf('endereço de e-mail inválido (email: %s)', $email));

		if (!StringUtil::hasMaxLength($email, MAX_CONTACT_EMAIL_LEN))
			throw new EntityParseException(sprintf('endereço de e-mail deve até %d caracteres (email: %s)', MAX_CONTACT_EMAIL_LEN, $email));

		$this->email = $email;
	}

	/**
	 * @return Phone aquisição do commercial comercial para contato.
	 */

	public function getCommercial():Phone
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

	public function getOtherPhone():Phone
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

	public function getPhones():Phones
	{
		$phones = new Phones();
		$phones->add($this->getCommercial());
		$phones->add($this->getOtherPhone());

		return $phones;
	}
}

?>