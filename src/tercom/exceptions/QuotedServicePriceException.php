<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @author Andrew
 */
class QuotedServicePriceException extends ApiStatusException
{
	/**
	 *
	 * @return QuotedServicePriceException
	 */
	public static function newInserted(): QuotedServicePriceException
	{
		return new QuotedServicePriceException('não foi possível adicionar a preço de serviço cotado', ApiStatus::QUOTED_SERVICE_PRICE_INSERTED);
	}

	/**
	 *
	 * @return QuotedServicePriceException
	 */
	public static function newDeleted(): QuotedServicePriceException
	{
		return new QuotedServicePriceException('não foi possível excluir o preço de serviço cotado', ApiStatus::QUOTED_SERVICE_PRICE_DELETED);
	}

	/**
	 *
	 * @return QuotedServicePriceException
	 */
	public static function newSelected(): QuotedServicePriceException
	{
		return new QuotedServicePriceException('não foi possível obter a preço de serviço cotado', ApiStatus::QUOTED_SERVICE_PRICE_SELECTED);
	}

	/**
	 * @return QuotedServicePriceException
	 */
	public static function newNameEmpty(): QuotedServicePriceException
	{
		return new QuotedServicePriceException('nome não informado', ApiStatus::QUOTED_SERVICE_PRICE_NAME_EMPTY);
	}

	/**
	 * @return QuotedServicePriceException
	 */
	public static function newAmountEmpty(): QuotedServicePriceException
	{
		return new QuotedServicePriceException('quantidade não informada', ApiStatus::QUOTED_SERVICE_PRICE_AMOUNT_EMPTY);
	}

	/**
	 * @return QuotedServicePriceException
	 */
	public static function newPriceEmpty(): QuotedServicePriceException
	{
		return new QuotedServicePriceException('preço não informado', ApiStatus::QUOTED_SERVICE_PRICE_PRICE_EMPTY);
	}

	/**
	 * @return QuotedServicePriceException
	 */
	public static function newServiceNone(): QuotedServicePriceException
	{
		return new QuotedServicePriceException('serviço não informado', ApiStatus::QUOTED_SERVICE_PRICE_SERVICE_NONE);
	}

	/**
	 * @return QuotedServicePriceException
	 */
	public static function newProviderNone(): QuotedServicePriceException
	{
		return new QuotedServicePriceException('fornecedor não informado', ApiStatus::QUOTED_SERVICE_PRICE_PROVIDER_NONE);
	}

	/**
	 * @return QuotedServicePriceException
	 */
	public static function newServiceInvalid(): QuotedServicePriceException
	{
		return new QuotedServicePriceException('serviço inválido', ApiStatus::QUOTED_SERVICE_PRICE_SERVICE_INVALID);
	}

	/**
	 * @return QuotedServicePriceException
	 */
	public static function newProviderInvalid(): QuotedServicePriceException
	{
		return new QuotedServicePriceException('fornecedor inválido', ApiStatus::QUOTED_SERVICE_PRICE_PROVIDER_INVALID);
	}
}

