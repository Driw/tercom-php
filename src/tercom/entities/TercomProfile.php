<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\IntegerUtil;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 * Perfil TERCOM
 *
 * O perfil TERCOM tem como finalidade agrupar um conjunto de permissões para um ou mais funcionários.
 * Desta forma, a gerência de permissões se torna mais fácil, onde alterar as permissões de um perfil
 * será aplicado para todo funcionário que esteja neste perfil, não sendo necessário iterar funcionários.
 *
 * @see AdvancedObject
 *
 * @author Andrew
 */
class TercomProfile extends AdvancedObject
{
	/**
	 * @var int quantidade mínima da caracteres no nome do perifl.
	 */
	public const MIN_NAME_LEN = 3;
	/**
	 * @var int quantidade máxima de caracteres no nome do perfil.
	 */
	public const MAX_NAME_LEN = 64;
	/**
	 * @var int nível mínimo de assinatura de permissões.
	 */
	public const MIN_ASSIGNMENT_LEVEL = Permission::MIN_ASSIGNMENT_LEVEL;
	/**
	 * @var int nível máximo de assinatura de permissões.
	 */
	public const MAX_ASSIGNMENT_LEVEL = Permission::MAX_ASSIGNMENT_LEVEL;

	/**
	 * @var int código de identificação único do perfil TERCOM.
	 */
	private $id;
	/**
	 * @var string nome para assimilação do conjunto de permissões.
	 */
	private $name;
	/**
	 * @var int nível de assinatura de permissões no perfil.
	 */
	private $assignmentLevel;

	/**
	 * Construtor para inicializar os atributos obrigatórios com valores "em branco".
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->name = '';
		$this->assignmentLevel = 0;
	}

	/**
	 * @return int aquisição do código de identificação único do perifl.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do perifl.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return string aquisição do nome para assimilação do conjunto de permissões.
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome para assimilação do conjunto de permissões.
	 */
	public function setName(string $name): void
	{
		if (!StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new('nome deve possuir de %d a %d caracteres', self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return int aquisição do nível de assinatura de permissões no perfil.
	 */
	public function getAssignmentLevel(): int
	{
		return $this->assignmentLevel;
	}

	/**
	 * @param int $assignmentLevel nível de assinatura de permissões no perfil.
	 */
	public function setAssignmentLevel(int $assignmentLevel): void
	{
		if (!IntegerUtil::inInterval($assignmentLevel, self::MIN_ASSIGNMENT_LEVEL, self::MAX_ASSIGNMENT_LEVEL))
			throw EntityParseException::new('nível de assinatura deve ser de %d a %d', self::MIN_ASSIGNMENT_LEVEL, self::MAX_ASSIGNMENT_LEVEL);

		$this->assignmentLevel = $assignmentLevel;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */
	public function getAttributeTypes()
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'name' => ObjectUtil::TYPE_STRING,
			'assignmentLevel' => ObjectUtil::TYPE_INTEGER,
		];
	}
}

