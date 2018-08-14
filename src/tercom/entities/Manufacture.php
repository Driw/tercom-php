<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;
use tercom\Entities\EntityParseException;

/**
 * <h1>Fabricante</h1>
 *
 * <p>Cada vlaor de produto no sistema deve ser vinculado a um fabrincate já que um mesmo produto pode ter vários.
 * Quanto ao fabrincate é necessário apenas o seu nome e que por regra de negócio é o seu nome fantasia.</p>
 *
 * @see AdvancedObject
 * @author Andrew
 */

class Manufacture extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres necessário no nome fantasia.
	 */
	public const MIN_FANTASY_NAME_LEN = 6;
	/**
	 * @var int quantidade máxima de caracteres necessário no nome fantasia.
	 */
	public const MAX_FANTASY_NAME_LEN = 48;

	/**
	 * @var int código de identificação único.
	 */
	private $id;
	/**
	 * @var string nome fantasia da empresa.
	 */
	private $fantasyName;

	/**
	 * Cria uma nova instância de um fabricante.
	 */

	public function __construct()
	{
		$this->id = 0;
		$this->fantasyName = '';
	}

	/**
	 * @return int aquisição do código de identificação.
	 */

	public function getID(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação.
	 */

	public function setID(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return string aquisição do nome fantasia da empresa.
	 */

	public function getFantasyName(): string
	{
		return $this->fantasyName;
	}

	/**
	 * @param string $name nome fantasia da empresa.
	 */

	public function setFantasyName(string $fantasyName)
	{
		if (!StringUtil::hasBetweenLength($fantasyName, self::MIN_FANTASY_NAME_LEN, self::MAX_FANTASY_NAME_LEN))
			throw EntityParseException::new("nome do fabricante deve deter de %d a %d caracteres (nome: $fantasyName)", self::MIN_FANTASY_NAME_LEN, self::MAX_FANTASY_NAME_LEN);

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
