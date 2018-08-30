<?php

namespace tercom\dao;

use dProject\MySQL\MySQL;
use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use dProject\MySQL\Query;

class GenericDAO
{
	/**
	 *
	 * @var MySQL
	 */
	protected $mysql;

	public function __construct(MySQL $mysql)
	{
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

	protected function parseNullID(int $id): ?int
	{
		return $id === 0 ? null : $id;
	}
}

?>