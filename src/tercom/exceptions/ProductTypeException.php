<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @see ApiStatusException
 * @author Andrew
 */
class ProductTypeException extends ApiStatusException
{
	/**
	 * @return ProductTypeException
	 */
	public static function newIdentified(): ProductTypeException
	{
		return new ProductTypeException('tipo de produto já identificado', ApiStatus::PRODUCT_TYPE_IDENTIFIED);
	}

	/**
	 * @return ProductTypeException
	 */
	public static function newNotIdentified(): ProductTypeException
	{
		return new ProductTypeException('tipo de produto não identificado', ApiStatus::PRODUCT_TYPE_IDENTIFIED);
	}

	/**
	 * @return ProductTypeException
	 */
	public static function newNameEmpty(): ProductTypeException
	{
		return new ProductTypeException('nome não informado', ApiStatus::PRODUCT_TYPE_NAME_EMPTY);
	}

	/**
	 * @return ProductTypeException
	 */
	public static function newNameUnavaiable(): ProductTypeException
	{
		return new ProductTypeException('nome indisponível', ApiStatus::PRODUCT_TYPE_NAME_UNAVAIABLE);
	}

	/**
	 * @return ProductTypeException
	 */
	public static function newHasUses(): ProductTypeException
	{
		return new ProductTypeException('tipo de produto em uso', ApiStatus::PRODUCT_TYPE_HAS_USES);
	}

	/**
	 * @return ProductTypeException
	 */
	public static function newNotInserted(): ProductTypeException
	{
		return new ProductTypeException('não foi possível adicionar a tipo de produto', ApiStatus::PRODUCT_TYPE_INSERTED);
	}

	/**
	 * @return ProductTypeException
	 */
	public static function newNotUpdated(): ProductTypeException
	{
		return new ProductTypeException('não foi possível atualizar o tipo de produto', ApiStatus::PRODUCT_TYPE_UPDATED);
	}

	/**
	 * @return ProductTypeException
	 */
	public static function newNotDeleted(): ProductTypeException
	{
		return new ProductTypeException('não foi possível excluir o tipo de produto', ApiStatus::PRODUCT_TYPE_DELETED);
	}

	/**
	 * @return ProductTypeException
	 */
	public static function newNotSelected(): ProductTypeException
	{
		return new ProductTypeException('tipo de produto não encontrada', ApiStatus::PRODUCT_TYPE_SELECTED);
	}
}

