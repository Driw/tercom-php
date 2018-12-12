<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 * Emablagem de Produto
 *
 * A embalade é usada para especificar uma informação pre-definida do produto referente a sua embalagem.
 *
 * @see AdvancedObject
 * @author Andrew
 */

class ProductPackage extends AdvancedObject
{
	/**
	 * @var int quantidade máxima de caracteres para o nome da emblagem de produto.
	 */
	public const MIN_NAME_LEN = 3;
	/**
	 * @var int quantidade máxima de caracteres para o nome da emblagem de produto.
	 */
	public const MAX_NAME_LEN = 32;

	/**
	 * @var int código de identificação único da embalagem de produto.
	 */
	private $id;
	/**
	 * @var string nome da embalagem de produto.
	 */
	private $name;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->name = '';
	}

	/**
	 * @return int aquisição do código de identificação único da embalagem de produto.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único da embalagem de produto.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return string aquisição do nome da embalagem de produto.
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome da embalagem de produto.
	 */
	public function setName(string $name): void
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

