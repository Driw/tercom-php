<?php

namespace tercom\control;

use tercom\dao\OrderAcceptanceServiceDAO;
use tercom\entities\OrderAcceptance;
use tercom\entities\OrderAcceptanceService;
use tercom\entities\lists\OrderAcceptanceServices;
use tercom\exceptions\OrderAcceptanceServiceException;
use tercom\TercomException;

/**
 * @author Andrew
 */
class OrderAcceptanceServiceControl extends GenericControl
{
	/**
	 * @var OrderAcceptanceServiceDAO
	 */
	private $orderAcceptanceServiceDAO;

	public function __construct()
	{
		$this->orderAcceptanceServiceDAO = new OrderAcceptanceServiceDAO();
	}

	private function validateCustomer(OrderAcceptance $orderAcceptance): void
	{
		if ($orderAcceptance->getCustomerEmployee()->getCustomerProfile()->getCustomerId() !== $this->getCustomerLoggedId())
			throw TercomException::newCustomerInvliad();
	}

	public function add(OrderAcceptance $orderAcceptance, OrderAcceptanceService $orderAcceptanceService): void
	{
		$this->validateCustomer($orderAcceptance);

		if (!$this->orderAcceptanceServiceDAO->insert($orderAcceptance, $orderAcceptanceService))
			throw OrderAcceptanceServiceException::newInserted();
	}

	public function set(OrderAcceptance $orderAcceptance, OrderAcceptanceService $orderAcceptanceService): void
	{
		$this->validateCustomer($orderAcceptance);

		if (!$this->orderAcceptanceServiceDAO->update($orderAcceptanceService))
			throw OrderAcceptanceServiceException::newUpdated();
	}

	public function remove(OrderAcceptance $orderAcceptance, OrderAcceptanceService $orderAcceptanceService): void
	{
		$this->validateCustomer($orderAcceptance);

		if (!$this->orderAcceptanceServiceDAO->delete($orderAcceptanceService))
			throw OrderAcceptanceServiceException::newDeleted();
	}

	public function removeAll(OrderAcceptance $orderAcceptance): void
	{
		$this->validateCustomer($orderAcceptance);

		if (!$this->orderAcceptanceServiceDAO->deleteAll($orderAcceptance))
			throw OrderAcceptanceServiceException::newDeletedAll();
	}

	public function get(OrderAcceptance $orderAcceptance, int $idOrderAcceptanceService): OrderAcceptanceService
	{
		$this->validateCustomer($orderAcceptance);

		if (($orderAcceptanceService = $this->orderAcceptanceServiceDAO->select($idOrderAcceptanceService, $orderAcceptance->getId())) === null)
			throw OrderAcceptanceServiceException::newSelected();

		return $orderAcceptanceService;
	}

	public function getAll(OrderAcceptance $orderAcceptance): OrderAcceptanceServices
	{
		$this->validateCustomer($orderAcceptance);

		return $this->orderAcceptanceServiceDAO->selectByOrderAcceptance($orderAcceptance);
	}
}

