<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\StringUtil;
use tercom\Functions;
use tercom\entities\lists\Phones;
use tercom\entities\lists\ProviderContacts;

/**
 * <h1>Provider</h1>
 *
 * <p>Fornecedores são empresas disponíveis no sistema que possuem um ou mais produtos e/ou serviços oferecidos.
 * Um fornecedor possui informações básicas da empresa como razão social, nome fantasia, CNPJ, telefone e contatos.
 * Também é permitido desabilitar um fornecedor, assim todos seus preços automaticamente ficaram indisponíveis.</p>
 *
 * <p>Cada fornecedor possui apenas dois números de telefone para contato direto, porém possui uma lista de contatos.
 * A lista de contatos não possui limite de contatos, cada contato terá seus dados e um telefone para o mesmo.
 * Desta forma é possível que um fornecedor possa ter vários telefones, sendo apenas dois genéricos (sem contato).</p>
 *
 * @see AdvancedObject
 * @see Phone
 * @see ProviderContacts
 * @author Andrew
 */

class Provider extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres necessário na razão social.
	 */
	public const MIN_COMPANY_NAME_LEN = 6;
	/**
	 * @var int quantidade máxima de caracteres necessário na razão social.
	 */
	public const MAX_COMPANY_NAME_LEN = 72;
	/**
	 * @var int quantidade mínima de caracteres necessário no nome fantasia.
	 */
	public const MIN_FANTASY_NAME_LEN = 6;
	/**
	 * @var int quantidade máxima de caracteres necessário no nome fantasia.
	 */
	public const MAX_FANTASY_NAME_LEN = 48;
	/**
	 * @var int quantidade mínima de caracteres necessário no nome do representante.
	 */
	public const MIN_SPOKESMAN_LEN = MIN_NAME_LEN;
	/**
	 * @var int quantidade máxima de caracteres necessário no nome do representante.
	 */
	public const MAX_SPOKESMAN_LEN = MAX_NAME_LEN;
	/**
	 * @var int quantidade máxima de caracteres necessário no endereço do site.
	 */
	public const MAX_SITE_LEN = 64;

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
	 * @var Phone número de telefone secundário.
	 */
	private $otherphone;
	/**
	 * @var bool fornecedor está ativo ou não no sistema.
	 */
	private $inactive;
	/**
	 * @var ProviderContacts lista de contacts do fornecedor.
	 */
	private $contacts;

	/**
	 * Cria uma nova instância de um fornecedor.
	 * Inicializa as instâncias do telefone commercial e otherphone.
	 */

	public function __construct()
	{
		$this->id = 0;
		$this->inactive = false;
		$this->otherphone = new Phone();
		$this->commercial = new Phone();
		$this->contacts = new ProviderContacts();
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
		if ($id < 1)
			throw new EntityParseException("código de identificação inválido (id: $id)");

		$this->id = $id;
	}

	/**
	 * @return string aquisição do número do cadastro nacional de pessoa jurídica.
	 */

	public function getCNPJ(): ?string
	{
		return $this->cnpj;
	}

	/**
	 * @param string $cnpj número do cadastro nacional de pessoa jurídica.
	 */

	public function setCNPJ(string $cnpj)
	{
		if (!Functions::validateCNPJ($cnpj))
			throw new EntityParseException("CNPJ inválido (cnpj: $cnpj)");

		$this->cnpj = $cnpj;
	}

	/**
	 * @return string aquisição do nome de registro da empresa do fornecedor.
	 */

	public function getCompanyName(): ?string
	{
		return $this->companyName;
	}

	/**
	 * @param string $companyName nome de registro da empresa do fornecedor.
	 */

	public function setCompanyName(string $companyName)
	{
		if (!StringUtil::hasBetweenLength($companyName, self::MIN_COMPANY_NAME_LEN, self::MAX_COMPANY_NAME_LEN))
			throw EntityParseException::new("razão social deve ter de %d a %d caracteres (razão social: $companyName)", self::MIN_COMPANY_NAME_LEN, self::MAX_COMPANY_NAME_LEN);

		$this->companyName = $companyName;
	}

	/**
	 * @return string aquisição do nome de fachada da empresa do fornecedor.
	 */

	public function getFantasyName(): ?string
	{
		return $this->fantasyName;
	}

	/**
	 * @param string $fantasyName nome de fachada da empresa do fornecedor.
	 */

	public function setFantasyName(string $fantasyName)
	{
		if (!StringUtil::hasBetweenLength($fantasyName, self::MIN_FANTASY_NAME_LEN, self::MAX_FANTASY_NAME_LEN))
			throw EntityParseException::new("nome fantasia deve ter de %d a %d caracteres (nome fantasia: $fantasyName)", self::MIN_FANTASY_NAME_LEN, self::MAX_FANTASY_NAME_LEN);

		$this->fantasyName = $fantasyName;
	}

	/**
	 * @return string aquisição do nome de uma pessoa representante do fornecedor.
	 */

	public function getSpokesman(): ?string
	{
		return $this->spokesman;
	}

	/**
	 * @param string $spokesman nome de uma pessoa representante do fornecedor.
	 */

	public function setSpokesman(?string $spokesman)
	{
		if ($spokesman != null && !StringUtil::hasBetweenLength($spokesman, self::MIN_SPOKESMAN_LEN, self::MAX_SPOKESMAN_LEN))
			throw EntityParseException::new("nome do representante deve ter de %d a %d caracteres (representante: $spokesman)", self::MIN_SPOKESMAN_LEN, self::MAX_SPOKESMAN_LEN);

		$this->spokesman = $spokesman;
	}

	/**
	 * @return string aquisição do link para acesso à página do fornecedor.
	 */

	public function getSite(): ?string
	{
		return $this->site;
	}

	/**
	 * @param string $site link para acesso à página do fornecedor.
	 */

	public function setSite(string $site)
	{
		if ($site != null)
		{
			// Remover protocolos
			$site = str_replace('http://', '', $site);
			$site = str_replace('https://', '', $site);

			if (filter_var($site, FILTER_VALIDATE_URL))
				throw new EntityParseException("site inválido (url: $site)");

			if (!StringUtil::hasMaxLength($site, self::MAX_SITE_LEN))
				throw EntityParseException::new("endereço do site deve ter até %d caracteres (site: $site)", self::MAX_SITE_LEN);
		}

		$this->site = $site;
	}

	/**
	 * @return Phone aquisição do número de telefone commercial.
	 */

	public function getCommercial(): Phone
	{
		return $this->commercial;
	}

	/**
	 * @param Phone $commercial número de telefone commercial.
	 */

	public function setCommercial(Phone $commercial)
	{
		if ($commercial == null)
			throw new EntityParseException('não é permitido definir telefone comercial nulo');

		$this->commercial = $commercial;
	}

	/**
	 * @return Phone aquisição do número de telefone secundário.
	 */

	public function getOtherPhone(): Phone
	{
		return $this->otherphone;
	}

	/**
	 * @param Phone $otherPhone número de telefone secundário
	 */

	public function setOtherPhone(Phone $otherPhone)
	{
		if ($otherPhone == null)
			throw new EntityParseException('não é permitido definir telefone secundário nulo');

		$this->otherphone = $otherPhone;
	}

	/**
	 * @return Phones aquisição de uma lista com o telefone comercial e o telefone secundário.
	 */

	public function getPhones(): Phones
	{
		$phones = new Phones();
		$phones->add($this->getCommercial());
		$phones->add($this->getOtherPhone());

		return $phones;
	}

	/**
	 * @return boolean fornecedor está ativo ou não no sistema.
	 */

	public function isInactive(): bool
	{
		return $this->inactive;
	}

	/**
	 * @param boolean $inactive fornecedor está ativo ou não no sistema.
	 */

	public function setInactive(bool $inactive)
	{
		$this->inactive = $inactive;
	}

	/**
	 * @return ProviderContacts aquisição da lista de contatos do fornecedor.
	 */

	public function getContacs(): ProviderContacts
	{
		return $this->contacts;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getParamTypes()
	 */

	public function getParamTypes():array
	{
		return [
			'id' => self::TYPE_INTEGER,
			'cnpj' => self::TYPE_STRING,
			'companyName' => self::TYPE_STRING,
			'fantasyName' => self::TYPE_STRING,
			'spokesman' => self::TYPE_STRING,
			'site' => self::TYPE_STRING,
			'inactive' => self::TYPE_BOOLEAN,
		];
	}
}

?>