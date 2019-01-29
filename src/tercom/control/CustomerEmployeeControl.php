<?php

namespace tercom\control;

use tercom\dao\CustomerEmployeeDAO;
use tercom\entities\CustomerEmployee;
use tercom\entities\lists\CustomerEmployees;
use tercom\entities\CustomerProfile;
use tercom\entities\Customer;
use tercom\TercomException;

/**
 * @see GenericControl
 * @see CustomerEmployee
 * @see CustomerEmployees
 * @see CustomerEmployeeDAO
 * @author Andrew
 */
class CustomerEmployeeControl extends GenericControl
{
	/**
	 * @var CustomerEmployeeDAO
	 */
	private $customerEmployeeDAO;
	/**
	 * @var CustomerProfileControl
	 */
	private $customerProfileControl;
	/**
	 * @var PhoneControl
	 */
	private $phoneControl;

	/**
	 *
	 */
	public function __construct()
	{
		$this->customerEmployeeDAO = new CustomerEmployeeDAO();
		$this->customerProfileControl = new CustomerProfileControl();
		$this->phoneControl = new PhoneControl();
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmployee
	 * @param int $assignmentLevel|NULL
	 * @throws ControlException
	 */
	public function verify(CustomerEmployee $customerEmployee, ?int $assignmentLevel = null)
	{
		if (!$this->isTercomManagement())
			if ($customerEmployee->getCustomerProfile()->getCustomerId() !== $this->getCustomerLoggedId())
				throw TercomException::newPermissionRestrict();

		if ($assignmentLevel !== null)
			if ($customerEmployee->getCustomerProfile()->getAssignmentLevel() > $assignmentLevel)
				throw TercomException::newPermissionLowLevel();
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmployee
	 * @param int $assignmentLevel
	 * @throws ControlException
	 */
	public function add(CustomerEmployee $customerEmployee, int $assignmentLevel): void
	{
		$this->verify($customerEmployee, $assignmentLevel);

		if (!$this->customerEmployeeDAO->insert($customerEmployee))
			throw new ControlException('não foi possível adicionar o funcionário de cliente');
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmployee
	 * @param CustomerProfile $customerProfile
	 * @param int $assignmentLevel
	 * @throws ControlException
	 */
	public function set(CustomerEmployee $customerEmployee, ?CustomerProfile $customerProfile, int $assignmentLevel): void
	{
		$this->verify($customerEmployee, $assignmentLevel);

		if ($customerProfile !== null)
		{
			if ($customerProfile->getCustomerId() !== $customerEmployee->getCustomerProfile()->getCustomerId())
				throw new ControlException('novo perfil não pertence ao mesmo cliente');

			$customerEmployee->setCustomerProfile($customerProfile);
		}

		if (!$this->phoneControl->keepPhones($customerEmployee->getPhones()))
			throw new ControlException('não foi possível atualizar os dados do(s) telefone(s)');

		if (!$this->customerEmployeeDAO->update($customerEmployee))
			throw new ControlException('não foi possível atualizar o funcionário de cliente');
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmployee
	 * @throws ControlException
	 */
	public function removePhone(CustomerEmployee $customerEmployee): void
	{
		$this->customerEmployeeDAO->beginTransaction();
		{
			$phone = $customerEmployee->getPhone();
			$customerEmployee->setPhone(null);

			if (!$this->customerEmployeeDAO->update($customerEmployee) || !$this->phoneControl->removePhone($phone))
			{
				$this->customerEmployeeDAO->rollback();
				throw new ControlException('não foi possível excluir o número de telefone');
			}
		}
		$this->customerEmployeeDAO->commit();
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmployee
	 * @throws ControlException
	 */
	public function removeCellphone(CustomerEmployee $customerEmployee): void
	{
		$this->customerEmployeeDAO->beginTransaction();
		{
			$cellphone = $customerEmployee->getCellphone();
			$customerEmployee->setCellphone(null);

			if (!$this->customerEmployeeDAO->update($customerEmployee) || !$this->phoneControl->removePhone($cellphone))
			{
				$this->customerEmployeeDAO->rollback();
				throw new ControlException('não foi possível excluir o número de celular');
			}
		}
		$this->customerEmployeeDAO->commit();
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmployee
	 * @throws ControlException
	 */
	public function setEnabled(CustomerEmployee $customerEmployee): void
	{
		if (!$this->customerEmployeeDAO->updateEnabled($customerEmployee))
			throw new ControlException('não foi possível atualizar o estado do funcionário de cliente');
	}

	/**
	 *
	 * @param int $idCustomerEmployee
	 * @throws ControlException
	 * @return CustomerEmployee
	 */
	public function get(int $idCustomerEmployee): CustomerEmployee
	{
		if (($customerEmployee = $this->customerEmployeeDAO->select($idCustomerEmployee)) === null)
			throw new ControlException('funcionário não encontrado');

		$this->verify($customerEmployee);

		return $customerEmployee;
	}

	/**
	 *
	 * @param string $email
	 * @return CustomerEmployee
	 */
	public function getByEmail(string $email): CustomerEmployee
	{
		if (($customerEmployee = $this->customerEmployeeDAO->selectByEmail($email)) === null)
			throw new ControlException('endereço de e-mail não registrado');

		return $customerEmployee;
	}

	/**
	 *
	 * @throws ControlException
	 * @return CustomerEmployees
	 */
	public function getAll(): CustomerEmployees
	{
		if (!$this->isTercomManagement() && $this->hasCustomerLogged())
			return $this->getByCustomer($this->getCustomerLogged());

		return $this->customerEmployeeDAO->selectAll();
	}

	/**
	 *
	 * @param Customer $customer
	 * @return CustomerEmployees
	 */
	public function getByCustomer(Customer $customer): CustomerEmployees
	{
		return $this->customerEmployeeDAO->selectByCustomer($customer);
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @return CustomerEmployees
	 */
	public function getByCustomerProfile(CustomerProfile $customerProfile): CustomerEmployees
	{
		if (!$this->isTercomManagement() && $this->hasCustomerLogged())
			if ($this->getCustomerLoggedId() !== $customerProfile->getCustomerId())
				throw TercomException::newPermissionRestrict();

		return $this->customerEmployeeDAO->selectByProfile($customerProfile);
	}

	/**
	 *
	 * @param string $email
	 * @param int $idCustomerEmployee
	 * @return bool
	 */
	public function avaiableEmail(string $email, int $idCustomerEmployee = 0): bool
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			throw new ControlException('endereço de e-mail inválido');

		return !$this->customerEmployeeDAO->existEmail($email, $idCustomerEmployee);
	}
}

