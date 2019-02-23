<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @see ApiStatusException
 * @author Andrew
 */
class ProductException extends ApiStatusException
{
	/**
	 * @return ProductException
	 */
	public static function newIdentified(): ProductException
	{
		return new ProductException('produto já identificado', ApiStatus::PRODUCT_IDENTIFIED);
	}

	/**
	 * @return ProductException
	 */
	public static function newNotIdentified(): ProductException
	{
		return new ProductException('produto não identificado', ApiStatus::PRODUCT_NOT_IDENTIFIED);
	}

	/**
	 * @return ProductException
	 */
	public static function newNameEmpty(): ProductException
	{
		return new ProductException('nome não informado', ApiStatus::PRODUCT_NAME_EMPTY);
	}

	/**
	 * @return ProductException
	 */
	public static function newDescriptionEmpty(): ProductException
	{
		return new ProductException('descrição não informado', ApiStatus::PRODUCT_DESCRIPTION_EMPTY);
	}

	/**
	 * @return ProductException
	 */
	public static function newUnitNone(): ProductException
	{
		return new ProductException('não foi possível atualizar o produto', ApiStatus::PRODUCT_UNIT_NONE);
	}

	/**
	 * @return ProductException
	 */
	public static function newNameUnavaiable(): ProductException
	{
		return new ProductException('nome indisponível', ApiStatus::PRODUCT_NAME_UNAVAIABLE);
	}

	/**
	 * @return ProductException
	 */
	public static function newUnitInvalid(): ProductException
	{
		return new ProductException('unidade de produto inválida', ApiStatus::PRODUCT_UNIT_INVALID);
	}

	/**
	 * @return ProductException
	 */
	public static function newCategoryInvalid(): ProductException
	{
		return new ProductException('categoria de produto inválida', ApiStatus::PRODUCT_CATEGORY_INVALID);
	}

	/**
	 * @return ProductException
	 */
	public static function newNotInserted(): ProductException
	{
		return new ProductException('não foi possível adicionar o produto', ApiStatus::PRODUCT_NOT_INSERTED);
	}

	/**
	 * @return ProductException
	 */
	public static function newNotUpdated(): ProductException
	{
		return new ProductException('não foi possível atualizar o produto', ApiStatus::PRODUCT_NOT_UPDATED);
	}

	/**
	 * @return ProductException
	 */
	public static function newNotSelected(): ProductException
	{
		return new ProductException('produto não encontrado', ApiStatus::PRODUCT_NOT_SELECTED);
	}

	/**
	 * @return ProductException
	 */
	public static function newCustomerIdExist(): ProductException
	{
		return new ProductException('código de produto personalizado já utilizado', ApiStatus::PRODUCT_CUSTOMER_ID_EXIST);
	}

	/**
	 * @return ProductException
	 */
	public static function newCustomerId(): ProductException
	{
		return new ProductException('não foi possível definir um código de produto exclusivo', ApiStatus::PRODUCT_CUSTOMER_ID);
	}
}

