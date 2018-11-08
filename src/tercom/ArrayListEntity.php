<?php

namespace tercom;

use dProject\Primitive\AdvancedObject;
use tercom\entities\Entity;

/**
 *
 * @see AdvancedObject
 * @see ListEntity
 * @author Andrew
 */

class ArrayListEntity extends AdvancedObject implements ListEntity
{
	/**
	 * @var int índice de elemento não encontrado.
	 */
	const NOT_FOUND = -1;

	/**
	 * @var string nome da classe aceita pela lista.
	 */
	private $generic;
	/**
	 * @var array vetor contendo os elementos da lista.
	 */
	protected $elements;

	/**
	 * Cria uma nova instância de uma lista em vetor inicializando o vetor dos elementos.
	 * @param string $generic [optional] nome do tipo de classe que será aceita pela lista,
	 * se nenhum tipo for definido será considerado que aceita qualquer tipo de objeto/valor.
	 * @param array $array [optional] se definido usa o vetor como lista.
	 * caso contrário será iniciado uma lista em branco (vetor vazio).
	 */
	public function __construct(?string $generic = null, $array = null)
	{
		if (is_array($array))
			$this->elements = $array;
		else
			$this->clear();

		$this->generic = $generic;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ListEntity::add()
	 */
	public function add(Entity $entity): bool
	{
		if ($this->generic !== null && !($entity instanceof $this->generic))
			return false;

		array_push($this->elements, $entity);
		return true;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ListEntity::replace()
	 */
	public function replace(Entity $entity): bool
	{
		for ($i = 0; $i < $this->size(); $i++)
		{
			if (($element = $this->elements[$i]) !== null && $element instanceof Entity)
				if ($element->getId() === $entity->getId())
				{
					$this->elements[$i] = $entity;
					return true;
				}
		}

		$this->add($entity);
		return false;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ListEntity::remove()
	 */
	public function remove(int $index): bool
	{
		$removed = false;

		if (isset($this->elements[$index]))
		{
			unset($this->elements[$index]);
			$removed = true;
		}

		$this->elements = array_values($this->elements);
		return $removed;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ListEntity::removeElement()
	 */
	public function removeElement(Entity $entity): bool
	{
		if (($index = $this->indexOf($entity)) !== self::NOT_FOUND)
		{
			$this->remove($index);
			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ListEntity::has()
	 */
	public function has(Entity $entity): bool
	{
		return $this->key($entity) !== self::NOT_FOUND;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ListEntity::indexOf()
	 */
	public function indexOf($element): int
	{
		if (($index = array_search($element, $this->elements)) === false)
			return self::NOT_FOUND;

		return $index;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ListEntity::get()
	 */
	public function get(int $index): ?Entity
	{
		if (isset($this->elements[$index]))
			return $this->elements[$index];

		return null;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ListEntity::toElementsArray()
	 */
	public function toElementsArray(): array
	{
		return $this->elements;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ListEntity::clear()
	 */
	public function clear(): void
	{
		$this->elements = [];
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\ListEntity::size()
	 */
	public function size(): int
	{
		return count($this->elements);
	}

	/**
	 * {@inheritDoc}
	 * @see \Iterator::next()
	 */
	public function next()
	{
		next($this->elements);
	}

	/**
	 * {@inheritDoc}
	 * @see \Iterator::valid()
	 */
	public function valid()
	{
		$key = key($this->elements);

		return $key !== null && $key !== false;
	}

	/**
	 * {@inheritDoc}
	 * @see \Iterator::current()
	 */
	public function current()
	{
		return current($this->elements);
	}

	/**
	 * {@inheritDoc}
	 * @see \Iterator::rewind()
	 */
	public function rewind()
	{
		reset($this->elements);
	}

	/**
	 * {@inheritDoc}
	 * @see \Iterator::key()
	 */
	public function key()
	{
		return key($this->elements);
	}
}

?>