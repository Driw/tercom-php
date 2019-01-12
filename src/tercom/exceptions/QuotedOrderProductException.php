<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @author Andrew
 */
class QuotedOrderProductException extends ApiStatusException
{
	/**
	 *
	 * @return QuotedOrderProductException
	 */
	public static function newIdentified(): QuotedOrderProductException
	{
		return new QuotedOrderProductException('cotação de produto já identificada', ApiStatus::QUOTED_ORDER_PRODUCT_IDENTIFIED);
	}

	/**
	 *
	 * @return QuotedOrderProductException
	 */
	public static function newNotIdentified(): QuotedOrderProductException
	{
		return new QuotedOrderProductException('cotação de produto não identificada', ApiStatus::QUOTED_ORDER_PRODUCT_NOT_IDENTIFIED);
	}

	/**
	 *
	 * @return QuotedOrderProductException
	 */
	public static function newInserted(): QuotedOrderProductException
	{
		return new QuotedOrderProductException('não foi possível adicionar a cotação de produto', ApiStatus::QUOTED_ORDER_PRODUCT_INSERTED);
	}

	/**
	 *
	 * @return QuotedOrderProductException
	 */
	public static function newUpdated(): QuotedOrderProductException
	{
		return new QuotedOrderProductException('não foi possível atualizar a cotação de produto', ApiStatus::QUOTED_ORDER_PRODUCT_UPDATED);
	}

	/**
	 *
	 * @return QuotedOrderProductException
	 */
	public static function newDeleted(): QuotedOrderProductException
	{
		return new QuotedOrderProductException('não foi possível excluir a cotação de produto', ApiStatus::QUOTED_ORDER_PRODUCT_DELETED);
	}

	/**
	 *
	 * @return QuotedOrderProductException
	 */
	public static function newDeletedAll(): QuotedOrderProductException
	{
		return new QuotedOrderProductException('não foi possível excluir as cotações de produto', ApiStatus::QUOTED_ORDER_PRODUCT_DELETED_ALL);
	}

	/**
	 *
	 * @return QuotedOrderProductException
	 */
	public static function newSelected(): QuotedOrderProductException
	{
		return new QuotedOrderProductException('não foi possível obter a cotação de produto', ApiStatus::QUOTED_ORDER_PRODUCT_SELECTED);
	}

	/**
	 *
	 * @return QuotedOrderProductException
	 */
	public static function newAlreadyUsed(): QuotedOrderProductException
	{
		return new QuotedOrderProductException('preço já utilizado na cotação do produto', ApiStatus::QUOTED_ORDER_PRODUCT_ALREADY_USED);
	}

	/**
	 *
	 * @return QuotedOrderProductException
	 */
	public static function newItemInvalid(): QuotedOrderProductException
	{
		return new QuotedOrderProductException('item de produto não encontrado para cotação', ApiStatus::QUOTED_ORDER_PRODUCT_ITEM_INVALID);
	}

	/**
	 *
	 * @return QuotedOrderProductException
	 */
	public static function newPriceInvalid(): QuotedOrderProductException
	{
		return new QuotedOrderProductException('preço de produto não encontrado para cotação', ApiStatus::QUOTED_ORDER_PRODUCT_PRICE_INVALID);
	}

	/**
	 *
	 * @return QuotedOrderProductException
	 */
	public static function newPriceError(): QuotedOrderProductException
	{
		return new QuotedOrderProductException('preço de produto cotado não corresponde ao produto em cotação', ApiStatus::QUOTED_ORDER_PRODUCT_PRICE_ERROR);
	}

	/**
	 *
	 * @return QuotedOrderProductException
	 */
	public static function newOrderRequest(): QuotedOrderProductException
	{
		return new QuotedOrderProductException('solicitação de pedido de cotação não possui esse item', ApiStatus::QUOTED_ORDER_PRODUCT_ORDER_REQUEST);
	}
}

