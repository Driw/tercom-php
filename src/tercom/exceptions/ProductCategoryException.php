<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @see ApiStatusException
 * @author Andrew
 */
class ProductCategoryException extends ApiStatusException
{
	/**
	 * @return ProductCategoryException
	 */
	public static function newIdentified(): ProductCategoryException
	{
		return new ProductCategoryException('categoria de produto já identificado', ApiStatus::PRODUCT_CATEGORY_IDENTIFIED);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newNotIdentified(): ProductCategoryException
	{
		return new ProductCategoryException('categoria de produto não identificado', ApiStatus::PRODUCT_CATEGORY_IDENTIFIED);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newNameEmpty(): ProductCategoryException
	{
		return new ProductCategoryException('nome não informado', ApiStatus::PRODUCT_CATEGORY_NAME_EMPTY);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newTypeEmpty(): ProductCategoryException
	{
		return new ProductCategoryException('tipo não informado', ApiStatus::PRODUCT_CATEGORY_TYPE_EMPTY);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newNameUnavaiable(): ProductCategoryException
	{
		return new ProductCategoryException('nome indisponível', ApiStatus::PRODUCT_CATEGORY_NAME_UNAVAIABLE);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newTypeInvalid(): ProductCategoryException
	{
		return new ProductCategoryException('tipo inválido', ApiStatus::PRODUCT_CATEGORY_TYPE_INVALID);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newNotFound(): ProductCategoryException
	{
		return new ProductCategoryException('categoria de produto não encontrada', ApiStatus::PRODUCT_CATEGORY_NOT_FOUND);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newHasUses(): ProductCategoryException
	{
		return new ProductCategoryException('categoria de produto em uso', ApiStatus::PRODUCT_CATEGORY_HAS_USES);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newNotInserted(): ProductCategoryException
	{
		return new ProductCategoryException('não foi possível adicionar a categoria de produto', ApiStatus::PRODUCT_CATEGORY_INSERTED);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newNotUpdated(): ProductCategoryException
	{
		return new ProductCategoryException('não foi possível atualizar a categoria de produto', ApiStatus::PRODUCT_CATEGORY_UPDATED);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newNotDeleted(): ProductCategoryException
	{
		return new ProductCategoryException('não foi possível excluir a categoria de produto', ApiStatus::PRODUCT_CATEGORY_DELETED);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newNotDeletedRelationship(): ProductCategoryException
	{
		return new ProductCategoryException('não foi possível excluir os relacionamentos', ApiStatus::PRODUCT_CATEGORY_DELETED_RELATIONSHIP);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newNotSelected(): ProductCategoryException
	{
		return new ProductCategoryException('categoria de produto não encontrada', ApiStatus::PRODUCT_CATEGORY_SELECTED);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newReplaceRelationship(): ProductCategoryException
	{
		return new ProductCategoryException('não foi possível relacionar as categorias', ApiStatus::PRODUCT_CATEGORY_RELATIONSHIP);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newParentNotIdentified(): ProductCategoryException
	{
		return new ProductCategoryException('categoria de produto à vincular não identificado', ApiStatus::PRODUCT_CATEGORY_PARENT);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newParentInvalid(): ProductCategoryException
	{
		return new ProductCategoryException('categoria de produto à vincular inválida', ApiStatus::PRODUCT_CATEGORY_PARENT_INVALID);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newExistOnRelationship(): ProductCategoryException
	{
		return new ProductCategoryException('categoria de produto em relacionamento', ApiStatus::PRODUCT_CATEGORY_ON_RELATIONSHIP);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newExistOnProduct(): ProductCategoryException
	{
		return new ProductCategoryException('categoria de produto em uso por produtos', ApiStatus::PRODUCT_CATEGORY_ON_PRODUCT);
	}

	/**
	 * @return ProductCategoryException
	 */
	public static function newInvalidType(): ProductCategoryException
	{
		return new ProductCategoryException('categoria de produto não disponibiliza subcategorias', ApiStatus::PRODUCT_CATEGORY_INVALID_TYPE);
	}
}

