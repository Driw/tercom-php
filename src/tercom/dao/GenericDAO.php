<?php

namespace tercom\dao;

use dProject\MySQL\MySQL;
use dProject\MySQL\MySQLException;
use dProject\MySQL\Query;
use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\core\System;
use tercom\dao\exceptions\DAOException;

class GenericDAO
{
	public const ER_ROW_IS_REFERENCED_2 = 1451;

	private $mysql;

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

	protected function createQuery(string $sql):Query
	{
		return $this->mysql->createQuery($sql);
	}

	protected function parseSingleResult(Result $result):?array
	{
		if (!$result->hasNext())
			return null;

		$arrayData = $result->next();
		$result->free();

		return $arrayData;
	}

	protected function parseMultiplyResults(Result $result, $columnIndex = null)
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

	protected function parsePage($page, $length)
	{
		$offset = ($page - 1) * $length;

		return "LIMIT $offset, $length";
	}

	protected function parseArrayJoin($baseArray, $prefix)
	{
		$array = [];
		$prefix = $prefix. '_';

		foreach ($baseArray as $name => $value)
			if (StringUtil::startsWith($name, $prefix))
				$array[substr($name, strlen($prefix))] = $value;

		return count($array) == 0 ? null : $array;
	}

	protected function buildQuery(array $columns, string $table, $prefix = null): string
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

	protected function buildInQueryInt(array $ints)
	{
		foreach ($ints as $int)
			if (!is_int($int))
				throw new DAOException('parâmetro int esperado');

		return implode(", ", $ints);
	}

	protected function parseNullID(int $id): ?int
	{
		return $id === 0 ? null : $id;
	}

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

	protected function parseQueryExist(Query $query): bool
	{
		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) > 0;
	}

	public function beginTransaction()
	{
		$sql = "START TRANSACTION";
		$query = $this->createQuery($sql);
		$query->execute();

		if (!($query->execute())->isSuccessful())
			throw new DAOException('não foi possível realizar uma nova transação');
	}

	public function commit()
	{
		$sql = "COMMIT";
		$query = $this->createQuery($sql);
		$query->execute();

		if (!($query->execute())->isSuccessful())
			throw new DAOException('não foi possível realizar o commit da transação');
	}

	public function rollback()
	{
		$sql = "ROLLBACK";
		$query = $this->createQuery($sql);
		$query->execute();

		if (!($query->execute())->isSuccessful())
			throw new DAOException('não foi possível realizar o rollback da transação');
	}
}

