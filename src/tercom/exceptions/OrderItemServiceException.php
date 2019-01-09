<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @author Andrew
 */
class OrderItemServiceException extends ApiStatusException
{
	/**
	 *
	 * @return OrderItemServiceException
	 */
	public static function newIdentified(): OrderItemServiceException
	{
		return new OrderItemServiceException('item de serviço já identificado', ApiStatus::ORDER_ITEM_SERVICE_IDENTIFIED);
	}

	/**
	 *
	 * @return OrderItemServiceException
	 */
	public static function newNotIdentified(): OrderItemServiceException
	{
		return new OrderItemServiceException('item de serviço não identificado', ApiStatus::ORDER_ITEM_SERVICE_NOT_IDENTIFIED);
	}

	/**
	 *
	 * @return OrderItemServiceException
	 */
	public static function newInserted(): OrderItemServiceException
	{
		return new OrderItemServiceException('não foi possível adicionar o item de serviço no pedido', ApiStatus::ORDER_ITEM_SERVICE_INSERTED);
	}

	/**
	 *
	 * @return OrderItemServiceException
	 */
	public static function newUpdated(): OrderItemServiceException
	{
		return new OrderItemServiceException('não foi possível atualizar o item de serviço do pedido', ApiStatus::ORDER_ITEM_SERVICE_UPDATED);
	}

	/**
	 *
	 * @return OrderItemServiceException
	 */
	public static function newDeleted(): OrderItemServiceException
	{
		return new OrderItemServiceException('não foi possível excluir o serviço do pedido', ApiStatus::ORDER_ITEM_SERVICE_DELETED);
	}

	/**
	 *
	 * @return OrderItemServiceException
	 */
	public static function newDeletedAll(): OrderItemServiceException
	{
		return new OrderItemServiceException('não foi possível excluir os serviços do pedido', ApiStatus::ORDER_ITEM_SERVICE_DELETED_ALL);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newSelected(): OrderItemProductException
	{
		return new OrderItemProductException('não foi possível obter o serviço do pedido', ApiStatus::ORDER_ITEM_SERVICE_SELECTED);
	}

	/**
	 *
	 * @return OrderItemServiceException
	 */
	public static function newServiceInvalid(): OrderItemServiceException
	{
		return new OrderItemServiceException('serviço não encontrado para item de pedido', ApiStatus::ORDER_ITEM_SERVICE_SERVICE_INVALID);
	}

	/**
	 *
	 * @return OrderItemServiceException
	 */
	public static function newProviderInvalid(): OrderItemServiceException
	{
		return new OrderItemServiceException('fornecedor não encontrado para preferência', ApiStatus::ORDER_ITEM_SERVICE_PROVIDER_INVALID);
	}

	/**
	 *
	 * @return OrderItemServiceException
	 */
	public static function newServiceEmpty(): OrderItemServiceException
	{
		return new OrderItemServiceException('serviço não informado no item do pedido', ApiStatus::ORDER_ITEM_SERVICE_SERVICE_EMPTY);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newExist(): OrderItemProductException
	{
		return new OrderItemProductException('serviço já registrado no pedido', ApiStatus::ORDER_ITEM_SERVICE_EXIST);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newBinded(): OrderItemProductException
	{
		return new OrderItemProductException('serviço não vinculado ao pedido', ApiStatus::ORDER_ITEM_SERVICE_BINDED);
	}
}

