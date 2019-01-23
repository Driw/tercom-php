<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @author Andrew
 */
class OrderAcceptanceException extends ApiStatusException
{
	/**
	 * @return OrderAcceptanceException
	 */
	public static function newIdentified(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('aceitação do pedido já identificado', ApiStatus::ORDER_ACCEPTANCE_IDENTIFIED);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newNotIdentified(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('aceitação do pedido não identificado', ApiStatus::ORDER_ACCEPTANCE_NOT_IDENTIFIED);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newInserted(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('não foi possível adicionar a aceitação do pedido', ApiStatus::ORDER_ACCEPTANCE_INSERTED);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newUpdated(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('não foi possível atualizar a aceitação do pedido', ApiStatus::ORDER_ACCEPTANCE_UPDATEED);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newSelected(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('não foi possível obter os dados de aceitação do pedido', ApiStatus::ORDER_ACCEPTANCE_SELECTED);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newOrderEmpty(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('solicitação de cotação não informada', ApiStatus::ORDER_ACCEPTANCE_ORDER_EMPTY);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newOrderInvalid(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('solicitação de cotação inválida', ApiStatus::ORDER_ACCEPTANCE_ORDER_INVALID);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newCustomerEmpty(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('funcionário de cliente não informada', ApiStatus::ORDER_ACCEPTANCE_CUSTOMER_EMPTY);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newCustomerInvalid(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('funcionário de cliente inválida', ApiStatus::ORDER_ACCEPTANCE_CUSTOMER_INVALID);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newTercomEmpty(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('funcionário TERCOM não informada', ApiStatus::ORDER_ACCEPTANCE_TERCOM_EMPTY);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newTercomInvalid(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('funcionário TERCOM inválida', ApiStatus::ORDER_ACCEPTANCE_TERCOM_INVALID);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newAddressEmpty(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('endereço não informado', ApiStatus::ORDER_ACCEPTANCE_ADDRESS_EMPTY);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newAddressInvalid(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('endereço inválido', ApiStatus::ORDER_ACCEPTANCE_ADDRESS_INVALID);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newManage(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('aceitação de pedido não pode mais ser alterada', ApiStatus::ORDER_ACCEPTANCE_MANAGE);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newApproving(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('aceitação de pedido não está em aprovação', ApiStatus::ORDER_ACCEPTANCE_APPROVING);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newApproved(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('aceitação de pedido não está aprovada', ApiStatus::ORDER_ACCEPTANCE_APPROVED);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newRequest(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('aceitação de pedido ainda não foi solicitada', ApiStatus::ORDER_ACCEPTANCE_REQUEST);
	}

	/**
	 * @return OrderAcceptanceException
	 */
	public static function newPaid(): OrderAcceptanceException
	{
		return new OrderAcceptanceException('aceitação de pedido ainda sem confirmação do pagamento', ApiStatus::ORDER_ACCEPTANCE_PAID);
	}
}

