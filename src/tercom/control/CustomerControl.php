<?php

namespace tercom\control;

use dProject\Primitive\StringUtil;
use tercom\Functions;
use tercom\dao\CustomerDAO;
use tercom\entities\Customer;
use tercom\entities\lists\Customers;

/**
 * @see GenericControl
 * @see CustomerAddressControl
 * @see PhoneControl
 * @see CustomerDAO
 * @author Andrew
 */
class CustomerControl extends GenericControl
{
	/**
	 * @var int
	 */
	public const MIN_STATE_REGISTRY_FILTER_LEN = 5;
	/**
	 * @var int
	 */
	public const MIN_CNPJ_FILTER_LEN = 5;

	/**
	 * @var CustomerDAO
	 */
	private $customerDAO;
	/**
	 * @var CustomerAddressControl
	 */
	private $customerAddressControl;
	/**
	 * @var PhoneControl
	 */
	private $phoneControl;

	/**
	 *
	 */
	public function __construct()
	{
		$this->customerDAO = new CustomerDAO();
		$this->customerAddressControl = new CustomerAddressControl();
		$this->phoneControl = new PhoneControl();
	}

	/**
	 *
	 * @param Customer $customer
	 * @return bool
	 */
	public function add(Customer $customer, bool $full = false): bool
	{
		$added = $this->customerDAO->insert($customer);

		if ($full)
			$this->setRelationships($customer);

		return $added;
	}

	/**
	 *
	 * @param Customer $customer
	 * @param bool $full
	 * @return bool
	 */
	public function set(Customer $customer, bool $full = false): bool
	{
		if ($full)
			$this->setRelationships($customer);

		return $this->customerDAO->update($customer);
	}

	/**
	 *
	 * @param Customer $customer
	 * @return int
	 */
	public function setRelationships(Customer $customer): int
	{
		return	$this->customerAddressControl->saveCustomerAddresses($customer) +
				$this->phoneControl->saveCustomerPhones($customer);
	}

	/**
	 *
	 * @param Customer $customer
	 * @return bool
	 */
	public function remove(Customer $customer): bool
	{
		return $this->customerDAO->delete($customer);
	}

	/**
	 *
	 * @param int $idCustomer
	 * @param bool $full
	 * @throws ControlException
	 * @return Customer
	 */
	public function get(int $idCustomer, bool $full = false): Customer
	{
		if (($customer = $this->customerDAO->select($idCustomer)) === null)
			throw new ControlException('cliente não encontrado');

		if ($full)
		{
			$this->customerAddressControl->loadCustomerAddresses($customer);
			$this->phoneControl->loadCustomerPhones($customer);
		}

		return $customer;
	}

	/**
	 *
	 * @param string $cnpj
	 * @throws ControlException
	 * @return Customer
	 */
	public function getByCnpj(string $cnpj): Customer
	{
		if (!Functions::validateCNPJ($cnpj))
			throw new ControlException('CNPJ inválido');

		if (($customer = $this->customerDAO->selectByCnpj($cnpj)) === null)
			throw new ControlException("nenhum cliente encontrado com o CNPJ $cnpj");

		return $customer;
	}

	/**
	 *
	 * @return Customer
	 */
	public function getAll(): Customers
	{
		return $this->customerDAO->selectAll();
	}

	/**
	 *
	 * @param string $stateRegistry
	 * @return Customers
	 */
	public function searchByStateRegistry(string $stateRegistry): Customers
	{
		if (!StringUtil::hasMinLength($stateRegistry, self::MIN_STATE_REGISTRY_FILTER_LEN))
			throw ControlException::new('busca por Inscrição Estadual deve possuir ao menos %d dígitos', self::MIN_STATE_REGISTRY_FILTER_LEN);

		$customers = $this->customerDAO->selectLikeStateRegistry($stateRegistry);

		return $customers;
	}

	/**
	 *
	 * @param string $cnpj
	 * @return Customers
	 */
	public function searchByCnpj(string $cnpj): Customers
	{
		if (!StringUtil::hasMinLength($cnpj, self::MIN_CNPJ_FILTER_LEN))
			throw ControlException::new('busca por CNPJ deve possuir ao menos %d dígitos', $cnpj);

		$customers = $this->customerDAO->selectLikeCnpj($cnpj);

		return $customers;
	}

	/**
	 *
	 * @param string $fantasyName
	 * @return Customers
	 */
	public function searchByFantasyName(string $fantasyName): Customers
	{
		if (!StringUtil::hasMinLength($fantasyName, Customer::MIN_FANTASY_NAME_LEN))
			throw ControlException::new('nome fantasia deve possuir ao menos %d caracteres', Customer::MIN_FANTASY_NAME_LEN);

		$customers = $this->customerDAO->selectLikeFantasyName($fantasyName);

		return $customers;
	}

	/**
	 *
	 * @param string $cnpj
	 * @param int $idCustomer
	 * @return bool
	 */
	public function hasAvaiableCnpj(string $cnpj, int $idCustomer = 0): bool
	{
		if (!Functions::validateCNPJ($cnpj))
			throw new ControlException('CNPJ inválido');

		return !$this->customerDAO->existCnpj($cnpj, $idCustomer);
	}

	/**
	 *
	 * @param string $companyName
	 * @param int $idCustomer
	 * @return bool
	 */
	public function hasAvaiableCompanyName(string $companyName, int $idCustomer = 0): bool
	{
		return !$this->customerDAO->existCompanyName($companyName, $idCustomer);
	}
}

