<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 * Tipo de Produto
 *
 * Tipo de produto é uma informação para classificar os produtos e é informado apenas no preço do produto.
 *
 * @see AdvancedObject
 * @author Andrew
 */

class ProductType extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres para o nome.
	 */
	public const MIN_NAME_LEN = 3;
	/**
	 * @var int quantidade máxima de caracteres para o nome.
	 */
	public const MAX_NAME_LEN = 32;

	/**
	 * @var int código de identificação único do tipo de produto.
	 */
	private $id;
	/**
	 * @var string nome do tipo de produto.
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
	 * @return int aquisição do código de identificação único do tipo de produto.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do tipo de produto.
	 */
	public function setID(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return string aquisição do nome do tipo de produto.
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome do tipo de produto.
	 */
	public function setName(string $name)
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres (nome: $name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */
	public function getAttributeTypes(): array
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'name' => ObjectUtil::TYPE_STRING,
		];
	}
}
