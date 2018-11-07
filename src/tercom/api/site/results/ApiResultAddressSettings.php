<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use dProject\Primitive\AdvancedObject;
use tercom\entities\Address;

/**
 * @see AdvancedObject
 * @see ApiResult
 * @author Andrew
 *
 */
class ApiResultAddressSettings extends AdvancedObject implements ApiResult
{
	/**
	 * @var int
	 */
	private $minCityLen;
	/**
	 * @var int
	 */
	private $maxCityLen;
	/**
	 * @var int
	 */
	private $cepLen;
	/**
	 * @var int
	 */
	private $minNeighborhoodLen;
	/**
	 * @var int
	 */
	private $maxNeighborhoodLen;
	/**
	 * @var int
	 */
	private $minStreetLen;
	/**
	 * @var int
	 */
	private $maxStreetLen;
	/**
	 * @var int
	 */
	private $minNumber;
	/**
	 * @var int
	 */
	private $maxNumber;
	/**
	 * @var int
	 */
	private $maxComplementLen;

	/**
	 *
	 */
	public function __construct()
	{
		$this->minCityLen = Address::MIN_CITY_LEN;
		$this->maxCityLen = Address::MAX_CITY_LEN;
		$this->cepLen = Address::CEP_LEN;
		$this->minNeighborhoodLen = Address::MIN_NEIGHBORHOOD_LEN;
		$this->maxNeighborhoodLen = Address::MAX_NEIGHBORHOOD_LEN;
		$this->minStreetLen = Address::MIN_STREET_LEN;
		$this->maxStreetLen = Address::MAX_STREET_LEN;
		$this->minNumber = Address::MIN_NUMBER;
		$this->maxNumber = Address::MAX_NUMBER;
		$this->maxComplementLen = Address::MAX_COMPLEMENT_LEN;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */
	public function toApiArray():array
	{
		return $this->toArray(true);
	}
}

