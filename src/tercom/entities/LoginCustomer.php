<?php

namespace tercom\entities;

/**
 * Acesso de Cliente
 *
 * O acesso do cliente é o acesso de um funcionário de cliente seja ele qual for.
 * Possui todas as informações de um acesso e adiciona um funcionário de cliente no mesmo.
 *
 * @see Login
 * @see CustomerEmployee
 *
 * @author Andrew
 */
class LoginCustomer extends Login
{
	/**
	 * @var CustomerEmployee funcionário de cliente que realizou o acesso.
	 */
	private $customerEmployee;

	/**
	 * @return CustomerEmployee aquisição do funcionário de cliente que realizou o acesso.
	 */
	public function getCustomerEmployee(): CustomerEmployee
	{
		return $this->customerEmployee === null ? ($this->customerEmployee = new CustomerEmployee()) : $this->customerEmployee;
	}

	/**
	 * @param CustomerEmployee $customerEmployee funcionário de cliente que realizou o acesso.
	 */
	public function setCustomerEmployee(CustomerEmployee $customerEmployee)
	{
		$this->customerEmployee = $customerEmployee;
	}

	/**
	 * @return int aquisição do código de identificação único do funcionário de cliente
	 * que realizou o acesso ou zero se não informado.
	 */
	public function getCustomerEmployeeId(): int
	{
		return $this->customerEmployee === null ? 0 : $this->customerEmployee->getId();
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\entities\Login::getAttributeTypes()
	 */
	public function getAttributeTypes()
	{
		$attributeTypes = parent::getAttributeTypes();
		$attributeTypes['customerEmployee'] = CustomerEmployee::class;

		return $attributeTypes;
	}
}

