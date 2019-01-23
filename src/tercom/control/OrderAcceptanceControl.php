<?php

namespace tercom\control;

use tercom\dao\OrderAcceptanceDAO;
use tercom\entities\Customer;
use tercom\entities\CustomerEmployee;
use tercom\entities\OrderAcceptance;
use tercom\entities\OrderQuote;
use tercom\entities\TercomEmployee;
use tercom\entities\lists\OrderAcceptances;
use tercom\exceptions\OrderAcceptanceException;
use tercom\TercomException;

/**
 *
 *
 * @see OrderAcceptance
 * @see OrderAcceptances
 * @see Customer
 * @see CustomerEmployee
 * @see TercomEmployee
 *
 * @author Andrew
 */
class OrderAcceptanceControl extends GenericControl
{
	/**
	 * @var OrderAcceptanceDAO
	 */
	private $orderAcceptanceDAO;
	/**
	 * @var AddressControl
	 */
	private $addressControl;

	/**
	 *
	 */
	public function __construct()
	{
		$this->orderAcceptanceDAO = new OrderAcceptanceDAO();
		$this->addressControl = new AddressControl();
	}

	/**
	 *
	 * @param OrderAcceptance $orderAcceptance
	 * @return bool
	 */
	private function isValidCustomerLogged(OrderAcceptance $orderAcceptance): bool
	{
		return $orderAcceptance->getCustomerEmployee()->getCustomerProfile()->getCustomerId() === $this->getCustomerLoggedId();
	}

	/**
	 *
	 * @param OrderAcceptance $orderAcceptance
	 * @throws OrderAcceptanceException
	 */
	public function add(OrderAcceptance $orderAcceptance): void
	{
		try {

			if ($orderAcceptance->getCustomerEmployee()->getCustomerProfile()->getCustomerId() !== $this->getCustomerLoggedId())
				throw TercomException::newPermissionRestrict();

			$this->orderAcceptanceDAO->beginTransaction();
			{
				$this->addressControl->add($orderAcceptance->getAddress());

				if (!$this->orderAcceptanceDAO->insert($orderAcceptance))
					throw OrderAcceptanceException::newInserted();
			}
			$this->orderAcceptanceDAO->commit();

		} catch (\Exception $e) {
			$this->orderAcceptanceDAO->rollback();
			throw $e;
		}
	}

	/**
	 *
	 * @param OrderAcceptance $orderAcceptance
	 * @throws OrderAcceptanceException
	 */
	public function set(OrderAcceptance $orderAcceptance): void
	{
		if (!$this->orderAcceptanceDAO->update($orderAcceptance))
			throw OrderAcceptanceException::newInserted();
	}

	/**
	 *
	 * @param OrderAcceptance $orderAcceptance
	 * @throws OrderAcceptanceException
	 */
	public function setAddress(OrderAcceptance $orderAcceptance): void
	{
		$this->addressControl->set($orderAcceptance->getAddress());
	}

	/**
	 *
	 * @param int $idOrderAcceptance
	 * @param bool $onlyView
	 * @throws OrderAcceptanceException
	 * @return OrderAcceptance
	 */
	public function get(int $idOrderAcceptance, bool $onlyView = false): OrderAcceptance
	{
		if (($orderAcceptance = $this->orderAcceptanceDAO->select($idOrderAcceptance)) === null)
			throw OrderAcceptanceException::newSelected();

		return $this->getOnlyView($orderAcceptance, $onlyView);
	}

	/**
	 *
	 * @param OrderQuote $orderQuote
	 * @param bool $onlyView
	 * @throws OrderAcceptanceException
	 * @throws TercomException
	 * @return OrderAcceptance
	 */
	public function getByOrderQuote(OrderQuote $orderQuote, bool $onlyView = false): OrderAcceptance
	{
		if (($orderAcceptance = $this->orderAcceptanceDAO->selectByOrderQuote($orderQuote)) === null)
			throw OrderAcceptanceException::newSelected();

		return $this->getOnlyView($orderAcceptance, $onlyView);
	}

	/**
	 *
	 * @param OrderAcceptance $orderAcceptance
	 * @param bool $onlyView
	 * @throws OrderAcceptanceException
	 * @throws TercomException
	 * @return OrderAcceptance
	 */
	private function getOnlyView(OrderAcceptance $orderAcceptance, bool $onlyView): OrderAcceptance
	{
		if (!$this->isTercomManagement())
			if ($orderAcceptance->getCustomerEmployee()->getCustomerProfile()->getCustomerId() !== $this->getCustomerLoggedId())
				throw TercomException::newCustomerInvliad();

		if (!$onlyView && $orderAcceptance->getStatus() !== OrderAcceptance::OAS_APPROVING)
			throw OrderAcceptanceException::newManage();

		return $orderAcceptance;
	}

	/**
	 *
	 * @param Customer $customer
	 * @throws TercomException
	 * @return OrderAcceptances
	 */
	public function getByCustomer(Customer $customer): OrderAcceptances
	{
		if (!$this->isTercomManagement())
			if ($customer->getId() !== $this->getCustomerLoggedId())
				throw TercomException::newPermissionRestrict();

		return $this->orderAcceptanceDAO->selectByCustomer($customer);
	}

	/**
	 *
	 * @param CustomerEmployee $customerEmployee
	 * @throws TercomException
	 * @return OrderAcceptances
	 */
	public function getByCustomerEmployee(CustomerEmployee $customerEmployee): OrderAcceptances
	{
		if (!$this->isTercomManagement())
			if ($customerEmployee->getCustomerProfile()->getCustomerId() !== $this->getCustomerLoggedId())
				throw TercomException::newPermissionRestrict();

		return $this->orderAcceptanceDAO->selectByCustomerEmployee($customerEmployee);
	}

	/**
	 *
	 * @param TercomEmployee $tercomEmployee
	 * @throws TercomException
	 * @return OrderAcceptances
	 */
	public function getByTercomEmployee(TercomEmployee $tercomEmployee): OrderAcceptances
	{
		if (!$this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		return $this->orderAcceptanceDAO->selectByTercomEmployee($tercomEmployee);
	}

	/**
	 *
	 * @throws TercomException
	 * @return OrderAcceptances
	 */
	public function getAll(): OrderAcceptances
	{
		if (!$this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		return $this->orderAcceptanceDAO->selectAll();
	}

	/**
	 *
	 * @param OrderAcceptance $orderAcceptance
	 * @throws OrderAcceptanceException
	 * @throws TercomException
	 */
	public function approved(OrderAcceptance $orderAcceptance): void
	{
		if ($orderAcceptance->getStatus() !== OrderAcceptance::OAS_APPROVING)
			throw OrderAcceptanceException::newApproving();

		if ($this->isTercomManagement() || !$this->isValidCustomerLogged($orderAcceptance))
			throw TercomException::newPermissionRestrict();

		$orderAcceptance->setStatus(OrderAcceptance::OAS_APPROVED);

		if (!$this->orderAcceptanceDAO->updateStatus($orderAcceptance))
			throw OrderAcceptanceException::newInserted();
	}

	/**
	 *
	 * @param OrderAcceptance $orderAcceptance
	 * @throws OrderAcceptanceException
	 * @throws TercomException
	 */
	public function request(OrderAcceptance $orderAcceptance): void
	{
		if ($orderAcceptance->getStatus() !== OrderAcceptance::OAS_APPROVED)
			throw OrderAcceptanceException::newApproved();

		if (!$this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		$orderAcceptance->setStatus(OrderAcceptance::OAS_REQUEST);

		if (!$this->orderAcceptanceDAO->updateStatus($orderAcceptance))
			throw OrderAcceptanceException::newInserted();
	}

	/**
	 *
	 * @param OrderAcceptance $orderAcceptance
	 * @throws OrderAcceptanceException
	 * @throws TercomException
	 */
	public function paid(OrderAcceptance $orderAcceptance): void
	{
		if ($orderAcceptance->getStatus() !== OrderAcceptance::OAS_REQUEST)
			throw OrderAcceptanceException::newRequest();

		if ($this->isTercomManagement() || !$this->isValidCustomerLogged($orderAcceptance))
			throw TercomException::newPermissionRestrict();

		$orderAcceptance->setStatus(OrderAcceptance::OAS_PAID);

		if (!$this->orderAcceptanceDAO->updateStatus($orderAcceptance))
			throw OrderAcceptanceException::newInserted();
	}

	/**
	 *
	 * @param OrderAcceptance $orderAcceptance
	 * @throws OrderAcceptanceException
	 * @throws TercomException
	 */
	public function onTheWay(OrderAcceptance $orderAcceptance): void
	{
		if ($orderAcceptance->getStatus() !== OrderAcceptance::OAS_PAID)
			throw OrderAcceptanceException::newPaid();

		if (!$this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		$orderAcceptance->setStatus(OrderAcceptance::OAS_ON_THE_WAY);

		if (!$this->orderAcceptanceDAO->updateStatus($orderAcceptance))
			throw OrderAcceptanceException::newInserted();
	}
}

