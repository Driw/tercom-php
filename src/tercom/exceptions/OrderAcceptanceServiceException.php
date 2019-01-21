<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @author Andrew
 */
class OrderAcceptanceServiceException extends ApiStatusException
{
	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newIdentified(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('cotação de serviço aceito já identificado', ApiStatus::ORDER_ACCEPTANCE_SERVICE_IDENTIFIED);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newNotIdentified(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('cotação de serviço aceito não identificado', ApiStatus::ORDER_ACCEPTANCE_SERVICE_NOT_IDENTIFIED);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newInserted(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('não foi possível adicionar a cotação de serviço aceito', ApiStatus::ORDER_ACCEPTANCE_SERVICE_INSERTED);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newUpdated(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('não foi possível atualizar cotação de serviço aceito', ApiStatus::ORDER_ACCEPTANCE_SERVICE_UPDATED);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newDeleted(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('não foi possível excluir a cotação de serviço aceito', ApiStatus::ORDER_ACCEPTANCE_SERVICE_DELETED);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newDeletedAll(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('não foi possível excluir as cotações de serviço aceito', ApiStatus::ORDER_ACCEPTANCE_SERVICE_DELETED_ALL);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newSelected(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('aceitação de serviço cotado não encontrado', ApiStatus::ORDER_ACCEPTANCE_SERVICE_SELECTED);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newAcceptanceEmpty(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('aceitação de pedido não informado', ApiStatus::ORDER_ACCEPTANCE_SERVICE_ACCEPTANCE_EMPTY);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newNameEmpty(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('nome não informado', ApiStatus::ORDER_ACCEPTANCE_SERVICE_NAME_EMPTY);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newAcceptanceInvalid(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('aceitação de pedido inválido', ApiStatus::ORDER_ACCEPTANCE_SERVICE_ACCEPTANCE_INVALID);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newPriceEmpty(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('preço de serviço cotado não informado', ApiStatus::ORDER_ACCEPTANCE_SERVICE_PRICE_EMPTY);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newAmountRequestEmpty(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('quantidade solicitada não informada', ApiStatus::ORDER_ACCEPTANCE_SERVICE_AMOUNT_REQUEST_EMPTY);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newSubpriceEmpty(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('subpreço não informado', ApiStatus::ORDER_ACCEPTANCE_SERVICE_SUBPRICE_EMPTY);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newServiceEmpty(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('serviço não informado', ApiStatus::ORDER_ACCEPTANCE_SERVICE_SERVICE_EMPTY);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newProviderEmpty(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('subpreço não informado', ApiStatus::ORDER_ACCEPTANCE_SERVICE_PROVIDER_EMPTY);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newServiceInvalid(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('serviço inválido', ApiStatus::ORDER_ACCEPTANCE_SERVICE_SERVICE_INVALID);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newProviderInvalid(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('fornecedor inválido', ApiStatus::ORDER_ACCEPTANCE_SERVICE_PROVIDER_INVALID);
	}

	/**
	 * @return OrderAcceptanceServiceException
	 */
	public static function newQuotedPriceUsed(): OrderAcceptanceServiceException
	{
		return new OrderAcceptanceServiceException('preço de serviço cotado já utilizado', ApiStatus::ORDER_ACCEPTANCE_SERVICE_USED);
	}
}

