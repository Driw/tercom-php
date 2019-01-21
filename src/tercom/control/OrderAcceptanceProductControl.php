<?php

namespace tercom\control;

use tercom\dao\OrderAcceptanceProductDAO;
use tercom\entities\OrderAcceptance;
use tercom\entities\OrderAcceptanceProduct;
use tercom\entities\lists\OrderAcceptanceProducts;
use tercom\exceptions\OrderAcceptanceProductException;
use tercom\TercomException;

/**
 * @author Andrew
 */
class OrderAcceptanceProductControl extends GenericControl
{
	/**
	 * @var OrderAcceptanceProductDAO
	 */
	private $orderAcceptanceProductDAO;

	public function __construct()
	{
		$this->orderAcceptanceProductDAO = new OrderAcceptanceProductDAO();
	}

	private function validateCustomer(OrderAcceptance $orderAcceptance): void
	{
		if ($orderAcceptance->getCustomerEmployee()->getCustomerProfile()->getCustomerId() !== $this->getCustomerLoggedId())
			throw TercomException::newCustomerInvliad();
	}

	public function add(OrderAcceptance $orderAcceptance, OrderAcceptanceProduct $orderAcceptanceProduct): void
	{
		$this->validateCustomer($orderAcceptance);

		if (!$this->orderAcceptanceProductDAO->insert($orderAcceptance, $orderAcceptanceProduct))
			throw OrderAcceptanceProductException::newInserted();
	}

	public function set(OrderAcceptance $orderAcceptance, OrderAcceptanceProduct $orderAcceptanceProduct): void
	{
		$this->validateCustomer($orderAcceptance);

		if (!$this->orderAcceptanceProductDAO->update($orderAcceptanceProduct))
			throw OrderAcceptanceProductException::newUpdated();
	}

	public function remove(OrderAcceptance $orderAcceptance, OrderAcceptanceProduct $orderAcceptanceProduct): void
	{
		$this->validateCustomer($orderAcceptance);

		if (!$this->orderAcceptanceProductDAO->delete($orderAcceptanceProduct))
			throw OrderAcceptanceProductException::newDeleted();
	}

	public function removeAll(OrderAcceptance $orderAcceptance): void
	{
		$this->validateCustomer($orderAcceptance);

		if (!$this->orderAcceptanceProductDAO->deleteAll($orderAcceptance))
			throw OrderAcceptanceProductException::newDeletedAll();
	}

	public function get(OrderAcceptance $orderAcceptance, int $idOrderAcceptanceProduct): OrderAcceptanceProduct
	{
		$this->validateCustomer($orderAcceptance);

		if (($orderAcceptanceProduct = $this->orderAcceptanceProductDAO->select($idOrderAcceptanceProduct, $orderAcceptance->getId())) === null)
			throw OrderAcceptanceProductException::newSelected();

		return $orderAcceptanceProduct;
	}

	public function getAll(OrderAcceptance $orderAcceptance): OrderAcceptanceProducts
	{
		$this->validateCustomer($orderAcceptance);

		return $this->orderAcceptanceProductDAO->selectByOrderAcceptance($orderAcceptance);
	}
}

