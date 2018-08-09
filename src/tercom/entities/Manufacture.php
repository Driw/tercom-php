<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;
use tercom\Entities\EntityParseException;

/**
 * <h1>Fabricante</h1>
 *
 * <p></p>
 *
 * @see AdvancedObject
 * @author Andrew
 */

class Manufacture extends AdvancedObject
{
	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var string
	 */
	private $fantasyName;

	/**
	 *
	 */

	public function __construct()
	{
		$this->id = 0;
		$this->fantasyName = '';
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

	public function getFantasyName()
	{
		return $this->fantasyName;
	}

	/**
	 * @param string $name
	 */

	public function setFantasyName($fantasyName)
	{
		if (!StringUtil::hasBetweenLength($fantasyName, MIN_FANTASY_NAME_LEN, MAX_FANTASY_NAME_LEN))
			throw new EntityParseException(sprintf('nome do fabricante deve deter de %d a %d caracteres (nome: %s)', MIN_FANTASY_NAME_LEN, MAX_FANTASY_NAME_LEN, $fantasyName));

		$this->fantasyName = $fantasyName;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */

	public function getAttributeTypes():array
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'fantasyName' => ObjectUtil::TYPE_STRING,
		];
	}
}
