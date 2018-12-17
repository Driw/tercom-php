<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\dao\exceptions\CustomerAddressDAOException;
use tercom\entities\Address;
use tercom\entities\Customer;
use tercom\entities\lists\Addresses;

/**
 * DAO para Endereços de Clientes
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos endereços dos clientes, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar, selecionar e excluir.
 *
 * @see GenericDAO
 * @see Address
 * @see Addresses
 * @see Customer
 *
 * @author andrews
 */
class CustomerAddressDAO extends GenericDAO
{
	/**
	 * Procedimento interno para validação dos dados de um endereço e cliente ao inserir e/ou atualizar.
	 * Ambos os dados já devem estar registrado no banco logo precisam ter um código de identificação.
	 * @param Customer $customer objeto do tipo cliente à ser validado.
	 * @param Address $address objeto do tipo endereço à ser validado.
	 * @throws CustomerAddressDAOException caso algum dos dados de cliente ou enderçeo não estejam de acordo.
	 */
	private function validateCustomerAddress(Customer $customer, Address $address)
	{
		// FOREIGN KEY
		if ($customer->getId() === 0) throw CustomerAddressDAOException::newCustomerNotIdentified();
		if ($address->getId() === 0) throw CustomerAddressDAOException::newAddressNotIdentified();
	}

	/**
	 * Insere um novo vículo entre um cliente e um endereço já registrados.
	 * @param Customer $customer objeto do tipo cliente à ser adicionado.
	 * @param Address $address objeto do tipo endereço à ser adicionado.
	 * @return bool true se conseguir inserir ou false caso contrário.
	 */
	public function insert(Customer $customer, Address $address): bool
	{
		$this->validateCustomerAddress($customer, $address);

		$sql = "INSERT INTO customer_addresses (idCustomer, idAddress)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setInteger(2, $address->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui o vínculo de um cliente com um endereço já registrado.
	 * @param Customer $customer objeto do tipo cliente à ser adicionado.
	 * @param Address $address objeto do tipo endereço à ser adicionado.
	 * @return bool true se conseguir excluir ou false caso contrário.
	 */
	public function delete(Customer $customer, Address $address): bool
	{
		$this->validateCustomerAddress($customer, $address);

		$sql = "DELETE FROM customer_addresses
				WHERE idCustomer = ? AND idAddress = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setInteger(2, $address->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta de endereços de clientes para SELECT.
	 */
	private function newSqlCustomerAddresses(): string
	{
		$addressColumns = $this->buildQuery(AddressDAO::ALL_COLUMNS, 'addresses');

		return "SELECT $addressColumns
				FROM addresses
				INNER JOIN customer_addresses ON customer_addresses.idAddress = addresses.id";
	}

	/**
	 * Selecione os dados de endereços de um cliente já registrado.
	 * @param Customer $customer objeto do tipo cliente à listar.
	 * @return Addresses aquisição da lista de endereços do cliente.
	 */
	public function select(Customer $customer): Addresses
	{
		$sqlCustomerAddresses = $this->newSqlCustomerAddresses();
		$sql = "$sqlCustomerAddresses
				WHERE customer_addresses.idCustomer = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());

		$result = $query->execute();

		return $this->parseAddresses($result);
	}

	/**
	 * Verifica se um vinculo entre cliente e endereço já existe no sistema.
	 * @param Customer $customer objeto do tipo cliente à verificar.
	 * @param Address $address objeto do tipo endereço à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function exist(Customer $customer, Address $address): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_addresses
				WHERE idCustomer = ? AND idAddress = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setInteger(2, $address->getId());

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) !== 0;
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

