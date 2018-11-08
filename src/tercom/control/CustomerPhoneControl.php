<?php

namespace tercom\control;

use tercom\dao\CustomerPhoneDAO;
use tercom\entities\Customer;
use tercom\entities\Phone;
use tercom\entities\lists\Phones;

/**
 * @author andrews
 */
class CustomerPhoneControl extends GenericControl implements RelationshipControl
{
	/**
	 * @var CustomerPhoneDAO acesso aos telefones de clientes no banco de dados.
	 */
	private $customerPhoneDAO;
	/**
	 * @var PhoneControl controle para gerenciamento de telefones no sistema.
	 */
	private $phoneControl;

	/**
	 * Cria uma nova instância de um controle de telefones para clientes.
	 */
	function __construct()
	{
		$this->customerPhoneDAO = new CustomerPhoneDAO();
		$this->phoneControl = new PhoneControl();
	}

	/**
	 *
	 * @param Customer $customer
	 * @throws ControlException
	 */
	private function validateCustomer(Customer $customer)
	{
		if ($customer->getId() === 0)
			throw new ControlException('cliente não identificado');
	}

	/**
	 *
	 * @param Customer $customer
	 * @param Phone $phone
	 * @throws ControlException
	 */
	private function validateCustomerPhone(Customer $customer, Phone $phone)
	{
		$this->validateCustomer($customer);

		if (!$this->hasRelationship($customer, $phone))
			throw new ControlException('telefone não vinculado ao cliente');
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::addRelationship()
	 * @param Customer $customer
	 * @param Phone $phone
	 */
	public function addRelationship($customer, $phone): bool
	{
		$this->validateCustomer($customer);

		$this->customerPhoneDAO->beginTransaction();
		{
			if (!$this->phoneControl->addPhone($phone) || !$this->customerPhoneDAO->insert($customer, $phone))
				$this->customerPhoneDAO->rollback();
		}
		$this->customerPhoneDAO->commit();

		$customer->getPhones()->add($phone);
		return true;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::setRelationship()
	 * @param Customer $customer
	 * @param Phone $phone
	 */
	public function setRelationship($customer, $phone): bool
	{
		$this->validateCustomerPhone($customer, $phone);

		return $this->phoneControl->setPhone($phone);
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::removeRelationship()
	 * @param Customer $customer
	 * @param Phone $phone
	 */
	public function removeRelationship($customer, $phone): bool
	{
		$this->validateCustomerPhone($customer, $phone);

		if (!$this->phoneDAO->delete($phone))
			return false;

		$customer->getPhones()->removeElement($phone);
		return true;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::getRelationship()
	 * @param Customer $customer
	 * @return Phone
	 */
	public function getRelationship($customer, int $idPhone)
	{
		$this->validateCustomer($customer);
		$phone = $this->phoneControl->getPhone($idPhone);

		if (!$this->hasRelationship($customer, $phone))
			throw new ControlException('telefone não vinculado ao cliente');

		$customer->getPhones()->replace($phone);
		return $phone;

	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::getRelationships()
	 * @param Customer $customer
	 * @return Phones
	 */
	public function getRelationships($customer)
	{
		$this->validateCustomer($customer);
		$phones = $this->customerPhoneDAO->select($customer);
		$customer->setPhones($phones);

		return $phones;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\control\RelationshipControl::hasRelationship()
	 * @param Customer $customer
	 * @param Phone $phone
	 */
	public function hasRelationship($customer, $phone): bool
	{
		return $this->customerPhoneDAO->has($customer, $phone);
	}
}

