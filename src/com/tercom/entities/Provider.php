<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;

/**
 * <h1>Provider</h1>
 *
 * @author Andrew
 */

class Provider extends AdvancedObject
{
	/**
	 * @var number código de identificação do fornecedor.
	 */
	private $id;
	/**
	 * @var string número do cadastro nacional de pessoa jurídica.
	 */
	private $cnpj;
	/**
	 * @var string nome de registro da empresa do fornecedor.
	 */
	private $companyName;
	/**
	 * @var string nome de fachada da empresa do fornecedor.
	 */
	private $fantasyName;
	/**
	 * @var string nome de uma pessoa representante do fornecedor.
	 */
	private $spokesman;
	/**
	 * @var string link para acesso à página do fornecedor.
	 */
	private $site;
	/**
	 * @var Phone número de telefone commercial.
	 */
	private $commercial;
	/**
	 * @var Phone número de telefone otherphone.
	 */
	private $otherphone;
	/**
	 * @var bool o fornecedor está ativo ou não no sistema.
	 */
	private $inactive;
	/**
	 * @var ProviderContacts lista de contacs do fornecedor.
	 */
	private $contacs;

	/**
	 * Cria uma nova instância de um fornecedor.
	 * Inicializa as instâncias do telefone commercial e otherphone.
	 */

	public function __construct()
	{
		$this->id = 0;
		$this->otherphone = new Phone();
		$this->commercial = new Phone();
		$this->inactive = false;
	}

	/**
	 * @return number aquisição do código de identificação do fornecedor.
	 */

	public function getID():int
	{
		return $this->id;
	}

	/**
	 * @param number $id código de identificação do fornecedor.
	 */

	public function setID(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */

	public function getCNPJ():?string
	{
		return $this->cnpj;
	}

	/**
	 * @param string $cnpj
	 */

	public function setCNPJ(string $cnpj)
	{
		$this->cnpj = $cnpj;
	}

	/**
	 * @return string
	 */

	public function getCompanyName():?string
	{
		return $this->companyName;
	}

	/**
	 * @param string $companyName
	 */

	public function setCompanyName(string $companyName)
	{
		$this->companyName = $companyName;
	}

	/**
	 * @return string
	 */

	public function getFantasyName():?string
	{
		return $this->fantasyName;
	}

	/**
	 * @param string $fantasyName
	 */

	public function setFantasyName(string $fantasyName)
	{
		$this->fantasyName = $fantasyName;
	}

	/**
	 * @return string
	 */

	public function getSpokesman():?string
	{
		return $this->spokesman;
	}

	/**
	 * @param string $spokesman
	 */

	public function setSpokesman(string $spokesman)
	{
		$this->spokesman = $spokesman;
	}

	/**
	 * @return string
	 */

	public function getSite():?string
	{
		return $this->site;
	}

	/**
	 * @param string $site
	 */

	public function setSite(string $site)
	{
		$this->site = $site;
	}

	/**
	 * @return Phone
	 */

	public function getCommercial():?Phone
	{
		return $this->commercial;
	}

	/**
	 * @param Phone $commercial
	 */

	public function setCommercial(Phone $commercial)
	{
		$this->commercial = $commercial;
	}

	/**
	 * @return Phone
	 */

	public function getOtherPhone():?Phone
	{
		return $this->otherphone;
	}

	/**
	 * @param Phone $otherPhone
	 */

	public function setOtherPhone(Phone $otherPhone)
	{
		$this->otherphone = $otherphone;
	}

	/**
	 * @return boolean
	 */

	public function isInactive():bool
	{
		return $this->inactive;
	}

	/**
	 * @param boolean $inactive
	 */

	public function setInactive(bool $inactive)
	{
		$this->inactive = $inactive;
	}

	/**
	 * @return ProviderContacts aquisição da lista de contacs do fornecedor.
	 */

	public function getContacs():ProviderContacts
	{
		return $this->contacs;
	}
}

?>