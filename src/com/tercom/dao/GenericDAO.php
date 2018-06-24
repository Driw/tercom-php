<?php

namespace tercom\DAO;

use dProject\MySQL\MySQL;
use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;

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

	protected function parseSingleResult(Result $result)
	{
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

	protected function parsePage($page, $lenght)
	{
		$offset = ($page - 1) * $length;

		return "LIMIT $offset, $length";
	}

	protected function parseArrayJoin($baseArray, $prefix)
	{
		$array = [];
		$prefix = "$prefix_";

		foreach ($baseArray as $name => $value)
			if (StringUtil::startsWith($name, $prefix))
				$array[substr($name, strlen($prefix))] = $value;

		return count($array) == 0 ? null : $array;
	}
}

?>