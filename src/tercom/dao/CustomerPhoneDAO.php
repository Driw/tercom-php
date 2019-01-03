<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\Customer;
use tercom\entities\Phone;
use tercom\entities\lists\Phones;

/**
 * DAO para Telefone de Cliente
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos telefones de clientes, incluindo todas operações.
 * Estas operações consiste em: adicionar, selecionar e excluir telefones de clientes.
 *
 * @see GenericDAO
 * @see Customer
 * @see Phone
 * @see Phones
 *
 * @author andrews
 */
class CustomerPhoneDAO extends GenericDAO
{
	/**
	 * Insere um novo vinculo entre um cliente e telefone já existentes.
	 * @param Customer $customer objeto do tipo cliente à vincular o telefone.
	 * @param Phone $phone objeto do tipo telefone à vincular ao cliente.
	 * @return bool true se conseguir inserir ou false caso contrário.
	 */
	public function insert(Customer $customer, Phone $phone): bool
	{
		$sql = "INSERT INTO customer_phones (idCustomer, idPhone)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setInteger(2, $phone->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui um vinculo existente entre cliente e telefone já existentes.
	 * @param Customer $customer objeto do tipo cliente à desvincular o telefone.
	 * @param Phone $phone objeto do tipo telefone à desvincular ao cliente.
	 * @return bool true se conseguir desvincular ou false caso contrário.
	 */
	public function delete(Customer $customer, Phone $phone): bool
	{
		$sql = "DELETE FROM customer_phones
				WHERE idCustomer = ? AND idPhone = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setInteger(2, $phone->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Diveras tabelas possui uma relação de lista de telefones, por tanto faz-se necessário uma query base.
	 * Esta query base deve ser complementada para cada ocasião onde há uma lista de telefones.
	 * @return string aquisição da query SQL básica para uso de inner joins com outras tabelas.
	 */
	private function newSqlBasePhone(): string
	{
		$phoneColumns = $this->buildQuery(PhoneDAO::ALL_COLUMNS, 'phones');

		return "SELECT $phoneColumns
				FROM phones";
	}

	/**
	 * Consulta no banco de dados os telefones listados por um cliente e os adiciona no mesmo.
	 * A lista de telefones anterior é substituída por uma nova lista de telefones criada internamente.
	 * @param Customer $customer referência do cliente do qual deseja carregar os telefones.
	 * @return Phones aquisição da lista de telefones vinculadas ao cliente.
	 */
	public function select(Customer $customer): Phones
	{
		$sqlBasePhone = $this->newSqlBasePhone();
		$sql = "$sqlBasePhone
				INNER JOIN customer_phones ON customer_phones.idPhone = phones.id
				WHERE customer_phones.idCustomer = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());

		$result = $query->execute();
		$phones = $this->parsePhones($result);

		return $phones;
	}

	/**
	 * Verifica se existe um vinculo entre um cliente e um telefone.
	 * @param Customer $customer objeto do tipo cliente à verificar.
	 * @param Phone $phone objeto do tipo telefone à verificar.
	 * @return bool true se existir o vinculo ou false caso contrário.
	 */
	public function exist(Customer $customer, Phone $phone): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_phones
				WHERE idCustomer = ? AND idPhone = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setInteger(2, $phone->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta no banco de addos.
	 * Esse resultado é esperado que haja apena sum único telefone, portanto ignora outros.
	 * @param Result $result resultado otvido da seleção de telefone do banco de dados.
	 * @return Phone aquisição do telefone do qual foi selecionado na consulta.
	 */
	private function parsePhone($result):Phone
	{
		$phoneArray = $this->parseSingleResult($result);

		return $this->newPhone($phoneArray);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta no banco de dados.
	 * Esse resultado é esperado que haja uma lista de telefones, portanto é criado um vetor.
	 * @param Result $result resultado obtido da seleção de telefones do banco de dados.
	 * @return Phones lista contendo todos os telefones do quão foram consultados.
	 */
	private function parsePhones(Result $result): Phones
	{
		$phones = new Phones();

		while ($result->hasNext())
		{
			$entry = $result->next();
			$phone = $this->newPhone($entry);
			$phones->add($phone);
		}

		return $phones;
	}

	/**
	 * Cria uma nova instância de um telefone carregando os dados de um registro consultado.
	 * @param array $phoneArray vetor com dados do registro obtido do banco de dados.
	 * @return Phone aquisição do telefone com os dados carregados.
	 */
	private function newPhone(array $phoneArray): Phone
	{
		$phone = new Phone();
		$phone->fromArray($phoneArray);

		return $phone;
	}
}

