<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use tercom\entities\lists\Tags;

/**
 * @see AdvancedObject
 * @author Andrew
 */
class Service extends AdvancedObject
{
	/**
	 * @var int
	 */
	public const MIN_NAME_LEN = 2;
	/**
	 * @var int
	 */
	public const MAX_NAME_LEN = 48;
	/**
	 * @var int
	 */
	public const MAX_DESCRIPTION_LEN = 256;

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
	private $description;
	/**
	 * @var Tags
	 */
	private $tags;
	/**
	 * @var bool
	 */
	private $inactive;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->name = '';
		$this->description = '';
		$this->tags = new Tags();
		$this->inactive = false;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param number $id
	 * @return Service
	 */
	public function setId(int $id): Service
	{
		$this->id = $id;
		return $this;
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
	 * @return Service
	 */
	public function setName(string $name): Service
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return Service
	 */
	public function setDescription(string $description): Service
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return Tags
	 */
	public function getTags(): Tags
	{
		return $this->tags;
	}

	/**
	 * @param Tags $tags
	 * @return Service
	 */
	public function setTags(Tags $tags): Service
	{
		$this->tags = $tags;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isInactive(): bool
	{
		return $this->inactive;
	}

	/**
	 * @param bool $inactive
	 * @return Service
	 */
	public function setInactive(bool $inactive): Service
	{
		$this->inactive = $inactive;
		return $this;
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
			'description' => ObjectUtil::TYPE_STRING,
			'tags' => Tags::class,
			'inactive' => ObjectUtil::TYPE_BOOLEAN,
		];
	}
}

