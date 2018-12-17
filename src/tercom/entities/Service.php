<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use tercom\entities\lists\Tags;

/**
 * Serviço
 *
 * Serviço é algo do mundo real do qual inclui uma ou mais atividades para serem cumpridas.
 * Oferecido por fornecedores no sistema de forma que estes possam ser escolhidos por clientes afim das cotações.
 * Os serviços precisam ter um nome único, descrição que inclua todos os detalhes e tags para busca.
 *
 * @see AdvancedObject
 * @see Tags
 *
 * @author Andrew
 */
class Service extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres no nome do serviço.
	 */
	public const MIN_NAME_LEN = 2;
	/**
	 * @var int quantidade máxima de caracteres no nome do serviço.
	 */
	public const MAX_NAME_LEN = 48;
	/**
	 * @var int quantidade máxima de caracteres na descrição do servio.
	 */
	public const MAX_DESCRIPTION_LEN = 256;

	/**
	 * @var int código de identificação único do serviço.
	 */
	private $id;
	/**
	 * @var string nome do serviço.
	 */
	private $name;
	/**
	 * @var string descrição que deve incluir <b>TODOS</b> os detalhes do serviço.
	 */
	private $description;
	/**
	 * @var Tags objeto com a lista de tag do serivço.
	 */
	private $tags;
	/**
	 * @var bool estado de inatividade do serviço.
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
		$this->inactive = false;
	}

	/**
	 * @return int aquisição do código de identificação único do serviço.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do serviço.
	 * @return Service aquisição do objeto de serviço usado.
	 */
	public function setId(int $id): Service
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string aquisição do nome do serviço.
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome do serviço.
	 * @return Service aquisição do objeto de serviço usado.
	 */
	public function setName(string $name): Service
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string aquisição da descrição que deve incluir <b>TODOS</b> os detalhes do serviço.
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description descrição que deve incluir <b>TODOS</b> os detalhes do serviço.
	 * @return Service aquisição do objeto de serviço usado.
	 */
	public function setDescription(string $description): Service
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return Tags aquisição do objeto com a lista de tag do serivço.
	 */
	public function getTags(): Tags
	{
		return $this->tags;
	}

	/**
	 * @param Tags $tags objeto com a lista de tag do serivço.
	 * @return Service aquisição do objeto de serviço usado.
	 */
	public function setTags(Tags $tags): Service
	{
		$this->tags = $tags;
		return $this;
	}

	/**
	 * @return bool ativar ou desativar serviço para cotações.
	 */
	public function isInactive(): bool
	{
		return $this->inactive;
	}

	/**
	 * @param bool $inactive ativar ou desativar serviço para cotações.
	 * @return Service aquisição do objeto de serviço usado.
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

