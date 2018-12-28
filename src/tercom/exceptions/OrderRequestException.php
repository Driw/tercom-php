<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @author Andrew
 */
class OrderRequestException extends ApiStatusException
{
	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newIdentified(): OrderRequestException
	{
		return new OrderRequestException('pedido já identificado', ApiStatus::ORDER_REQUEST_IDENTIFIED);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newNotIdentified(): OrderRequestException
	{
		return new OrderRequestException('pedido não identificado', ApiStatus::ORDER_REQUEST_NOT_IDENTIFIED);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newInserted(): OrderRequestException
	{
		return new OrderRequestException('não foi possível adicionar o pedido', ApiStatus::ORDER_REQUEST_INSERTED);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newUpdated(): OrderRequestException
	{
		return new OrderRequestException('não foi possível atualizar o pedido', ApiStatus::ORDER_REQUEST_UPDATED);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newSelected(): OrderRequestException
	{
		return new OrderRequestException('pedido não encontrado', ApiStatus::ORDER_REQUEST_SELECTED);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newCustomerEmployeeEmpty(): OrderRequestException
	{
		return new OrderRequestException('funcionário não informado', ApiStatus::ORDER_REQUEST_CUSTOMER_EMPLOYEE_EMPTY);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newCustomerEmployee(): OrderRequestException
	{
		return new OrderRequestException('funcionário não encontrado', ApiStatus::ORDER_REQUEST_CUSTOMER_EMPLOYEE);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newTercomEmployee(): OrderRequestException
	{
		return new OrderRequestException('funcionário TERCOM não encontrado', ApiStatus::ORDER_REQUEST_TERCOM_EMPLOYEE);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newCustomerInvalid(): OrderRequestException
	{
		return new OrderRequestException('solicitação de pedido inválida para este cliente', ApiStatus::ORDER_REQUEST_CUSTOMER_INVALID);
	}
}

