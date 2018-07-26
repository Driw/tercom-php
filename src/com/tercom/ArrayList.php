<?php

namespace tercom;

use InvalidArgumentException;
use Iterator;
use dProject\Primitive\AdvancedObject;

/**
 * <h1>Lista em Vetor</h1>
 *
 * <p>Estrutura de dados de lista utilizando um vetor e também permite realizar iterações.</p>
 *
 * @see \Iterator
 * @author Andrew
 */

class ArrayList extends AdvancedObject implements Iterator
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
	private $elements;

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
	 * Adiciona um novo elemento à lista posicionando-o ao final dela.
	 * @param mixed $element elemento do qual será adicionado à lsita.
	 */

	public function add($element)
	{
		if ($this->generic !== null && !($element instanceof $this->generic))
			throw new InvalidArgumentException(sprintf('lista aceita apenas objetos do tipo %s', nameOf($this->generic)));

		array_push($this->elements, $element);
	}

	/**
	 * Remove um elemento da lista através da sua posição no mesmo.
	 * Após remover o elemento irá reposicionar os elementos seguintes,
	 * desta forma não havará índices com valores não definidos.
	 * @param mixed $index índice do elemento do qual será removido da lista.
	 */

	public function remove($index)
	{
		if (isset($this->elements[$index]))
			unset($this->elements[$index]);

		$this->elements = array_values($this->elements);
	}

	/**
	 * Remove um elemento da lista através do seu valor informado.
	 * Após remover o elemento irá reposicionar os elementos seguintes,
	 * desta forma não havará índices com valores não definidos.
	 * @param mixed $element valor do elemento do qual deseja remover.
	 */

	public function removeElement($element)
	{
		if (($index = $this->indexOf($element)) !== self::NOT_FOUND)
			$this->remove($index);
	}

	/**
	 * Verifica se um determinado objeto se encontra na lista de elementos.
	 * @param mixed $element referência do objeto do qual deseja verificar.
	 * @return bool true se a lista possuir o objeto ou false caso contrário.
	 */

	public function has($element)
	{
		return $this->key($element) !== self::NOT_FOUND;
	}

	/**
	 * Percorre a lista procurando pelo índice de um objeto especifico.
	 * @param mixed $element referência do objeto do qual deseja buscar.
	 * @return mixed índice do objeto na lista ou <code>NOT_FOUND</code> se não encontrar.
	 */

	public function indexOf($element)
	{
		if (($index = array_search($element, $this->elements)) === false)
			return self::NOT_FOUND;

		return $index;
	}

	/**
	 * Obtém um elemento da lista conforme o índice especificado.
	 * @param mixed $index índice do elemento à ser obtido.
	 * @return NULL|mixed aquisição do elemento no índice ou
	 * null se o índice invádlio.
	 */

	public function get($index)
	{
		if (isset($this->elements[$index]))
			return $this->elements[$index];

		return null;
	}

	/**
	 * Limpa a lista de elementos iniciando um novo vetor (vazio) para a lista.
	 */

	public function clear()
	{
		$this->elements = [];
	}

	/**
	 * @return number aquisição da quantidade de elementos na lista.
	 */

	public function size()
	{
		return count($this->elements);
	}

	/**
	 * {@inheritDoc}
	 * @see Iterator::next()
	 */

	public function next()
	{
		next($this->elements);
	}

	/**
	 * {@inheritDoc}
	 * @see Iterator::valid()
	 */

	public function valid()
	{
		$key = key($this->elements);

		return $key !== null && $key !== false;
	}

	/**
	 * {@inheritDoc}
	 * @see Iterator::current()
	 */

	public function current()
	{
		return current($this->elements);
	}

	/**
	 * {@inheritDoc}
	 * @see Iterator::rewind()
	 */

	public function rewind()
	{
		reset($this->elements);
	}

	/**
	 * {@inheritDoc}
	 * @see Iterator::key()
	 */

	public function key()
	{
		return key($this->elements);
	}
}

?>