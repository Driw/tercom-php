<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\entities\Address;
use tercom\entities\lists\Addresses;
use tercom\exceptions\AddressException;

/**
 * DAO para Endereços
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos endereços, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar, selecionar e excluir (se possível).
 *
 * Endereços obrigatoriamente devem possuir um UF, cidade, CEP, bairro, rua e número.
 * Somete o complemento é opcional já que nem todos endereços possuem um complemento.
 *
 * @see GenericDAO
 * @see Address
 * @see Addresses
 *
 * @author Andrew
 */
class AddressDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de endereços.
	 */
	public const ALL_COLUMNS = ['id', 'state', 'city', 'cep', 'neighborhood', 'street', 'number', 'complement'];

	/**
	 * Procedimento interno para validação dos dados de um fornecedor ao inserir e/ou atualizar.
	 * Fornecedores não podem possuir CNPJ, Razão Social e Nome Fantasia não definidos (em branco).
	 * @param Address $address objeto do tipo fornecedor à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws AddressException caso algum dos dados do fornecedor não estejam de acordo.
	 */
	private function validateAddress(Address $address, bool $validateID)
	{
		// PRIMARY KEY
		if ($validateID) {
			if ($address->getId() === 0)
				throw AddressException::newNotIdentified();
		} else {
			if ($address->getId() !== 0)
				throw AddressException::newIdentified();
		}

		// NOT NULL
		if (StringUtil::isEmpty($address->getState())) throw AddressException::newStateEmpty();
		if (StringUtil::isEmpty($address->getCity())) throw AddressException::newCityEmpty();
		if (StringUtil::isEmpty($address->getCep())) throw AddressException::newCepEmpty();
		if (StringUtil::isEmpty($address->getNeighborhood())) throw AddressException::newNeighborhoodEmpty();
		if (StringUtil::isEmpty($address->getStreet())) throw AddressException::newStreetEmpty();
		if ($address->getNumber() === 0) throw AddressException::newNumberEmpty();
	}

	/**
	 * Insere um novo endereço no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param Address $address objeto do tipo endereço à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(Address $address): bool
	{
		$sql = "INSERT INTO addresses (state, city, cep, neighborhood, street, number, complement)
				VALUES (?, ?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setString(1, $address->getState());
		$query->setString(2, $address->getCity());
		$query->setString(3, $address->getCep());
		$query->setString(4, $address->getNeighborhood());
		$query->setString(5, $address->getStreet());
		$query->setString(6, $address->getNumber());
		$query->setString(7, $address->getComplement());

		if (($result = $query->execute())->isSuccessful())
			$address->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 * Atualiza os dados de um endereço já existente no banco de dados.
	 * @param Address $address objeto do tipo endereço à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(Address $address): bool
	{
		$sql = "UPDATE addresses
				SET state = ?, city = ?, cep = ?, neighborhood = ?, street = ?, number = ?, complement = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setString(1, $address->getState());
		$query->setString(2, $address->getCity());
		$query->setString(3, $address->getCep());
		$query->setString(4, $address->getNeighborhood());
		$query->setString(5, $address->getStreet());
		$query->setString(6, $address->getNumber());
		$query->setString(7, $address->getComplement());
		$query->setString(8, $address->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui os dados de um endereço já existente no banco de dados.
	 * Ao excluir um endereço suas referências serão apagadas.
	 * @param Address $address objeto do tipo endereço à excluir.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function delete(Address $address): bool
	{
		$sql = "DELETE FROM addresses
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $address->getId());

		return ($query->execute())->getAffectedRows() >= 1;
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		return "SELECT id, state, city, cep, neighborhood, street, number, complement
				FROM addresses";
	}

	/**
	 * Selecione os dados de um endereço através do seu código de identificação único.
	 * @param int $idAddress código de identificação único do endereço.
	 * @return Address|NULL endereço com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idAddress): ?Address
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idAddress);

		$result = $query->execute();

		return $this->parseAddress($result);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de endereço.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Address|NULL objeto do tipo endereço com dados carregados ou NULL se não houver resultado.
	 */
	private function parseAddress(Result $result): ?Address
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newAddress($entry);
	}

	/**
	 * Procedimento inerno para analisar o resultado de uma consulta e criar os objetos de endereço.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Addresses aquisição da lista de endereços a partir da consulta.
	 */
	private function parseAddresses(Result $result): Addresses
	{
		$addresses = new Addresses();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$address = $this->newAddress($entry);
			$addresses->add($address);
		}

		return $addresses;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo endereço e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return Address aquisição de um objeto do tipo endereço com dados carregados.
	 */
	private function newAddress(array $entry): Address
	{
		$address = new Address();
		$address->fromArray($entry);

		return $address;
	}
}

