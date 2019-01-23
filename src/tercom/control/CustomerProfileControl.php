<?php

namespace tercom\control;

use tercom\dao\CustomerProfileDAO;
use tercom\entities\Customer;
use tercom\entities\CustomerProfile;
use tercom\entities\lists\CustomerProfiles;
use tercom\TercomException;

/**
 * @see CustomerProfileDAO
 * @see Customer
 * @see CustomerProfile
 * @see CustomerProfiles
 * @author Andrew
 */
class CustomerProfileControl extends GenericControl
{
	/**
	 * @var CustomerProfileDAO
	 */
	private $customerProfileDAO;
	/**
	 * @var CustomerControl
	 */
	private $customerControl;

	/**
	 *
	 */
	public function __construct()
	{
		$this->customerProfileDAO = new CustomerProfileDAO();
		$this->customerControl = new CustomerControl();
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @param int $assignmentLevel
	 * @throws ControlException
	 */
	private function validateLoginAndAssignment(CustomerProfile $customerProfile, int $assignmentLevel)
	{
		if ($customerProfile->getAssignmentLevel() > $assignmentLevel)
			throw new ControlException('nível de assinatura acima do permitido');

		if (!$this->isTercomManagement())
		{
			if ($customerProfile->getId() === (new LoginCustomerControl)->getCurrent()->getCustomerEmployee()->getCustomerProfileId())
				throw new ControlException('não é permitido alterar o próprio perfil');

			if ($this->getCustomerLoggedId() !== $customerProfile->getCustomerId())
				throw TercomException::newCustomerInvliad();
		}
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @param int $assignmentLevel
	 * @throws ControlException
	 */
	public function add(CustomerProfile $customerProfile, int $assignmentLevel): void
	{
		$this->validateLoginAndAssignment($customerProfile, $assignmentLevel);

		if (!$this->customerProfileDAO->insert($customerProfile))
			throw new ControlException('não foi possível adicionar o perfil');
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @param int $assignmentLevel
	 * @throws ControlException
	 */
	public function set(CustomerProfile $customerProfile, int $assignmentLevel): void
	{
		$this->validateLoginAndAssignment($customerProfile, $assignmentLevel);

		if (!$this->customerProfileDAO->update($customerProfile))
			throw new ControlException('não foi possível atualizar o perfil');
	}

	/**
	 *
	 * @param CustomerProfile $customerProfile
	 * @param int $assignmentLevel
	 * @throws ControlException
	 */
	public function remove(CustomerProfile $customerProfile, int $assignmentLevel): void
	{
		$this->validateLoginAndAssignment($customerProfile, $assignmentLevel);

		if (!$this->customerProfileDAO->delete($customerProfile))
			throw new ControlException('não foi possível excluir o perfil');
	}

	/**
	 *
	 * @param int $idCustomerProfile
	 * @param bool $dependences
	 * @param int $assignmentLevel
	 * @throws ControlException
	 * @return CustomerProfile
	 */
	public function get(int $idCustomerProfile, bool $dependences = false, int $assignmentLevel = 0): CustomerProfile
	{
		if (($customerProfile = $this->customerProfileDAO->select($idCustomerProfile)) === null)
			throw new ControlException('perfil de cliente não encontrado');

		if ($customerProfile->getAssignmentLevel() > $assignmentLevel)
			throw TercomException::newPermissionLowLevel();

		if ($this->hasCustomerLogged() && $customerProfile->getCustomerId() !== $this->getCustomerLogged()->getId())
			throw new ControlException('perfil de cliente desconhecido');

		if ($dependences)
		{
			$customer = $this->customerControl->get($customerProfile->getCustomerId());
			$customerProfile->setCustomer($customer);
		}

		return $customerProfile;
	}

	/**
	 *
	 * @param Customer $customer
	 * @return CustomerProfiles
	 */
	public function getByCustomer(Customer $customer, int $assignmentLevel): CustomerProfiles
	{
		if (!$this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		return $this->customerProfileDAO->selectByCustomer($customer);
	}

	/**
	 *
	 * @param Customer $customer
	 * @param int $assignmentLevel
	 * @return CustomerProfiles
	 */
	public function getByCustomerLevel(Customer $customer, int $assignmentLevel): CustomerProfiles
	{
		return $this->customerProfileDAO->selectByCustomerLevel($customer, $assignmentLevel);
	}

	/**
	 *
	 * @return CustomerProfiles
	 */
	public function getAll(): CustomerProfiles
	{
		if ($this->hasCustomerLogged())
			return $this->getByCustomerLevel($this->getCustomerLogged(), (new LoginCustomerControl())->getCurrent()->getCustomer()->getProfile()->getAssignmentLevel());

		return $this->customerProfileDAO->selectAll();
	}

	/**
	 *
	 * @param int $idCustomerProfile
	 * @return bool
	 */
	public function has(int $idCustomerProfile): bool
	{
		return $this->customerProfileDAO->exist($idCustomerProfile);
	}

	/**
	 *
	 * @param Customer $customer
	 * @param string $name
	 * @param int $idCustomerProfile
	 * @return bool
	 */
	public function avaiableName(Customer $customer, string $name, int $idCustomerProfile = 0): bool
	{
		return !$this->customerProfileDAO->existName($customer, $name, $idCustomerProfile);
	}
}

