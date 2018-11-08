<?php

namespace tercom;

use tercom\entities\Entity;

/**
 * @see \Iterator
 * @author andrews
 */
interface ListEntity extends \Iterator
{
	/**
	 * Adiciona um novo elemento à lista posicionando-o ao final dela.
	 * @param Entity $entity elemento do qual será adicionado à lsita.
	 * @return bool true se conseguiu adicionar ou false caso contrário.
	 */
	public function add(Entity $entity): bool;

	/**
	 * Substitui uma entidade da lista por uma outra supostamente atualizada.
	 * @param Entity $entity entidade do qual deseja substituir na lista.
	 * @return bool true se encontrado e substituído ou
	 * false caso não encontrado e adicionado.
	 */
	public function replace(Entity $entity): bool;

	/**
	 * Remove um elemento da lista através da sua posição no mesmo.
	 * Após remover o elemento irá reposicionar os elementos seguintes,
	 * desta forma não havará índices com valores não definidos.
	 * @param int $index índice do elemento do qual será removido da lista.
	 * @return bool true se conseguiu remover ou false caso contrário.
	 */
	public function remove(int $index): bool;

	/**
	 * Remove um elemento da lista através do seu valor informado.
	 * Após remover o elemento irá reposicionar os elementos seguintes,
	 * desta forma não havará índices com valores não definidos.
	 * @param Entity $entity valor do elemento do qual deseja remover.
	 * @return bool true se conseguiu remover ou false caso contrário.
	 */
	public function removeElement(Entity $entity): bool;


	/**
	 * Verifica se um determinado objeto se encontra na lista de elementos.
	 * @param Entity $entity referência do objeto do qual deseja verificar.
	 * @return bool true se a lista possuir o objeto ou false caso contrário.
	 */
	public function has(Entity $entity): bool;

	/**
	 * Percorre a lista procurando pelo índice de um objeto especifico.
	 * @param Entity $entity referência do objeto do qual deseja buscar.
	 * @return mixed índice do objeto na lista ou <code>NOT_FOUND</code> se não encontrar.
	 */
	public function indexOf(Entity $entity): int;

	/**
	 * Obtém um elemento da lista conforme o índice especificado.
	 * @param int $index índice do elemento à ser obtido.
	 * @return NULL|Entity aquisição do elemento no índice.
	 */
	public function get(int $index): ?Entity;

	/**
	 * @return Entity[] aquisição de um vetor com os elementos da lista.
	 */
	public function toElementsArray(): array;

	/**
	 * Limpa a lista de elementos iniciando um novo vetor (vazio) para a lista.
	 */
	public function clear(): void;

	/**
	 * @return int aquisição da quantidade de elementos na lista.
	 */
	public function size(): int;
}

