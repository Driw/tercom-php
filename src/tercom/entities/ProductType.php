<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;
use tercom\Entities\EntityParseException;

/**
 * <h1>Tipo de Produto</h1>
 *
 * <p></p>
 *
 * @see AdvancedObject
 * @author Andrew
 */

class ProductType extends AdvancedObject
{
	/**
	 * @var int
	 */
	public const MIN_NAME_LEN = 3;
	/**
	 * @var int
	 */
	public const MAX_NAME_LEN = 32;

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var string
	 */
	private $name;

	/**
	 *
	 */

	public function __construct()
	{
		$this->id = 0;
	}

	/**
	 * @return number
	 */

	public function getID()
	{
		return $this->id;
	}

	/**
	 * @param number $id
	 */

	public function setID($id)
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */

	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */

	public function setName($name)
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres (nome: $name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */

	public function getAttributeTypes():array
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'name' => ObjectUtil::TYPE_STRING,
		];
	}
}
