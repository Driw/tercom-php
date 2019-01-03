<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;
use dProject\Primitive\IntegerUtil;
use tercom\Functions;

/**
 * Endereço
 *
 * Um endereço possui informações principalmente para a relização de entregas informados aos fornecedores.
 * Cada cliente possui uma lista de endereços e estes endereços podem ser informados conform preferência de entregas.
 * Para cada endereço há o estado (UF), cidade, bairro, rua, número, complemento e CEP.
 *
 * @see AdvancedObject
 *
 * @author Andrew
 */
class Address extends AdvancedObject implements Entity
{
	/**
	 * @var int quantidade mínima de caracteres para o nome da cidade.
	 */
	public const MIN_CITY_LEN = 3;
	/**
	 * @var int quantidade máxima de caracteres para o nome da cidade.
	 */
	public const MAX_CITY_LEN = 48;
	/**
	 * @var int quantidade exata de caracteres para o número do CEP.
	 */
	public const CEP_LEN = 8;
	/**
	 * @var int quantidade mínima de caracteres para o nome do bairro.
	 */
	public const MIN_NEIGHBORHOOD_LEN = 3;
	/**
	 * @var int quantidade máxima de caracteres para o nome do bairro.
	 */
	public const MAX_NEIGHBORHOOD_LEN = 32;
	/**
	 * @var int quantidade mínima de caracteres para o nome da rua.
	 */
	public const MIN_STREET_LEN = 3;
	/**
	 * @var int quantidade máxima de caracteres para o nome da rua.
	 */
	public const MAX_STREET_LEN = 32;
	/**
	 * @var int valor mínimo permitido no número do edifício.
	 */
	public const MIN_NUMBER = 00001;
	/**
	 * @var int valor máximo permitido no número do edifício.
	 */
	public const MAX_NUMBER = 99999;
	/**
	 * @var int quantidade máxima de caracteres na especificação do complemento.
	 */
	public const MAX_COMPLEMENT_LEN = 24;

	/**
	 * @var int código de identificação único do endereço.
	 */
	private $id;
	/**
	 * @var string UF do estado.
	 */
	private $state;
	/**
	 * @var string nome da cidade.
	 */
	private $city;
	/**
	 * @var string número do CEP.
	 */
	private $cep;
	/**
	 * @var string nome do bairro.
	 */
	private $neighborhood;
	/**
	 * @var string nome da rua.
	 */
	private $street;
	/**
	 * @var int número do edifício.
	 */
	private $number;
	/**
	 * @var string especificação complementar do endereço.
	 */
	private $complement;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->state = '';
		$this->city = '';
		$this->cep = '';
		$this->neighborhood = '';
		$this->street = '';
		$this->number = 0;
	}

	/**
	 * @return int aquisição do código de identificação único do endereço.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do endereço.
	 */
	public function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return string aquisição da UF do estado.
	 */
	public function getState(): string
	{
		return $this->state;
	}

	/**
	 * @param string $uf UF do estado.
	 */
	public function setState(string $state)
	{
		if (!self::hasState($state))
			throw new EntityParseException('UF inválida');

		$this->state = $state;
	}

	/**
	 * @return string aquisição do nome da cidade.
	 */
	public function getCity(): string
	{
		return $this->city;
	}

	/**
	 * @param string $city nome da cidade.
	 */
	public function setCity(string $city)
	{
		if (!StringUtil::hasBetweenLength($city, self::MIN_CITY_LEN, self::MAX_CITY_LEN))
			throw EntityParseException::new('cidade deve possuir de %d a %d caracteres', self::MIN_CITY_LEN, self::MAX_CITY_LEN);

		$this->city = $city;
	}

	/**
	 * @return string aquisição do número do CEP.
	 */
	public function getCep(): string
	{
		return $this->cep;
	}

	/**
	 * @param string $cep número do CEP.
	 */
	public function setCep(string $cep)
	{
		if (!Functions::validateCEP($cep))
			throw EntityParseException::new('CEP deve possuir %d dígitos', self::CEP_LEN);

		$this->cep = $cep;
	}

	/**
	 * @return string aquisição do nome do bairro.
	 */
	public function getNeighborhood(): string
	{
		return $this->neighborhood;
	}

	/**
	 * @param string $neighborhood nome do bairro.
	 */
	public function setNeighborhood(string $neighborhood)
	{
		if (!StringUtil::hasBetweenLength($neighborhood, self::MIN_STREET_LEN, self::MAX_STREET_LEN))
			throw EntityParseException::new('bairro deve possuir de %d a %d caracteres', self::MIN_NEIGHBORHOOD_LEN, self::MAX_NEIGHBORHOOD_LEN);

		$this->neighborhood = $neighborhood;
	}

	/**
	 * @return string aquisição do nome da rua.
	 */
	public function getStreet(): string
	{
		return $this->street;
	}

	/**
	 * @param string $street nome da rua.
	 */
	public function setStreet(string $street)
	{
		if (!StringUtil::hasBetweenLength($street, self::MIN_STREET_LEN, self::MAX_STREET_LEN))
			throw EntityParseException::new('rua deve possuir de %d a %d caracteres', self::MIN_STREET_LEN, self::MAX_STREET_LEN);

		$this->street = $street;
	}

	/**
	 * @return int aquisição do número do edifício.
	 */
	public function getNumber(): int
	{
		return $this->number;
	}

	/**
	 * @param int $number número do edifício.
	 */
	public function setNumber(int $number)
	{
		if (!IntegerUtil::inInterval($number, self::MIN_NUMBER, self::MAX_NUMBER))
			throw EntityParseException::new('número do edifício deve ser de %d a %d', self::MIN_NUMBER, self::MAX_NUMBER);

		$this->number = $number;
	}

	/**
	 * @return string|NULL aquisição da especificação complementar do endereço.
	 */
	public function getComplement(): ?string
	{
		return $this->complement;
	}

	/**
	 * @param string $complement especificação complementar do endereço.
	 */
	public function setComplement(?string $complement)
	{
		if ($complement !== null && !StringUtil::hasMaxLength($complement, self::MAX_COMPLEMENT_LEN))
			throw EntityParseException::new('complemento deve possuir até %d caracteres', self::MAX_COMPLEMENT_LEN);

		$this->complement = $complement;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */
	public function getAttributeTypes()
	{
		return [
			'state' => ObjectUtil::TYPE_STRING,
			'city' => ObjectUtil::TYPE_STRING,
			'cep' => ObjectUtil::TYPE_STRING,
			'neighborhood' => ObjectUtil::TYPE_STRING,
			'street' => ObjectUtil::TYPE_STRING,
			'number' => ObjectUtil::TYPE_INTEGER,
			'complement' => ObjectUtil::TYPE_STRING
		];
	}

	/**
	 * @return array aquisição da lista de UF disponível no sistema.
	 */
	public static function getUfs(): array
	{
		return [
			'AC'=>'Acre',
			'AL'=>'Alagoas',
			'AP'=>'Amapá',
			'AM'=>'Amazonas',
			'BA'=>'Bahia',
			'CE'=>'Ceará',
			'DF'=>'Distrito Federal',
			'ES'=>'Espírito Santo',
			'GO'=>'Goiás',
			'MA'=>'Maranhão',
			'MT'=>'Mato Grosso',
			'MS'=>'Mato Grosso do Sul',
			'MG'=>'Minas Gerais',
			'PA'=>'Pará',
			'PB'=>'Paraíba',
			'PR'=>'Paraná',
			'PE'=>'Pernambuco',
			'PI'=>'Piauí',
			'RJ'=>'Rio de Janeiro',
			'RN'=>'Rio Grande do Norte',
			'RS'=>'Rio Grande do Sul',
			'RO'=>'Rondônia',
			'RR'=>'Roraima',
			'SC'=>'Santa Catarina',
			'SP'=>'São Paulo',
			'SE'=>'Sergipe',
			'TO'=>'Tocantins'
		];
	}

	/**
	 * Verifica se uma determinada UF existe no sistema.
	 * @param string $uf unidade federativa à verificar.
	 * @param array $ufs [optional] lista de UF existentes,
	 * caso não informado seá considerado as do sistema.
	 * @return bool true se existir ou false caso contrário.
	 */
	public static function hasUf(string $uf, ?array $ufs = null): bool
	{
		if ($ufs === null) $ufs = self::getUfs();

		return isset($ufs[$uf]);
	}
}

