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
	public function add(TercomEmployee $tercomEmployee): void
	{
		if (!$this->tercomEmployeeDAO->insert($tercomEmployee))
			throw new ControlException('não foi possível adicionar o funcionário');
	}

	/**
	 *
	 * @param TercomEmployee $tercomEmployee
	 * @param TercomProfile $tercomProfile
	 * @throws ControlException
	 */
	public function set(TercomEmployee $tercomEmployee, ?TercomProfile $tercomProfile = null): void
	{
		if ($tercomProfile !== null)
			$tercomEmployee->setTercomProfile($tercomProfile);

		if (!$this->tercomEmployeeDAO->update($tercomEmployee))
			throw new ControlException('não foi possível atualizar o funcionário');
	}

	/**
	 *
	 * @param TercomEmployee $tercomEmployee
	 * @throws ControlException
	 */
	public function setEnabled(TercomEmployee $tercomEmployee): bool
	{
		if (!$this->tercomEmployeeDAO->updateEnabled($tercomEmployee))
			throw new ControlException('não foi possível atualizar o funcionário');
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

