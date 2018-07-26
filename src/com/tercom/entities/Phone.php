<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\IntegerUtil;
use tercom\Entities\EntityParseException;
use dProject\Primitive\StringUtil;
use tercom\Functions;
use dProject\Primitive\ObjectUtil;

/**
 * <h1>Telefone</h1>
 *
 * <p>Um telefone é constituído pelo seu número de discagem direta à distância (DDD), número e type.
 * Os tipos de telefone disponíveis são> <code>TYPE_PHONE</code>, <code>TYPE_COMMERCIAL</code> e <code>TYPE_RESIDENTIAL</code></p>.
 *
 * @see AdvancedObject
 * @author Andrew
 */

class Phone extends AdvancedObject
{
	/**
	 * @var string type de telefone celular.
	 */
	public const TYPE_PHONE = 'cellphone';
	/**
	 * @var string type de telefone comercial.
	 */
	public const TYPE_COMMERCIAL = 'commercial';
	/**
	 * @var string type de telefone residêncial.
	 */
	public const TYPE_RESIDENTIAL = 'residential';

	/**
	 * @var number código de identificação do telefone.
	 */
	private $id;
	/**
	 * @var number número da discagem direta à distância.
	 */
	private $ddd;
	/**
	 * @var string número do telefone.
	 */
	private $number;
	/**
	 * @var string type de telefone.
	 */
	private $type;

	/**
	 * Cria uma nova instância de um telefone.
	 */

	public function __construct()
	{
		$this->id = 0;
		$this->type = self::TYPE_PHONE;
	}

	/**
	 * @return number aquisidção do código de identificação do telefone.
	 */

	public function getID():int
	{
		return $this->id;
	}

	/**
	 * @param number $id código de identificação do telefone.
	 */

	public function setID(int $id)
	{
		if ($id < 1)
			throw new EntityParseException(sprintf('código de identificação inválido (id: %d)', $id));

		$this->id = $id;
	}

	/**
	 * @return number aquisição do número da discagem direta à distância.
	 */

	public function getDDD():?int
	{
		return $this->ddd;
	}

	/**
	 * @param number $ddd número da discagem direta à distância.
	 */

	public function setDDD(int $ddd)
	{
		if (!IntegerUtil::inInterval($ddd, MIN_PHONE_DDD, MAX_PHONE_DDD))
			throw new EntityParseException(sprintf('DDD deve ser de %d a %d (ddd: %d)', MIN_PHONE_DDD, MAX_PHONE_DDD, $ddd));

		$this->ddd = $ddd;
	}

	/**
	 * @return string aquisição do número do telefone.
	 */

	public function getNumber():?string
	{
		return $this->number;
	}

	/**
	 * @param string $number número do telefone.
	 */

	public function setNumber(string $number)
	{
		$number = Functions::parseOnlyNumbers($number);

		if (!StringUtil::hasBetweenLength($number, MIN_PHONE_NUMBER_LEN, MAX_PHONE_NUMBER_LEN))
			throw new EntityParseException(sprintf('número deve ter de %d a %d dígitos (numero: %s)', MIN_PHONE_NUMBER_LEN, MAX_PHONE_NUMBER_LEN, $number));

		$this->number = $number;
	}

	/**
	 * @return string aquisição do type de telefone.
	 */

	public function getType():?string
	{
		return $this->type;
	}

	/**
	 * @param string $type tipo de telefone.
	 */

	public function setType(string $type)
	{
		if (!self::hasType($type))
			throw new EntityParseException(sprintf('tipo de telefone inválido (tipo: %s)', $type));

		$this->type = $type;
	}

	/**
	 * @return array aquisição de um vetor com todos os tipos de telefone.
	 */

	public static function getTypes():array
	{
		return array(
			self::TYPE_RESIDENTIAL => 'Residêncial',
			self::TYPE_COMMERCIAL => 'Comercial',
			self::TYPE_PHONE => 'Celular',
		);
	}

	/**
	 * Verifica se um determinado type de telefone é válido.
	 * @param string $type nome do type de telefone à validar.
	 * @return bool true se for válido ou false caso contrário.
	 */

	public static function hasType(string $type):bool
	{
		return isset(self::getTypes()[$type]);
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */

	public function getAttributeTypes():array
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'ddd' => ObjectUtil::TYPE_INTEGER,
			'number' => ObjectUtil::TYPE_STRING,
			'type' => ObjectUtil::TYPE_STRING,
		];
	}
}

?>