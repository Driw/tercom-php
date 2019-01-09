<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @author Andrew
 */
class OrderItemProductException extends ApiStatusException
{
	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newIdentified(): OrderItemProductException
	{
		return new OrderItemProductException('item de produto já identificado', ApiStatus::ORDER_ITEM_PRODUCT_IDENTIFIED);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newNotIdentified(): OrderItemProductException
	{
		return new OrderItemProductException('item de produto não identificado', ApiStatus::ORDER_ITEM_PRODUCT_NOT_IDENTIFIED);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newInserted(): OrderItemProductException
	{
		return new OrderItemProductException('não foi possível manter o item de produto do pedido', ApiStatus::ORDER_ITEM_PRODUCT_INSERTED);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newUpdated(): OrderItemProductException
	{
		return new OrderItemProductException('não foi possível manter o produto no pedido', ApiStatus::ORDER_ITEM_PRODUCT_UPDATED);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newDeleted(): OrderItemProductException
	{
		return new OrderItemProductException('não foi possível excluir o produto do pedido', ApiStatus::ORDER_ITEM_PRODUCT_DELETED);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newDeletedAll(): OrderItemProductException
	{
		return new OrderItemProductException('não foi possível excluir os produtos do pedido', ApiStatus::ORDER_ITEM_PRODUCT_DELETED_ALL);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newSelected(): OrderItemProductException
	{
		return new OrderItemProductException('não foi possível obter o produto do pedido', ApiStatus::ORDER_ITEM_PRODUCT_SELECTED);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newProductInvalid(): OrderItemProductException
	{
		return new OrderItemProductException('produto não encontrado para item de pedido', ApiStatus::ORDER_ITEM_PRODUCT_PRODUCT_INVALID);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newProviderInvalid(): OrderItemProductException
	{
		return new OrderItemProductException('fornecedor não encontrado para preferência', ApiStatus::ORDER_ITEM_PRODUCT_PROVIDER_INVALID);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newManufacturerInvalid(): OrderItemProductException
	{
		return new OrderItemProductException('fabricante não encontrado para preferência', ApiStatus::ORDER_ITEM_PRODUCT_MANUFACTURER_INVALID);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newProductEmpty(): OrderItemProductException
	{
		return new OrderItemProductException('produto já listado na solicitação de pedido', ApiStatus::ORDER_ITEM_PRODUCT_PRODUCT_EMPTY);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newExist(): OrderItemProductException
	{
		return new OrderItemProductException('produto já registrado no pedido', ApiStatus::ORDER_ITEM_PRODUCT_EXIST);
	}

	/**
	 *
	 * @return OrderItemProductException
	 */
	public static function newBinded(): OrderItemProductException
	{
		return new OrderItemProductException('produto não vinculado ao pedido', ApiStatus::ORDER_ITEM_PRODUCT_BINDED);
	}
}

