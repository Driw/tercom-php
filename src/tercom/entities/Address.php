<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;

/**
 * @see AdvancedObject
 * @author Andrew
 */
class Address extends AdvancedObject
{
	/**
	 * @var int
	 */
	public const MIN_CITY_LEN = 3;
	/**
	 * @var int
	 */
	public const MAX_CITY_LEN = 48;
	/**
	 * @var int
	 */
	public const CEP_LEN = 8;
	/**
	 * @var int
	 */
	public const MIN_NEIGHBORHOOD_LEN = 3;
	/**
	 * @var int
	 */
	public const MAX_NEIGHBORHOOD_LEN = 32;
	/**
	 * @var int
	 */
	public const MIN_STREET_LEN = 3;
	/**
	 * @var int
	 */
	public const MAX_STREET_LEN = 32;
	/**
	 * @var int
	 */
	public const MIN_NUMBER = 00001;
	/**
	 * @var int
	 */
	public const MAX_NUMBER = 99999;
	/**
	 * @var int
	 */
	public const MAX_COMPLEMENT_LEN = 24;

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var string
	 */
	private $state;
	/**
	 * @var string
	 */
	private $city;
	/**
	 * @var string
	 */
	private $cep;
	/**
	 * @var string
	 */
	private $neighborhood;
	/**
	 * @var string
	 */
	private $street;
	/**
	 * @var int
	 */
	private $number;
	/**
	 * @var string
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
	public function getState(): string
	{
		return $this->state;
	}

	/**
	 * @param string $state
	 */
	public function setState(string $state)
	{
		$this->state = $state;
	}

	/**
	 * @return string
	 */
	public function getCity(): string
	{
		return $this->city;
	}

	/**
	 * @param string $city
	 */
	public function setCity(string $city)
	{
		$this->city = $city;
	}

	/**
	 * @return string
	 */
	public function getCep(): string
	{
		return $this->cep;
	}

	/**
	 * @param string $cep
	 */
	public function setCep(string $cep)
	{
		$this->cep = $cep;
	}

	/**
	 * @return string
	 */
	public function getNeighborhood(): string
	{
		return $this->neighborhood;
	}

	/**
	 * @param string $neighborhood
	 */
	public function setNeighborhood(string $neighborhood)
	{
		$this->neighborhood = $neighborhood;
	}

	/**
	 * @return string
	 */
	public function getStreet(): string
	{
		return $this->street;
	}

	/**
	 * @param string $street
	 */
	public function setStreet(string $street)
	{
		$this->street = $street;
	}

	/**
	 * @return int
	 */
	public function getNumber(): int
	{
		return $this->number;
	}

	/**
	 * @param int $number
	 */
	public function setNumber(int $number)
	{
		$this->number = $number;
	}

	/**
	 * @return string|NULL
	 */
	public function getComplement(): ?string
	{
		return $this->complement;
	}

	/**
	 * @param string $complement
	 */
	public function setComplement(?string $complement)
	{
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
			'complement' => ObjectUtil::TYPE_STRING,
		];
	}
}

