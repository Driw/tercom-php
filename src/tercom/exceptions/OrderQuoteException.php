<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @author Andrew
 */
class OrderQuoteException extends ApiStatusException
{
	/**
	 *
	 * @return OrderQuoteException
	 */
	public static function newIdentified(): OrderQuoteException
	{
		return new OrderQuoteException('cotação do pedido já identificado', ApiStatus::ORDER_QUOTE_IDENTIFIED);
	}

	/**
	 *
	 * @return OrderQuoteException
	 */
	public static function newNotIdentified(): OrderQuoteException
	{
		return new OrderQuoteException('cotação do pedido não identificado', ApiStatus::ORDER_QUOTE_NOT_IDENTIFIED);
	}

	/**
	 *
	 * @return OrderQuoteException
	 */
	public static function newInserted(): OrderQuoteException
	{
		return new OrderQuoteException('não foi possível adicionar a cotação do pedido', ApiStatus::ORDER_QUOTE_INSERTED);
	}

	/**
	 *
	 * @return OrderQuoteException
	 */
	public static function newUpdated(): OrderQuoteException
	{
		return new OrderQuoteException('não foi possível atualizar a cotação do pedido', ApiStatus::ORDER_QUOTE_UPDATED);
	}

	/**
	 *
	 * @return OrderQuoteException
	 */
	public static function newSelected(): OrderQuoteException
	{
		return new OrderQuoteException('não foi possível obter a cotação do pedido', ApiStatus::ORDER_QUOTE_SELECTED);
	}

	/**
	 *
	 * @return OrderQuoteException
	 */
	public static function newAlreadyQuoted(): OrderQuoteException
	{
		return new OrderQuoteException('pedido de cotação já possui possui uma cotação em andamento ou concluída', ApiStatus::ORDER_QUOTE_ALREADY_QUOTED);
	}

	/**
	 *
	 * @return OrderQuoteException
	 */
	public static function newOrderRequestNotFound(): OrderQuoteException
	{
		return new OrderQuoteException('pedido de cotação não encontrado', ApiStatus::ORDER_QUOTE_ORDER_REQUEST_NOT_FOUND);
	}

	/**
	 *
	 * @return OrderQuoteException
	 */
	public static function newOrderRequestNotQueued(): OrderQuoteException
	{
		return new OrderQuoteException('solicitação de pedido não está em fila', ApiStatus::ORDER_QUOTE_ORDER_REQUEST_NOT_QUEUED);
	}

	/**
	 *
	 * @return OrderQuoteException
	 */
	public static function newNotDoing(): OrderQuoteException
	{
		return new OrderQuoteException('pedido de cotação não está em andamento', ApiStatus::ORDER_QUOTE_ORDER_REQUEST_NOT_QUEUED);
	}
}

