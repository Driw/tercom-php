<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\Phone;
use tercom\entities\lists\Phones;

/**
 * <h1>Telefone DAO</h1>
 *
 * <p>Acessa diretamente a conexão com o banco de dados executando consultas de acordo com o método chamado.
 * Realiza operações de inserção, atualização, remoção, seleção de telefone por ID ou por IDs.</p>
 *
 * @see GenericDAO
 * @author Andrew
 */

class PhoneDAO extends GenericDAO
{
	/**
	 * @var array todas as colunas da tabela de telefones.
	 */
	public const ALL_COLUMNS = ['id', 'ddd', 'number', 'type'];

	/**
	 * Insere um novo telefone ao banco de dados.
	 * Se bem sucedido atualiza o telefone com seu código de identificação único.
	 * @param Phone $phone telefone com os dados do qual será inserido.
	 * @return bool true se inserido com êxito ou false caso contrário.
	 */

	public function insert(Phone $phone):bool
	{
		$sql = "INSERT INTO phones (ddd, number, type) VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $phone->getDDD());
		$query->setString(2, $phone->getNumber());
		$query->setString(3, $phone->getType());

		$result = $query->execute();

		if ($result->isSuccessful())
			$phone->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 *Atualiza todos os dados de um telefone com base no seu código de identificação.
	 * @param Phone $phonetelefone (com código de identificação) do qual será atualizado.
	 * @return bool true se ao menos um dado foi atualizada ou false caso contrário.
	 */

	public function update(Phone $phone):bool
	{
		$sql = "UPDATE phones SET ddd = ?, number = ?, type = ? WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $phone->getDDD());
		$query->setString(2, $phone->getNumber());
		$query->setString(3, $phone->getType());
		$query->setInteger(4, $phone->getId());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	/**
	 * Apaga um telefone do banco de de dados através do seu código de identificação único.
	 * @param Phone $phone telefone (com código de identificação) do qual será apagado.
	 * @return bool true se conseguir remover ou false se não encontrado.
	 */

	public function delete(Phone $phone):bool
	{
		$sql = "DELETE FROM phones WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $phone->getId());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	/**
	 * Apaga diversos telefones do banco de dados através dos seus códigos de identificações únicos.
	 * @param array $phones vetor contendo o códiugo de identificação dos telefones à serem apagados.
	 * @return int aquisição da quantidade de telefones que foram encontrados e apagados.
	 */

	public function deletePhones(array $phones):int
	{
		if (count($phones) == 0)
			return 0;

		$sqlIn = $this->buildInQueryInt($phones);
		$sql = "DELETE FROM phones WHERE id IN($sqlIn)";

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $result->getAffectedRows();
	}

	/**
	 * Seleciona um único telefone do banco de dados através do seu código de identificação único.
	 * @param int $phoneID código de identificação único do telefone à ser selecionado.
	 * @return NULL|Phone aquisição do telefone selecionado ou null se não encontrado.
	 */

	public function selectByID(int $phoneID): ?Phone
	{
		$sql = "SELECT id, ddd, number, type FROM phones WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $phoneID);

		$result = $query->execute();

		return $this->parsePhone($result);
	}

	/**
	 * Seleciona diversos telefones do banco de dados através de uma lista de IDs especificados.
	 * Se algum dos IDs não forem encontrados não causara erro, portanto se necessário deverá
	 * ser feito uma verificação fora da DAO.
	 * @param array $phoneIDs vetor contendo o ID dos telefones que serão selecionados.
	 * @return array aquisição de um vetor contendo os telefones selecionados.
	 */

	public function selectByIDs(array $phoneIDs):array
	{
		$sql = "SELECT id, ddd, number, type FROM phones WHERE id IN (?)";

		$query = $this->createQuery($sql);
		$query->setString(1, implode("', '", $phoneIDs));

		$result = $query->execute();
		$phones = $this->parsePhones($result);

		return $phones->toElementsArray();
	}

	/**
	 *
	 * @param int $idPhone
	 * @return bool
	 */
	public function exist(int $idPhone): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM phones
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idPhone);

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) === 1;
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta no banco de addos.
	 * Esse resultado é esperado que haja apena sum único telefone, portanto ignora outros.
	 * @param Result $result resultado otvido da seleção de telefone do banco de dados.
	 * @return Phone aquisição do telefone do qual foi selecionado na consulta.
	 */

	private function parsePhone($result): ?Phone
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newPhone($entry);
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

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
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

?>