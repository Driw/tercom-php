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

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newCanceledByCustomer(): OrderRequestException
	{
		return new OrderRequestException('cancelado pelo funcionário do cliente', ApiStatus::ORDER_REQUEST_CANCELED_BY_CUSTOMER);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newCanceledByTercom(): OrderRequestException
	{
		return new OrderRequestException('cancelado pelo funcionário TERCOM', ApiStatus::ORDER_REQUEST_CANCELED_BY_TERCOM);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newCustomerEmployeeError(): OrderRequestException
	{
		return new OrderRequestException('pedido de cotação não gerenciado por você', ApiStatus::ORDER_REQUEST_CUSTOMER_EMPLOYEE_ERROR);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newTercomEmployeeError(): OrderRequestException
	{
		return new OrderRequestException('pedido de cotação não gerenciado por você', ApiStatus::ORDER_REQUEST_TERCOM_EMPLOYEE_ERROR);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newTercomEmployeeSetted(): OrderRequestException
	{
		return new OrderRequestException('pedido de cotação já está sendo cotado por outro funcionário', ApiStatus::ORDER_REQUEST_TERCOM_EMPLOYEE_SETTED);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newNotManagin(): OrderRequestException
	{
		return new OrderRequestException('pedido de cotação não pode mais ter itens gerenciados', ApiStatus::ORDER_REQUEST_NOT_MANAGING);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newNotQueued(): OrderRequestException
	{
		return new OrderRequestException('pedido de cotação não está em fila de espera', ApiStatus::ORDER_REQUEST_NOT_QUEUED);
	}

	/**
	 *
	 * @return OrderRequestException
	 */
	public static function newNotQuoting(): OrderRequestException
	{
		return new OrderRequestException('pedido de cotação não está mais em processo de cotação', ApiStatus::ORDER_REQUEST_NOT_QUOTING);
	}
}

