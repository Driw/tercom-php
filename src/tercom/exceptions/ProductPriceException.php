<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @see ApiStatusException
 * @author Andrew
 */
class ProductPriceException extends ApiStatusException
{
	/**
	 * @return ProductPriceException
	 */
	public static function newIdentified(): ProductPriceException
	{
		return new ProductPriceException('preço de produto já identificado', ApiStatus::PRODUCT_PRICE_IDENTIFIED);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newNotIdentified(): ProductPriceException
	{
		return new ProductPriceException('preço de produto não identificado', ApiStatus::PRODUCT_PRICE_IDENTIFIED);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newNotInserted(): ProductPriceException
	{
		return new ProductPriceException('não foi possível adicionar a preço de produto', ApiStatus::PRODUCT_PRICE_INSERTED);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newNotUpdated(): ProductPriceException
	{
		return new ProductPriceException('não foi possível atualizar o preço de produto', ApiStatus::PRODUCT_PRICE_UPDATED);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newNotDeleted(): ProductPriceException
	{
		return new ProductPriceException('não foi possível excluir o preço de produto', ApiStatus::PRODUCT_PRICE_DELETED);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newNotSelected(): ProductPriceException
	{
		return new ProductPriceException('preço de produto não encontrada', ApiStatus::PRODUCT_PRICE_SELECTED);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newHasUses(): ProductPriceException
	{
		return new ProductPriceException('preço de produto em uso', ApiStatus::PRODUCT_PRICE_HAS_USES);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newNameEmpty(): ProductPriceException
	{
		return new ProductPriceException('nome não informado', ApiStatus::PRODUCT_PRICE_NAME_EMPTY);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newNameUnavaiable(): ProductPriceException
	{
		return new ProductPriceException('nome indisponível', ApiStatus::PRODUCT_PRICE_NAME_UNAVAIABLE);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newAmountEmpty(): ProductPriceException
	{
		return new ProductPriceException('quantidade não informada', ApiStatus::PRODUCT_PRICE_AMOUNT_EMPTY);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newPriceEmpty(): ProductPriceException
	{
		return new ProductPriceException('preço não informado', ApiStatus::PRODUCT_PRICE_PRICE_EMPTY);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newProductNone(): ProductPriceException
	{
		return new ProductPriceException('produto não informado', ApiStatus::PRODUCT_PRICE_PRODUCT_NONE);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newManufacturerNone(): ProductPriceException
	{
		return new ProductPriceException('fabricante não informado', ApiStatus::PRODUCT_PRICE_MANUFACTURER_NONE);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newProviderNone(): ProductPriceException
	{
		return new ProductPriceException('fornecedor não informado', ApiStatus::PRODUCT_PRICE_PROVIDER_NONE);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newProductPackageNone(): ProductPriceException
	{
		return new ProductPriceException('pacote de produto não informado', ApiStatus::PRODUCT_PRICE_PRODUCT_PACKAGE_NONE);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newProductInvalid(): ProductPriceException
	{
		return new ProductPriceException('produto inválido', ApiStatus::PRODUCT_PRICE_PRODUCT_INVALID);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newManufacturerInvalid(): ProductPriceException
	{
		return new ProductPriceException('fabricante inválido', ApiStatus::PRODUCT_PRICE_MANUFACTURER_INVALID);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newProviderInvalid(): ProductPriceException
	{
		return new ProductPriceException('fornecedor inválido', ApiStatus::PRODUCT_PRICE_PROVIDER_INVALID);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newProductPackageInvalid(): ProductPriceException
	{
		return new ProductPriceException('pacote de produto inválido', ApiStatus::PRODUCT_PRICE_PRODUCT_PACKAGE_INVALID);
	}

	/**
	 * @return ProductPriceException
	 */
	public static function newProductTypeInvalid(): ProductPriceException
	{
		return new ProductPriceException('tipo de produto inválido', ApiStatus::PRODUCT_PRICE_PRODUCT_TYPE_INVALID);
	}
}

