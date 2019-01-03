<?php

namespace tercom\dao;

use dProject\MySQL\MySQL;
use dProject\MySQL\MySQLException;
use dProject\MySQL\Query;
use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\core\System;
use tercom\dao\exceptions\DAOException;

/**
 * DAO Genérica
 *
 * Classe abstrata e genérica usada para centralizar alguns procedimentos utilizados por diversas classes do tipo DAO.
 * Esses métodos consistem em facilitar o desenvolvimento na análise de consultas e criação de consultas.
 *
 *  @see MySQL
 *  @see Query
 *  @see Result
 *
 * @author Andrew
 */
abstract class GenericDAO
{
	/**
	 * @var int código de erro MYSQL para <b>Cannot delete or update a parent row: a foreign key constraint fails (%s)</b>.
	 */
	public const ER_ROW_IS_REFERENCED_2 = 1451;

	/**
	 * @var MySQL conexão com o banco de dados à ser utilizar.
	 */
	private $mysql;

	/**
	 * Cria uma nova instância de uma DAO genérica sendo possível informar a conexão com o banco de dados.
	 * @param MySQL|NULL $mysql objeto do tipo MySQL referente a conexão com o banco de dados a utilizar,
	 * caso não seja informado será considerada a conexão do banco de dados padrão do sistema.
	 */
	public function __construct(?MySQL $mysql = null)
	{
		if ($mysql === null)
		{
			try {
				$mysql = System::getWebConnection();
			} catch (MySQLException $e) {
				throw DAOException::newMySqlConnection($e);
			}
		}

		$this->mysql = $mysql;
	}

	/**
	 * Procedimento interno usado para construir um novo objeto do tipo consulta com base na conexão definida.
	 * @param string $sql string contendo a estrutura da consulta do qual será realizada.
	 * @return Query aquisição de um novo objeto para realizar consultas no banco de dados.
	 */
	protected function createQuery(string $sql): Query
	{
		return $this->mysql->createQuery($sql);
	}

	/**
	 * Procedimento inerno usado para analisar um único resultado de consulta e retornar seus dados.
	 * @param Result $result objeto do tipo resultado contendo os dados resultantes da consulta.
	 * @return array|NULL vetor com os dados do registro consultado ou <code>NULL</code> se nenhum encontrado.
	 */
	protected function parseSingleResult(Result $result): ?array
	{
		if (!$result->hasNext())
			return null;

		$arrayData = $result->next();
		$result->free();

		return $arrayData;
	}

	/**
	 * Procedimento interno usado para analisar um resultado de consulta e construir um vetor com os dados resultantes.
	 * @param Result $result objeto do tipo resultado contendo os dados resultantes da consulta.
	 * @param string|NULL $columnIndex índice ou nome da coluna do qual será indexado os registros,
	 * caso não haja indexação dos registros por coluna deve ser informado <code>NULL</code>.
	 * @return array[] matriz onde cada índice corresponde a um vetor de dados de um único registro da consulta.
	 */
	protected function parseMultiplyResults(Result $result, ?string $columnIndex = null): array
	{
		$matrix = [];

		while ($arrayData = $result->next())
		{
			if ($columnIndex == null || empty($columnIndex) || isset($arrayData[$columnIndex]))
				array_push($matrix, $arrayData);
			else
				$matrix[$columnIndex] = $arrayData;
		}

		$result->free();

		return $matrix;
	}

	/**
	 * Procedimento para analisar dois parâmetros de paginação para consulta.
	 * @param int $page número da página do qual deseja filtrar na consulta.
	 * @param int $length quantidade de registros por página na consulta.
	 * @return string aquisição da string para concatenar na consulta.
	 */
	protected function parsePage(int $page, int $length)
	{
		$offset = ($page - 1) * $length;

		return "LIMIT $offset, $length";
	}

	/**
	 * DEPRECATED
	 * @param array $baseArray
	 * @param string $prefix
	 * @return array|NULL
	 */
	protected function parseArrayJoin(array $baseArray, string $prefix): ?array
	{
		$array = [];
		$prefix = $prefix. '_';

		foreach ($baseArray as $name => $value)
			if (StringUtil::startsWith($name, $prefix))
				$array[substr($name, strlen($prefix))] = $value;

		return count($array) == 0 ? null : $array;
	}

	/**
	 * Cria parte de uma consulta adicionando o nome das tabelas junto ao nome das colunas e com possibilidade de alias.
	 * Este procedimento é útil para criar consultas principalmente de JOINs onde há diversas colunas de diversas tabelas.
	 * @param array $columns vetor contendo o nome das colunas na tabela do banco de dados.
	 * @param string $table nome da tabela no banco de dados à ser considerado.
	 * @param string|NULL $prefix préfixo para montar o alias da coluna quando consultado ou NULL para desconsiderar.
	 * @return string aquisição de uma string com parte da consulta construída com base nos parâmetros informados.
	 */
	protected function buildQuery(array $columns, string $table, ?string $prefix = null): string
	{
		$sqlCampos = [];

		if (empty($prefix))
			foreach ($columns as $campo)
				$sqlCampos[] = sprintf('%s.%s', $table, $campo);

		else
			foreach ($columns as $campo)
				$sqlCampos[] = sprintf('%s.%s AS %s_%s', $table, $campo, $prefix, $campo);

		return implode(", ", $sqlCampos);
	}

	/**
	 * Procedimento interno para criar parte de uma consulta para a sintaxe de <code>IN</code>.
	 * @param array $ints vetor com todos os números inteniros a serem inclusos na consulta.
	 * @throws DAOException informado valor que não seja do tipo número inteiro.
	 * @return string aquisição de uma string para ser concatenada na consulta.
	 */
	protected function buildInQueryInt(array $ints)
	{
		foreach ($ints as $int)
			if (!is_int($int))
				throw new DAOException('parâmetro int esperado');

		return implode(", ", $ints);
	}

	/**
	 * Procedimento para analisar um ID que pode ser nulo, por padrão no sistema ID com valor zero deve ser nulo.
	 * @param int $id código de identificação único da entidade à ser analisado.
	 * @return int|NULL código de identificação único da entidade ou nulo se for zero.
	 */
	protected function parseNullID(int $id): ?int
	{
		return $id === 0 ? null : $id;
	}

	/**
	 * Procedimento interno usado para analisar um único registro para separar os registros de subregistros.
	 * Os subregitros devem estar no formato de <code>{prefixo}_{coluna}</code> onde prefixo são argumentos extras
	 * informados após o vetor com os dados do registro e a coluna é o nome da coluna definida em consulta.
	 * Ao fim verifica a possibilidade de remover dados de subregistros que estejam com ID nulo.
	 * @param array $entry vetor contendo os dados do registro consultado (linha de resultado),
	 * esse vetor é referenciado portanto as alterações necessárias afetam diretamente o vetor fora do método.
	 */
	protected function parseEntry(array &$entry)
	{
		$prefixes = array_slice(func_get_args(), 1);

		foreach ($prefixes as $prefix)
			foreach ($entry as $field => $value)
				if (StringUtil::startsWith($field, $prefix))
				{
					unset($entry[$field]);
					$prefixField = substr($field, strlen($prefix) + 1);
					$entry[$prefix][$prefixField] = $value;
				}

		$this->parseNullEntries($entry);
	}

	/**
	 * Procedimento interno usado para analisar um único registro para desconsiderar subregistros com ID nulo.
	 * Subregistros são registros do banco de dados obtido por JOINs que estão vinculados a um outro registro.
	 * Tem como objetivo remover dados referentes a JOINs do qual não são encontrados.
	 * @param array $entry vetor contendo os dados do registro consultado (linha de resultado),
	 * esse vetor é referenciado portanto as alterações necessárias afetam diretamente o vetor fora do método.
	 * @return boolean true se estiver ok ou false caso contrário (id informado e nulo).
	 */
	protected function parseNullEntries(array &$entry)
	{
		foreach ($entry as $field => $value)
		{
			if (!is_array($value))
			{
				if ($field === 'id' && $value === null)
					return false;
			}

			else
			{
				if (!$this->parseNullEntries($value))
					unset($entry[$field]);
			}
		}

		return true;
	}

	/**
	 * Procedimento interno para realizar a análise do resultado de uma consulta de existência de registros.
	 * Estas consultas são feitas para contar quantos registros existem em uma tabela.
	 * Para funcionar é necessário existir apenas um registros de <code>COUNT(*)</code> nomeada por <code>qty</code>.
	 * @param Query $query objeto do tipo consulta à ser considerada na análise.
	 * @return bool true se houver ao menos um registro contado ou false caso contrário.
	 */
	protected function parseQueryExist(Query $query): bool
	{
		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) > 0;
	}

	/**
	 * Procedimento para inicializar uma nova transação permitindo <b>commit</b> e <b>roolback</b>.
	 * @throws DAOException apenas se não for possível realizar uma nova transação.
	 */
	public function beginTransaction()
	{
		$sql = "START TRANSACTION";
		$query = $this->createQuery($sql);
		$query->execute();

		if (!($query->execute())->isSuccessful())
			throw new DAOException('não foi possível realizar uma nova transação'); // FIXME usar um método estático
	}

	/**
	 * Procedimento para executar um commit nas operações já realizadas.
	 * Funcional apenas quando for iniciada uma nova transação manualmente.
	 * Por padrão as conexões possuem <b>auto commit</b>.
	 * @throws DAOException apenas se não for possível realizar o commit da transação.
	 */
	public function commit()
	{
		$sql = "COMMIT";
		$query = $this->createQuery($sql);
		$query->execute();

		if (!($query->execute())->isSuccessful())
			throw new DAOException('não foi possível realizar o commit da transação'); // FIXME usar um método estático
	}

	/**
	 * Procedimento para executar um rollback nas operações já realizadas.
	 * Funcional apenas quando for iniciada uma nova transação manualmente.
	 * Por padrão as conexões possuem <b>auto commit</b>.
	 * @throws DAOException apenas se não for possível realizar o rollback da transação.
	 */
	public function rollback()
	{
		$sql = "ROLLBACK";
		$query = $this->createQuery($sql);
		$query->execute();

		if (!($query->execute())->isSuccessful())
			throw new DAOException('não foi possível realizar o rollback da transação'); // FIXME usar um método estático
	}
}

