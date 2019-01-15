<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @author Andrew
 */
class QuotedOrderServiceException extends ApiStatusException
{
	/**
	 *
	 * @return QuotedOrderServiceException
	 */
	public static function newIdentified(): QuotedOrderServiceException
	{
		return new QuotedOrderServiceException('cotação de serviço já identificada', ApiStatus::QUOTED_ORDER_SERVICE_IDENTIFIED);
	}

	/**
	 *
	 * @return QuotedOrderServiceException
	 */
	public static function newNotIdentified(): QuotedOrderServiceException
	{
		return new QuotedOrderServiceException('cotação de serviço não identificada', ApiStatus::QUOTED_ORDER_SERVICE_NOT_IDENTIFIED);
	}

	/**
	 *
	 * @return QuotedOrderServiceException
	 */
	public static function newInserted(): QuotedOrderServiceException
	{
		return new QuotedOrderServiceException('não foi possível adicionar a cotação de serviço', ApiStatus::QUOTED_ORDER_SERVICE_INSERTED);
	}

	/**
	 *
	 * @return QuotedOrderServiceException
	 */
	public static function newUpdated(): QuotedOrderServiceException
	{
		return new QuotedOrderServiceException('não foi possível atualizar a cotação de serviço', ApiStatus::QUOTED_ORDER_SERVICE_UPDATED);
	}

	/**
	 *
	 * @return QuotedOrderServiceException
	 */
	public static function newDeleted(): QuotedOrderServiceException
	{
		return new QuotedOrderServiceException('não foi possível excluir a cotação de serviço', ApiStatus::QUOTED_ORDER_SERVICE_DELETED);
	}

	/**
	 *
	 * @return QuotedOrderServiceException
	 */
	public static function newDeletedAll(): QuotedOrderServiceException
	{
		return new QuotedOrderServiceException('não foi possível excluir as cotações de serviço', ApiStatus::QUOTED_ORDER_SERVICE_DELETED_ALL);
	}

	/**
	 *
	 * @return QuotedOrderServiceException
	 */
	public static function newSelected(): QuotedOrderServiceException
	{
		return new QuotedOrderServiceException('não foi possível obter a cotação de serviço', ApiStatus::QUOTED_ORDER_SERVICE_SELECTED);
	}

	/**
	 *
	 * @return QuotedOrderServiceException
	 */
	public static function newAlreadyUsed(): QuotedOrderServiceException
	{
		return new QuotedOrderServiceException('preço já utilizado na cotação do serviço', ApiStatus::QUOTED_ORDER_SERVICE_ALREADY_USED);
	}

	/**
	 *
	 * @return QuotedOrderServiceException
	 */
	public static function newItemInvalid(): QuotedOrderServiceException
	{
		return new QuotedOrderServiceException('item de serviço não encontrado para cotação', ApiStatus::QUOTED_ORDER_SERVICE_ITEM_INVALID);
	}

	/**
	 *
	 * @return QuotedOrderServiceException
	 */
	public static function newPriceInvalid(): QuotedOrderServiceException
	{
		return new QuotedOrderServiceException('preço de serviço não encontrado para cotação', ApiStatus::QUOTED_ORDER_SERVICE_PRICE_INVALID);
	}

	/**
	 *
	 * @return QuotedOrderServiceException
	 */
	public static function newPriceError(): QuotedOrderServiceException
	{
		return new QuotedOrderServiceException('preço de serviço cotado não corresponde ao serviço em cotação', ApiStatus::QUOTED_ORDER_SERVICE_PRICE_ERROR);
	}

	/**
	 *
	 * @return QuotedOrderServiceException
	 */
	public static function newOrderRequest(): QuotedOrderServiceException
	{
		return new QuotedOrderServiceException('solicitação de pedido de cotação não possui esse item', ApiStatus::QUOTED_ORDER_SERVICE_ORDER_REQUEST);
	}
}

