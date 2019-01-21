<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @author Andrew
 */
class OrderAcceptanceProductException extends ApiStatusException
{
	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newIdentified(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('cotação de produto aceito já identificado', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_IDENTIFIED);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newNotIdentified(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('cotação de produto aceito não identificado', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_NOT_IDENTIFIED);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newInserted(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('não foi possível adicionar a cotação de produto aceito', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_INSERTED);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newUpdated(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('não foi possível atualizar cotação de produto aceito', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_UPDATED);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newDeleted(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('não foi possível excluir a cotação de produto aceito', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_DELETED);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newDeletedAll(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('não foi possível excluir as cotações de produto aceito', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_DELETED_ALL);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newSelected(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('aceitação de produto cotado não encontrado', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_SELECTED);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newAcceptanceEmpty(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('aceitação de pedido não informado', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_ACCEPTANCE_EMPTY);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newNameEmpty(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('nome não informado', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_NAME_EMPTY);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newAcceptanceInvalid(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('aceitação de pedido inválido', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_ACCEPTANCE_INVALID);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newAmountEmpty(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('quantidade do preço de produto cotado não informado', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_AMOUNT_EMPTY);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newPriceEmpty(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('preço de produto cotado não informado', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_PRICE_EMPTY);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newAmountRequestEmpty(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('quantidade solicitada não informada', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_AMOUNT_REQUEST_EMPTY);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newSubpriceEmpty(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('subpreço não informado', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_SUBPRICE_EMPTY);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newProductEmpty(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('produto não informado', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_PRODUCT_EMPTY);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newProviderEmpty(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('subpreço não informado', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_PROVIDER_EMPTY);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newProductPackageEmpty(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('subpreço não informado', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_PACKAGE_EMPTY);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newProductInvalid(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('produto inválido', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_PRODUCT_INVALID);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newProviderInvalid(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('fornecedor inválido', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_PROVIDER_INVALID);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newManufacturerInvalid(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('fabricante inválido', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_MANUFACTURER_INVALID);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newProductPackageInvalid(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('embalagem de produto inválido', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_PACKAGE_INVALID);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newProductTypeInvalid(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('tipo de produto inválido', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_TYPE_INVALID);
	}

	/**
	 * @return OrderAcceptanceProductException
	 */
	public static function newQuotedPriceUsed(): OrderAcceptanceProductException
	{
		return new OrderAcceptanceProductException('preço de produto cotado já utilizado', ApiStatus::ORDER_ACCEPTANCE_PRODUCT_USED);
	}
}

