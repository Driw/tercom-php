<?php

namespace tercom\control;

use tercom\dao\CustomerEmployeeDAO;
use tercom\entities\CustomerEmployee;
use tercom\entities\lists\CustomerEmployees;
use tercom\entities\CustomerProfile;
use tercom\entities\Customer;

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
	 * @throws ControlException
	 */
	public function verify(CustomerEmployee $customerEmployee)
	{
		if (!$this->customerProfileControl->has($customerEmployee->getCustomerProfileId()))
			throw new ControlException('perfil não encontrado');

		if (!$this->avaiableEmail($customerEmployee->getEmail(), $customerEmployee->getId()))
			throw new ControlException('CPF indisponível');

		if ($customerEmployee->getPhone()->getId() !== 0)
			if ($this->phoneControl->has($customerEmployee->getPhone()->getId()))
				throw new ControlException('número de telefone não encontrado');

		if ($customerEmployee->getCellphone()->getId() !== 0)
			if ($this->phoneControl->has($customerEmployee->getCellphone()->getId()))
				throw new ControlException('número de celular não encontrado');
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmployee
	 * @return bool
	 */
	public function add(CustomerEmployee $customerEmployee): bool
	{
		$this->verify($customerEmployee);

		return $this->customerEmployeeDAO->insert($customerEmployee);
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmployee
	 * @param CustomerProfile $customerProfile
	 * @throws ControlException
	 * @return bool
	 */
	public function set(CustomerEmployee $customerEmployee, ?CustomerProfile $customerProfile = null): bool
	{
		$this->verify($customerEmployee);

		if ($customerProfile !== null)
		{
			if ($customerProfile->getCustomerId() !== $customerEmployee->getCustomerProfile()->getCustomerId())
				throw new ControlException('novo perfil não pertence ao mesmo cliente');

			$customerEmployee->setCustomerProfile($customerProfile);
		}

		return $this->customerEmployeeDAO->update($customerEmployee);
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmployee
	 * @return bool
	 */
	public function setEnabled(CustomerEmployee $customerEmployee): bool
	{
		return $this->customerEmployeeDAO->updateEnabled($customerEmployee);
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

		return $customerEmployee;
	}

	/**
	 *
	 * @throws ControlException
	 * @return CustomerEmployees
	 */
	public function getAll(): CustomerEmployees
	{
		return $this->customerEmployeeDAO->selectAll();
	}

	/**
	 *
	 * @param int $assignmentLevel
	 * @return CustomerEmployees
	 */
	public function getByAssignmentLevel(int $assignmentLevel): CustomerEmployees
	{
		return $this->customerEmployeeDAO->selectByAssignmentLevel($assignmentLevel);
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

