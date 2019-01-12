<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @author Andrew
 */
class QuotedProductPriceException extends ApiStatusException
{
	/**
	 *
	 * @return QuotedProductPriceException
	 */
	public static function newInserted(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('não foi possível adicionar a preço de produto cotado', ApiStatus::QUOTED_PRODUCT_PRICE_INSERTED);
	}

	/**
	 *
	 * @return QuotedProductPriceException
	 */
	public static function newDeleted(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('não foi possível excluir o preço de produto cotado', ApiStatus::QUOTED_PRODUCT_PRICE_DELETED);
	}

	/**
	 *
	 * @return QuotedProductPriceException
	 */
	public static function newSelected(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('não foi possível obter a preço de produto cotado', ApiStatus::QUOTED_PRODUCT_PRICE_SELECTED);
	}

	/**
	 * @return QuotedProductPriceException
	 */
	public static function newNameEmpty(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('nome não informado', ApiStatus::QUOTED_PRODUCT_PRICE_NAME_EMPTY);
	}

	/**
	 * @return QuotedProductPriceException
	 */
	public static function newAmountEmpty(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('quantidade não informada', ApiStatus::QUOTED_PRODUCT_PRICE_AMOUNT_EMPTY);
	}

	/**
	 * @return QuotedProductPriceException
	 */
	public static function newPriceEmpty(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('preço não informado', ApiStatus::QUOTED_PRODUCT_PRICE_PRICE_EMPTY);
	}

	/**
	 * @return QuotedProductPriceException
	 */
	public static function newProductNone(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('produto não informado', ApiStatus::QUOTED_PRODUCT_PRICE_PRODUCT_NONE);
	}

	/**
	 * @return QuotedProductPriceException
	 */
	public static function newProviderNone(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('fornecedor não informado', ApiStatus::QUOTED_PRODUCT_PRICE_PROVIDER_NONE);
	}

	/**
	 * @return QuotedProductPriceException
	 */
	public static function newProductPackageNone(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('pacote de produto não informado', ApiStatus::QUOTED_PRODUCT_PRICE_PRODUCT_PACKAGE_NONE);
	}

	/**
	 * @return QuotedProductPriceException
	 */
	public static function newProductInvalid(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('produto inválido', ApiStatus::QUOTED_PRODUCT_PRICE_PRODUCT_INVALID);
	}

	/**
	 * @return QuotedProductPriceException
	 */
	public static function newManufacturerInvalid(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('fabricante inválido', ApiStatus::QUOTED_PRODUCT_PRICE_MANUFACTURER_INVALID);
	}

	/**
	 * @return QuotedProductPriceException
	 */
	public static function newProviderInvalid(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('fornecedor inválido', ApiStatus::QUOTED_PRODUCT_PRICE_PROVIDER_INVALID);
	}

	/**
	 * @return QuotedProductPriceException
	 */
	public static function newProductPackageInvalid(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('pacote de produto inválido', ApiStatus::QUOTED_PRODUCT_PRICE_PRODUCT_PACKAGE_INVALID);
	}

	/**
	 * @return QuotedProductPriceException
	 */
	public static function newProductTypeInvalid(): QuotedProductPriceException
	{
		return new QuotedProductPriceException('tipo de produto inválido', ApiStatus::QUOTED_PRODUCT_PRICE_PRODUCT_TYPE_INVALID);
	}
}

