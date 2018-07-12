<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;

/**
 * <h1>Contatos do Fornecedor</h1>
 *
 * <p>Classe usada para gerenciar a lista de contacs de um fornecedor.
 * O gerenciamento é feito pelos seus métodos que permitem adicionar, remover e obter.</p>
 *
 * @author Andrew
 */

class ProviderContacts extends AdvancedObject
{
	/**
	 * @var array vetor de contacs do fornecedor.
	 */
	private $contacts;

	/**
	 * Cria uma nova lista de contacs do fornecedor.
	 * Inicializa o vetor para armzenar os contacs.
	 */

	public function __construct()
	{
		$this->clear();
	}

	/**
	 * Limpa o vetor de contacts, inicializando um novo vetor em branco.
	 */

	public function clear()
	{
		$this->contacts = [];
	}

	/**
	 * Verifica se um determinado contato de fornecedor já foi definido à lista.
	 * @param ProviderContact $contact contato de fornecedor à verificar.
	 * @return bool true se já está definido ou false caso contrário.
	 */

	private function hasContact(ProviderContact $contact):bool
	{
		return array_search($contact, $this->contacts);
	}

	/**
	 * Adiciona um contato de fornecedor à lista de contacs do fornecedor.
	 * @param ProviderContact $contact contato de fornecedor à adicionar.
	 * @return bool true se conseguir adicionar ou false se já adicionado.
	 */

	public function addContact(ProviderContact $contact):bool
	{
		if ($this->has($contact))
			return false;

		array_push($contact, $this->contacts);
	}

	/**
	 * Remove um contato de fornecedor da lista de contacs do fornecedor.
	 * @param ProviderContact $contact contato de fornecedor à remover.
	 * @return bool true se removido ou false se não encontrado.
	 */

	public function removeContact(ProviderContact $contact):bool
	{
		if (!$this->has($contact))
			return false;

		array_unshift($this->contacts, $contact);

		return true;
	}

	/**
	 * Remove um contato de fornecedor da lista de contacs do fornecedor.
	 * @param int $index número do índice no vetor de contacs à remover.
	 * @return bool true se removido ou false se não encontrado.
	 */

	public function removeIndex(int $index):bool
	{
		if (!isset($this->contacts[$index]))
			return false;

		unset($this->contacts[$index]);
		array_map('array_values', $this->contacts);

		return true;
	}

	/**
	 * Obtém um contato de fornecedor da lista de contacs do fornecedor.
	 * @param int $index número do índice no vetor de contacs à obter.
	 * @return ProviderContact aquisição do contato no índice especificado.
	 */

	public function getContact(int $index):ProviderContact
	{
		return isset($this->contacts[$index]) ? $this->contacts[$index] : null;
	}

	/**
	 * @return number aquisição da quantidade de contacs armazenados.
	 */

	public function size()
	{
		return count($this->contacts);
	}

	/**
	 * @return array aquisição de um novo vetor com todos os contacs.
	 */

	public function toArrayContact():array
	{
		$array = [];

		foreach ($this->contacts as &$contact)
			$array[$i++] = $contact;

		return $array;
	}
}

?>