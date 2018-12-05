<?php

namespace tercom\dao;

use tercom\entities\LoginTercom;
use dProject\MySQL\Result;
use tercom\entities\TercomEmployee;

/**
 * @see GenericDAO
 * @author Andrew
 */
class LoginTercomDAO extends GenericDAO
{
	/**
	 *
	 * @param LoginTercom $loginCustomer
	 * @return bool
	 */
	public function insert(LoginTercom $loginTercom): bool
	{
		$sql = "INSERT INTO logins_tercom (idLogin, idTercomEmployee)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $loginTercom->getId());
		$query->setInteger(2, $loginTercom->getTercomEmployeeId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param LoginTercom $loginTercom
	 * @return int
	 */
	public function updateLogouts(LoginTercom $loginTercom): int
	{
		$sql = "UPDATE logins
				INNER JOIN logins_tercom ON logins_tercom.idLogin = logins.id
				SET logins.logout = ?
				WHERE logins_tercom.idLogin <> ? AND logins_tercom.idTercomEmployee = ?";

		$query = $this->createQuery($sql);
		$query->setBoolean(1, true);
		$query->setInteger(2, $loginTercom->getId());
		$query->setInteger(3, $loginTercom->getTercomEmployeeId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @return string
	 */
	private function newSelect(): string
	{
		$loginColumns = $this->buildQuery(LoginDAO::ALL_COLUMNS, 'logins');
		$tercomEmployeeColumns = $this->buildQuery(TercomEmployeeDAO::ALL_COLUMNS, 'tercom_employees', 'tercomEmployee');

		return "SELECT $loginColumns, $tercomEmployeeColumns
				FROM logins_tercom
				INNER JOIN logins ON logins.id = logins_tercom.idLogin
				INNER JOIN tercom_employees ON tercom_employees.id = logins_tercom.idTercomEmployee";
	}

	/**
	 *
	 * @param int $idLogin
	 * @param int $idTercomEmployee
	 * @return LoginTercom|NULL
	 */
	public function select(int $idLogin, int $idTercomEmployee): ?LoginTercom
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE logins_tercom.idLogin = ? AND logins_tercom.idTercomEmployee = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idLogin);
		$query->setInteger(2, $idTercomEmployee);

		$result = $query->execute();

		return $this->parseLoginTercom($result);
	}

	/**
	 *
	 * @param Result $result
	 * @return LoginTercom|NULL
	 */
	private function parseLoginTercom(Result $result): ?LoginTercom
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newLoginTercom($entry);
	}

	/**
	 *
	 * @param array $entry
	 * @return LoginTercom
	 */
	private function newLoginTercom(array $entry): LoginTercom
	{
		$tercomEmployeeEntry = $this->parseArrayJoin($entry, 'tercomEmployee');

		$tercomEmployee = new TercomEmployee();
		$tercomEmployee->fromArray($tercomEmployeeEntry);

		$loginTercom = new LoginTercom();
		$loginTercom->fromArray($entry);
		$loginTercom->setTercomEmployee($tercomEmployee);

		return $loginTercom;
	}
}

