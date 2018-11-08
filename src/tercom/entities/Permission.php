<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;
use dProject\Primitive\IntegerUtil;

/**
 * @see AdvancedObject
 * @see Entity
 * @author andrews
 */
class Permission extends AdvancedObject implements Entity
{
	/**
	 * @var int
	 */
	public const MAX_PACKET_NAME_LEN = 32;
	/**
	 * @var int
	 */
	public const MAX_ACTION_NAME_LEN = 32;
	/**
	 * @var int
	 */
	public const MIN_ASSIGNMENT_LEVEL = 0;
	/**
	 * @var int
	 */
	public const MAX_ASSIGNMENT_LEVEL = 99;

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var string
	 */
	private $packet;
	/**
	 * @var string
	 */
	private $action;
	/**
	 * @var int
	 */
	private $assignmentLevel;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->packet = '';
		$this->action = '';
		$this->assignmentLevel = 0;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\entities\Entity::getId()
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getPacket(): string
	{
		return $this->packet;
	}

	/**
	 * @param string $packet
	 */
	public function setPacket(string $packet)
	{
		if (!StringUtil::hasMaxLength($packet, self::MAX_PACKET_NAME_LEN))
			throw EntityParseException::new('pacote da permissão deve possuir até %d caracteres', self::MAX_PACKET_NAME_LEN);

		$this->packet = $packet;
	}

	/**
	 * @return string
	 */
	public function getAction(): string
	{
		return $this->action;
	}

	/**
	 * @param string $action
	 */
	public function setAction(string $action)
	{
		if (!StringUtil::hasMaxLength($action, self::MAX_ACTION_NAME_LEN))
			throw EntityParseException::new('permissão deve possuir até %d caracteres', self::MAX_ACTION_NAME_LEN);

		$this->action = $action;
	}

	/**
	 * @return int
	 */
	public function getAssignmentLevel(): int
	{
		return $this->assignmentLevel;
	}

	/**
	 * @param int $assignmentLevel
	 */
	public function setAssignmentLevel(int $assignmentLevel)
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
			'packet' => ObjectUtil::TYPE_STRING,
			'action' => ObjectUtil::TYPE_STRING,
			'assignmentLevel' => ObjectUtil::TYPE_INTEGER,
		];
	}
}

