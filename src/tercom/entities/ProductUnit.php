<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 * <h1>Unidade de Produto</h1>
 *
 * <p></p>
 *
 * @see AdvancedObject
 * @author Andrew
 */

class ProductUnit extends AdvancedObject
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
	public const MIN_SHORT_NAME_LEN = 1;
	/**
	 * @var int
	 */
	public const MAX_SHORT_NAME_LEN = 6;

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $shortName;

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

	public function getID(): int
	{
		return $this->id;
	}

	/**
	 * @param number $id
	 */

	public function setID(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */

	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */

	public function setName(string $name)
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres (nome: $name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return string
	 */

	public function getShortName(): string
	{
		return $this->shortName;
	}

	/**
	 * @param string $shortName
	 */

	public function setShortName(string $shortName)
	{
		if (!StringUtil::hasBetweenLength($shortName, self::MIN_SHORT_NAME_LEN, self::MAX_SHORT_NAME_LEN))
			throw EntityParseException::new("abreveação deve possuir de %d a %d caracteres (abreveação: $shortName)", self::MIN_SHORT_NAME_LEN, self::MAX_SHORT_NAME_LEN);

		$this->shortName = $shortName;
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
