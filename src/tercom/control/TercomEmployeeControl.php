<?php

namespace tercom\control;

use tercom\dao\TercomEmployeeDAO;
use tercom\entities\TercomEmployee;
use tercom\entities\TercomProfile;
use tercom\entities\lists\TercomEmployees;
use tercom\Functions;

/**
 * @see GenericControl
 * @see TercomEmployee
 * @see TercomEmployees
 * @see TercomEmployeeDAO
 * @author Andrew
 */
class TercomEmployeeControl extends GenericControl
{
	/**
	 * @var TercomEmployeeDAO
	 */
	private $tercomEmployeeDAO;
	/**
	 * @var TercomProfileControl
	 */
	private $tercomProfileControl;
	/**
	 * @var PhoneControl
	 */
	private $phoneControl;

	/**
	 *
	 */
	public function __construct()
	{
		$this->tercomEmployeeDAO = new TercomEmployeeDAO();
		$this->tercomProfileControl = new TercomProfileControl();
		$this->phoneControl = new PhoneControl();
	}

	/**
	 *
	 * @param TercomEmployee $tercomEmployee
	 * @throws ControlException
	 */
	public function verify(TercomEmployee $tercomEmployee)
	{
		if (!$this->tercomProfileControl->has($tercomEmployee->getTercomProfileId()))
			throw new ControlException('perfil não encontrado');

		if (!$this->avaiableCpf($tercomEmployee->getCpf(), $tercomEmployee->getId()))
			throw new ControlException('CPF indisponível');

		if (!$this->avaiableEmail($tercomEmployee->getEmail(), $tercomEmployee->getId()))
			throw new ControlException('CPF indisponível');

		if ($tercomEmployee->getPhone()->getId() !== 0)
			if ($this->phoneControl->has($tercomEmployee->getPhone()->getId()))
				throw new ControlException('número de telefone não encontrado');

		if ($tercomEmployee->getCellphone()->getId() !== 0)
			if ($this->phoneControl->has($tercomEmployee->getCellphone()->getId()))
				throw new ControlException('número de celular não encontrado');
	}

	/**
	 *
	 * @param TercomEmployee $tercomEmployee
	 * @return bool
	 */
	public function add(TercomEmployee $tercomEmployee): bool
	{
		$this->verify($tercomEmployee);

		return $this->tercomEmployeeDAO->insert($tercomEmployee);
	}

	/**
	 *
	 * @param TercomEmployee $tercomEmployee
	 * @param TercomProfile $tercomProfile
	 * @throws ControlException
	 * @return bool
	 */
	public function set(TercomEmployee $tercomEmployee, ?TercomProfile $tercomProfile = null): bool
	{
		$this->verify($tercomEmployee);

		if ($tercomProfile !== null)
			$tercomEmployee->setTercomProfile($tercomProfile);

		return $this->tercomEmployeeDAO->update($tercomEmployee);
	}

	/**
	 *
	 * @param TercomEmployee $tercomEmployee
	 * @return bool
	 */
	public function setEnabled(TercomEmployee $tercomEmployee): bool
	{
		return $this->tercomEmployeeDAO->updateEnabled($tercomEmployee);
	}

	/**
	 *
	 * @param int $idTercomEmployee
	 * @throws ControlException
	 * @return TercomEmployee
	 */
	public function get(int $idTercomEmployee): TercomEmployee
	{
		if (($tercomEmployee = $this->tercomEmployeeDAO->select($idTercomEmployee)) === null)
			throw new ControlException('funcionário não encontrado');

		return $tercomEmployee;
	}

	/**
	 *
	 * @param string $email
	 * @return TercomEmployee
	 */
	public function getByEmail(string $email): TercomEmployee
	{
		if (($tercomEmployee = $this->tercomEmployeeDAO->selectByEmail($email)) === null)
			throw new ControlException('endereço de e-mail não registrado');

		return $tercomEmployee;
	}

	/**
	 *
	 * @throws ControlException
	 * @return TercomEmployees
	 */
	public function getAll(): TercomEmployees
	{
		return $this->tercomEmployeeDAO->selectAll();
	}

	/**
	 *
	 * @param int $assignmentLevel
	 * @return TercomEmployees
	 */
	public function getByAssignmentLevel(int $assignmentLevel): TercomEmployees
	{
		return $this->tercomEmployeeDAO->selectByAssignmentLevel($assignmentLevel);
	}

	/**
	 *
	 * @param TercomProfile $tercomProfile
	 * @return TercomEmployees
	 */
	public function getByTercomProfile(TercomProfile $tercomProfile): TercomEmployees
	{
		return $this->tercomEmployeeDAO->selectByProfile($tercomProfile);
	}

	/**
	 *
	 * @param string $email
	 * @param int $idTercomEmployee
	 * @return bool
	 */
	public function avaiableCpf(string $cpf, int $idTercomEmployee = 0): bool
	{
		if (!Functions::validateCPF($cpf))
			throw new ControlException('CPF inválido');

		return !$this->tercomEmployeeDAO->existCpf($cpf, $idTercomEmployee);
	}

	/**
	 *
	 * @param string $email
	 * @param int $idTercomEmployee
	 * @return bool
	 */
	public function avaiableEmail(string $email, int $idTercomEmployee = 0): bool
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			throw new ControlException('endereço de e-mail inválido');

		return !$this->tercomEmployeeDAO->existEmail($email, $idTercomEmployee);
	}
}

