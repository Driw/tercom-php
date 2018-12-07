<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 * Unidade de Produto
 *
 * A unidade de produto tem como finalidade adicionar uma segunda descrição pré definida por uma lista.
 * Esta lista é correspondente pelas unidades de produtos existentes no sitema registradas posteriormente.
 * Desta forma também não deixa fixado as unidades de produto no código mas sim dinâmicas pelo banco de dados.
 *
 * @see AdvancedObject
 *
 * @author Andrew
 */
class ProductUnit extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres para definir o nome.
	 */
	public const MIN_NAME_LEN = 3;
	/**
	 * @var int quantidade máxima de caracteres para definir o nome.
	 */
	public const MAX_NAME_LEN = 32;
	/**
	 * @var int quantidade mínima de caracteres para definir a abreviação.
	 */
	public const MIN_SHORT_NAME_LEN = 1;
	/**
	 * @var int quantidade máxima de caracteres para definir a abreviação.
	 */
	public const MAX_SHORT_NAME_LEN = 6;

	/**
	 * @var int código de identificação único da unidade de produto.
	 */
	private $id;
	/**
	 * @var string nome da unidade.
	 */
	private $name;
	/**
	 * @var string abreviação do nome da unidade
	 */
	private $shortName;

	/**
	 * Cria uma nova instância para unidade de produto inicializado os valores com zeros e em branco quando obrigatórios.
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->name = '';
		$this->shortName = '';
	}

	/**
	 * @return int aquisição do código de identificação único da unidade de produto.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único da unidade de produto.
	 */
	public function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return string aquisição do nome da unidade.
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome da unidade.
	 */
	public function setName(string $name)
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres (nome: $name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return string aquisição da abreviação do nome da unidade.
	 */
	public function getShortName(): string
	{
		return $this->shortName;
	}

	/**
	 * @param string $shortName abreviação do nome da unidade
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
	public function getAttributeTypes(): array
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'name' => ObjectUtil::TYPE_STRING,
			'shortName' => ObjectUtil::TYPE_STRING,
		];
	}
}

