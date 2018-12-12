<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @see ApiStatusException
 * @author Andrew
 */
class ProductPackageException extends ApiStatusException
{
	/**
	 * @return ProductUnitException
	 */
	public static function newIdentified(): ProductUnitException
	{
		return new ProductUnitException('embalagem de produto já identificado', ApiStatus::PRODUCT_PACKAGE_IDENTIFIED);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNotIdentified(): ProductUnitException
	{
		return new ProductUnitException('embalagem de produto não identificado', ApiStatus::PRODUCT_PACKAGE_IDENTIFIED);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNameEmpty(): ProductUnitException
	{
		return new ProductUnitException('nome não informado', ApiStatus::PRODUCT_PACKAGE_NAME_EMPTY);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNameUnavaiable(): ProductUnitException
	{
		return new ProductUnitException('nome indisponível', ApiStatus::PRODUCT_PACKAGE_NAME_UNAVAIABLE);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newHasUses(): ProductUnitException
	{
		return new ProductUnitException('embalagem de produto em uso', ApiStatus::PRODUCT_PACKAGE_HAS_USES);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNotInserted(): ProductUnitException
	{
		return new ProductUnitException('não foi possível adicionar a embalagem de produto', ApiStatus::PRODUCT_PACKAGE_INSERTED);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNotUpdated(): ProductUnitException
	{
		return new ProductUnitException('não foi possível atualizar o embalagem de produto', ApiStatus::PRODUCT_PACKAGE_UPDATED);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNotDeleted(): ProductUnitException
	{
		return new ProductUnitException('não foi possível excluir o embalagem de produto', ApiStatus::PRODUCT_PACKAGE_DELETED);
	}

	/**
	 * @return ProductUnitException
	 */
	public static function newNotSelected(): ProductUnitException
	{
		return new ProductUnitException('embalagem de produto não encontrada', ApiStatus::PRODUCT_PACKAGE_SELECTED);
	}
}

