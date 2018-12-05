<?php

namespace tercom\entities;

/**
 * @see Login
 * @author Andrew
 */
class LoginCustomer extends Login
{
	/**
	 * @var CustomerEmployee
	 */
	private $customerEmployee;

	/**
	 * @return CustomerEmployee
	 */
	public function getCustomerEmployee(): CustomerEmployee
	{
		return $this->customerEmployee;
	}

	/**
	 * @param CustomerEmployee $customerEmployee
	 */
	public function setCustomerEmployee(CustomerEmployee $customerEmployee)
	{
		$this->customerEmployee = $customerEmployee;
	}

	/**
	 * @return int
	 */
	public function getCustomerEmployeeId(): int
	{
		return $this->customerEmployee === null ? 0 : $this->customerEmployee->getId();
	}
}

