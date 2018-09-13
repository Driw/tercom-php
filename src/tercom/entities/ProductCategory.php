<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 * <h1>Produto Folha</h1>
 *
 * <p>Classe para especificar o nome da categoria do produto e a identificação desta categoria.
 * No sistema a folha serve para representar todas as categorias: família, grupo, subgrupo e setor.
 * A finalidade das categorias é agrupar produtos de acordo com uma característica usado para filtros.</p>
 *
 * @see AdvancedObject
 * @author Andrew
 */

abstract class ProductCategory extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres necessário no nome.
	 */
	public const MIN_NAME_LEN = MIN_NAME_LEN;
	/**
	 * @var int quantidade máxima de caracteres necessário no nome.
	 */
	public const MAX_NAME_LEN = 32;

	/**
	 * @var int código da categoria do tipo familia.
	 */
	public const CATEGORY_FAMILY = 1;
	/**
	 * @var int código da categoria do tipo grupo.
	 */
	public const CATEGORY_GROUP = 2;
	/**
	 * @var int código da categoria do tipo subgrupo.
	 */
	public const CATEGORY_SUBGROUP = 3;
	/**
	 * @var int código da categoria do tipo setor.
	 */
	public const CATEGORY_SECTOR = 4;

	/**
	 * @var int código de identificação da categoria.
	 */
	private $id;
	/**
	 * @var string nome da categoria.
	 */
	private $name;
	/**
	 * @var int tipo de categoria.
	 */
	private $type;

	/**
	 * Cria uma nova instância de um produto folha para representação de categorias.
	 * @param int $type tipo de categoria que está sendo criada.
	 */

	public function __construct(int $type)
	{
		$this->id = 0;
		$this->name = '';
		$this->type = $type;
	}

	/**
	 * @return int aquisição do código de identificação da categoria.
	 */

	public function getID(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação da categoria.
	 */

	public function setID(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return string aquisição do nome da categoria.
	 */

	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome da categoria.
	 */

	public function setName(string $name)
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres (nome: $name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return int aquisição do tipo de categoria.
	 */

	public function getType(): int
	{
		return $this->type;
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
			'type' => ObjectUtil::TYPE_INTEGER,
		];
	}
}

