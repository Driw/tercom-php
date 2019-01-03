<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\Functions;
use tercom\entities\TercomEmployee;
use tercom\entities\TercomProfile;
use tercom\entities\lists\TercomEmployees;
use tercom\dao\exceptions\DAOException;

/**
 * @see GenericDAO
 * @see TercomEmployee
 * @author Andrew
 */
class TercomEmployeeDAO extends GenericDAO
{
	/**
	 * @var array
	 */
	public const ALL_COLUMNS = ['id', 'idTercomProfile', 'cpf', 'name', 'email', 'password', 'idPhone', 'idCellPhone', 'enabled', 'register'];

	private function validate(TercomEmployee $tercomEmployee, bool $validateID)
	{
		if ($validateID) {
			if ($tercomEmployee->getId() === 0)
				throw new DAOException('funcionário de cliente não identificado');
		} else {
			if ($tercomEmployee->getId() !== 0)
				throw new DAOException('funcionário de cliente já identificado');
		}

		if ($tercomEmployee->getTercomProfileId() === 0) throw new DAOException('perfil não informado');
		if (StringUtil::isEmpty($tercomEmployee->getCpf())) throw new DAOException('CPF não informado');
		if (StringUtil::isEmpty($tercomEmployee->getName())) throw new DAOException('nome não informado');
		if (StringUtil::isEmpty($tercomEmployee->getEmail())) throw new DAOException('endereço de e-mail não informado');
		if (StringUtil::isEmpty($tercomEmployee->getPassword())) throw new DAOException('senha não informada');
	}

	/**
	 *
	 * @param TercomEmployee $tercomEmplyee
	 * @return bool
	 */
	public function insert(TercomEmployee $tercomEmplyee): bool
	{
		$tercomEmplyee->getRegister()->setTimestamp(time());
		$this->validate($tercomEmplyee, false);

		$sql = "INSERT INTO tercom_employees (idTercomProfile, cpf, name, email, password, idPhone, idCellPhone, enabled)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomEmplyee->getTercomProfileId());
		$query->setString(2, $tercomEmplyee->getCpf());
		$query->setString(3, $tercomEmplyee->getName());
		$query->setString(4, $tercomEmplyee->getEmail());
		$query->setString(5, $tercomEmplyee->getPassword());
		$query->setInteger(6, $this->parseNullID($tercomEmplyee->getPhone()->getId()));
		$query->setInteger(7, $this->parseNullID($tercomEmplyee->getCellphone()->getId()));
		$query->setBoolean(8, $tercomEmplyee->isEnable());

		if (($result = $query->execute())->isSuccessful())
			$tercomEmplyee->setId($result->getInsertID());

		return $tercomEmplyee->getId() !== 0;
	}

	/**
	 *
	 * @param TercomEmployee $tercomEmplyee
	 * @return bool
	 */
	public function update(TercomEmployee $tercomEmplyee): bool
	{
		$this->validate($tercomEmplyee, true);

		$sql = "UPDATE tercom_employees
				SET idTercomProfile = ?, cpf = ?, name = ?, email = ?, password = ?, idPhone = ?, idCellPhone = ?, enabled = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomEmplyee->getTercomProfileId());
		$query->setString(2, $tercomEmplyee->getCpf());
		$query->setString(3, $tercomEmplyee->getName());
		$query->setString(4, $tercomEmplyee->getEmail());
		$query->setString(5, $tercomEmplyee->getPassword());
		$query->setInteger(6, $this->parseNullID($tercomEmplyee->getPhone()->getId()));
		$query->setInteger(7, $this->parseNullID($tercomEmplyee->getCellphone()->getId()));
		$query->setBoolean(8, $tercomEmplyee->isEnable());
		$query->setInteger(9, $tercomEmplyee->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @param TercomEmployee $tercomEmplyee
	 * @return bool
	 */
	public function updateEnabled(TercomEmployee $tercomEmplyee): bool
	{
		$sql = "UPDATE tercom_employees
				SET enabled = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setBoolean(1, $tercomEmplyee->isEnable());
		$query->setInteger(2, $tercomEmplyee->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 *
	 * @return string
	 */
	private function newSelectProfile(): string
	{
		$tercomEmployeeColumns = $this->buildQuery(self::ALL_COLUMNS, 'tercom_employees');
		$tercomProfileColumns = $this->buildQuery(TercomProfileDAO::ALL_COLUMNS, 'tercom_profiles', 'tercomProfile');

		return "SELECT $tercomEmployeeColumns, $tercomProfileColumns
				FROM tercom_employees
				INNER JOIN tercom_profiles ON tercom_profiles.id = tercom_employees.idTercomProfile";
	}

	/**
	 *
	 * @param int $idTercomEmployee
	 * @return TercomEmployee
	 */
	public function select(int $idTercomEmployee): ?TercomEmployee
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				WHERE tercom_employees.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idTercomEmployee);

		$result = $query->execute();

		return $this->parseTercomEmployee($result);
	}

	/**
	 *
	 * @param string $email
	 * @return TercomEmployee
	 */
	public function selectByEmail(string $email): ?TercomEmployee
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				WHERE tercom_employees.email = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $email);

		$result = $query->execute();

		return $this->parseTercomEmployee($result);
	}

	/**
	 *
	 * @return TercomEmployees
	 */
	public function selectAll(): TercomEmployees
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				ORDER BY tercom_employees.name";

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseTercomEmployees($result);
	}

	/**
	 *
	 * @param int $assignmentLevel
	 * @return TercomEmployees
	 */
	public function selectByAssignmentLevel(int $assignmentLevel): TercomEmployees
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				WHERE tercom_profiles.assignmentLevel >= ?
				ORDER BY tercom_employees.name";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $assignmentLevel);

		$result = $query->execute();

		return $this->parseTercomEmployees($result);
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @return TercomEmployees
	 */
	public function selectByProfile(TercomProfile $tercomProfile): TercomEmployees
	{
		$sqlSelectProfile = $this->newSelectProfile();
		$sql = "$sqlSelectProfile
				WHERE tercom_profiles.id = ?
				ORDER BY tercom_employees.name";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());

		$result = $query->execute();

		return $this->parseTercomEmployees($result);
	}

	/**
	 *
	 * @param string $cpf
	 * @param int $idTercomEmployee
	 * @return bool
	 */
	public function existCpf(string $cpf, int $idTercomEmployee = 0): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_employees
				WHERE cpf = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $cpf);
		$query->setInteger(2, $idTercomEmployee);

		$result = $query->execute();
		$entry = $result->next();

		return intval($entry['qty']) > 0;
	}

	/**
	 *
	 * @param string $email
	 * @param int $idTercomEmployee
	 * @return bool
	 */
	public function existEmail(string $email, int $idTercomEmployee = 0): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_employees
				WHERE email = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $email);
		$query->setInteger(2, $idTercomEmployee);

		$result = $query->execute();
		$entry = $result->next();

		return intval($entry['qty']) > 0;
	}

	/**
	 *
	 * @param Result $result
	 * @return TercomEmployee|NULL
	 */
	private function parseTercomEmployee(Result $result): ?TercomEmployee
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newTercomEmployee($entry);
	}

	/**
	 *
	 * @param Result $result
	 * @return TercomEmployees|NULL
	 */
	private function parseTercomEmployees(Result $result): TercomEmployees
	{
		$tercomEmployees = new TercomEmployees();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$tercomEmployee = $this->newTercomEmployee($entry);
			$tercomEmployees->add($tercomEmployee);
		}

		return $tercomEmployees;
	}

	/**
	 *
	 * @param array $entry
	 * @return TercomEmployee
	 */
	private function newTercomEmployee(array $entry): TercomEmployee
	{
		$tercomProfile = Functions::parseEntrySQL($entry, 'tercomProfile');

		$tercomEmployee = new TercomEmployee();
		$tercomEmployee->fromArray($entry);
		$tercomEmployee->getTercomProfile()->fromArray($tercomProfile);

		return $tercomEmployee;
	}
}

