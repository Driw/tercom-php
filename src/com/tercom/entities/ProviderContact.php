<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;

/**
 * <h1>Contato do Fornecedor</h1>
 *
 * <p>Possui informações de uma determinada pessoa para se entrar em contato com o fornecedor.
 * As informações consistem no name da pessoa, post ocupado na empresa do fornecedor,
 * endereço de e-mail, um cellphone residêncial/comercial e um cellphone otherphone.</p>
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
	 * @var string post do contato em sua empresa.
	 */
	private $post;
	/**
	 * @var string endereço de e-mail para contato.
	 */
	private $email;
	/**
	 * @var Phone cellphone residêncial/comercial para contato.
	 */
	private $cellphone;
	/**
	 * @var Phone cellphone otherphone para contato.
	 */
	private $otherphone;

	/**
	 * @return number aquisição do código de identificação do contato.
	 */

	public function getID()
	{
		return $this->id;
	}

	/**
	 * @param number $id código de identificação do contato.
	 */

	public function setID($id)
	{
		$this->id = $id;
	}

	/**
	 * @return string aquisição do name completo do contato.
	 */

	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name nome completo do contato.
	 */

	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string aquisição do post do contato em sua empresa.
	 */

	public function getPost()
	{
		return $this->post;
	}

	/**
	 * @param string $post cargo do contato em sua empresa.
	 */

	public function setPost($post)
	{
		$this->post = $post;
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
		$this->email = $email;
	}

	/**
	 * @return Phone aquisição do cellphone residêncial/comercial para contato.
	 */

	public function getCellPhone()
	{
		return $this->cellphone;
	}

	/**
	 * @param Phone $cellphone telefone residêncial/comercial para contato.
	 */

	public function setCellPhone(Phone $cellphone)
	{
		$this->cellphone = $cellphone;
	}

	/**
	 * @return Phone aquisição do cellphone otherphone para contato.
	 */

	public function getOtherPhone()
	{
		return $this->otherphone;
	}

	/**
	 * @param Phone $otherphone cellphone otherphone para contato.
	 */

	public function setOtherPhone(Phone $otherphone)
	{
		$this->otherphone = $otherphone;
	}
}

?>