<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @see ApiStatusException
 * @author Andrew
 */
class ProductUnitException extends ApiStatusException
{
	/**
	 * @return ProductUnitException
	 */
	public static function newIdentified(): ProductUnitException
	{
		return new ProductUnitException('unidade de produto já identificado', ApiStatus::PRODUCT_UNIT_IDENTIFIED);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNotIdentified(): ProductUnitException
	{
		return new ProductUnitException('unidade de produto não identificado', ApiStatus::PRODUCT_UNIT_IDENTIFIED);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNameEmpty(): ProductUnitException
	{
		return new ProductUnitException('nome não informado', ApiStatus::PRODUCT_UNIT_NAME_EMPTY);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newShortNameEmpty(): ProductUnitException
	{
		return new ProductUnitException('abreviação não informada', ApiStatus::PRODUCT_UNIT_SHORT_NAME_EMPTY);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNameUnavaiable(): ProductUnitException
	{
		return new ProductUnitException('nome indisponível', ApiStatus::PRODUCT_UNIT_NAME_UNAVAIABLE);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newShortNameUnavaiable(): ProductUnitException
	{
		return new ProductUnitException('abreviação indisponível', ApiStatus::PRODUCT_UNIT_SHORT_NAME_UNAVAIABLE);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNotFound(): ProductUnitException
	{
		return new ProductUnitException('unidade de produto não encontrada', ApiStatus::PRODUCT_UNIT_NOT_FOUND);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newHasUses(): ProductUnitException
	{
		return new ProductUnitException('unidade de produto em uso', ApiStatus::PRODUCT_UNIT_HAS_USES);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNotInserted(): ProductUnitException
	{
		return new ProductUnitException('não foi possível adicionar a unidade de produto', ApiStatus::PRODUCT_UNIT_INSERTED);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNotUpdated(): ProductUnitException
	{
		return new ProductUnitException('não foi possível atualizar a unidade de produto', ApiStatus::PRODUCT_UNIT_UPDATED);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNotDeleted(): ProductUnitException
	{
		return new ProductUnitException('não foi possível excluir a unidade de produto', ApiStatus::PRODUCT_UNIT_DELETED);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNotSelected(): ProductUnitException
	{
		return new ProductUnitException('unidade de produto não encontrada', ApiStatus::PRODUCT_UNIT_SELECTED);
	}
}

