<?php

namespace tercom\control;

use tercom\dao\ProductCategoryDAO;
use tercom\entities\ProductCategory;
use tercom\entities\lists\ProductCategories;
use tercom\exceptions\ProductCategoryException;

/**
 * @see GenericControl
 * @see ProductCategoryDAO
 * @see ProductCategory
 * @see ProductCategories
 * @author Andrew
 */
class ProductCategoryControl extends GenericControl
{
	/**
	 * @var ProductCategoryDAO
	 */
	private $productCategoryDAO;

	/**
	 *
	 */
	public function __construct()
	{
		$this->productCategoryDAO = new ProductCategoryDAO();
	}

	/**
	 *
	 * @param ProductCategory $productCategory
	 * @throws ProductCategoryException
	 */
	public function add(ProductCategory $productCategory): void
	{
		if ($productCategory->getId() === 0)
			if (!$this->productCategoryDAO->insert($productCategory))
				throw ProductCategoryException::newNotInserted();
	}

	/**
	 *
	 * @param ProductCategory|NULL $productCategoryParent
	 * @param ProductCategory $productCategory
	 */
	public function addRelationship(ProductCategory $productCategoryParent, ProductCategory $productCategory): void
	{
		$this->productCategoryDAO->beginTransaction();
		{
			if ($productCategory->getId() === 0)
				if (!$this->productCategoryDAO->insert($productCategory))
					throw ProductCategoryException::newNotInserted();

			if (!$this->productCategoryDAO->replaceRelationship($productCategory, $productCategoryParent))
			{
				$this->productCategoryDAO->rollback();
				throw ProductCategoryException::newReplaceRelationship();
			}
		}
		$this->productCategoryDAO->commit();
	}

	/**
	 *
	 * @param ProductCategory $productCategory
	 * @throws ProductCategoryException
	 */
	public function set(ProductCategory $productCategory): void
	{
		if (!$this->productCategoryDAO->update($productCategory))
			throw ProductCategoryException::newNotUpdated();
	}

	/**
	 *
	 * @param ProductCategory $productCategoryParent
	 * @param ProductCategory $productCategory
	 * @throws ProductCategoryException
	 */
	public function setRelationship(ProductCategory $productCategoryParent, ProductCategory $productCategory): void
	{
		if (!$this->productCategoryDAO->replaceRelationship($productCategory, $productCategoryParent))
			throw ProductCategoryException::newReplaceRelationship();
	}

	/**
	 *
	 * @param ProductCategory $productCategory
	 * @param int $idProductCategoryType
	 */
	public function remove(ProductCategory $productCategory, int $idProductCategoryType): void
	{
		$this->productCategoryDAO->beginTransaction();
		{
			if ($this->productCategoryDAO->existRelationship($productCategory->getId(), $idProductCategoryType))
				if (!$this->productCategoryDAO->deleteRelationship($productCategory, $idProductCategoryType))
					throw ProductCategoryException::newNotDeletedRelationship();

			if (!$this->productCategoryDAO->delete($productCategory))
			{
				$this->productCategoryDAO->rollback();
				throw ProductCategoryException::newReplaceRelationship();
			}
		}
		$this->productCategoryDAO->commit();
	}

	/**
	 *
	 * @param ProductCategory $productCategoryParent
	 * @param int $idProductCategoryType
	 */
	public function removeRelationship(ProductCategory $productCategoryParent, int $idProductCategoryType): bool
	{
		if (!$this->productCategoryDAO->deleteRelationship($productCategoryParent, $idProductCategoryType))
			throw ProductCategoryException::newReplaceRelationship();
	}

	/**
	 *
	 * @param int $idProductCategory
	 * @param int $idCategoryType
	 * @throws ProductCategoryException
	 * @return ProductCategory
	 */
	public function get(int $idProductCategory, int $idCategoryType = ProductCategory::CATEGORY_NONE): ProductCategory
	{
		if (($productCategory = $this->productCategoryDAO->select($idProductCategory, $idCategoryType)) === null)
			throw ProductCategoryException::newNotSelected();

		return $productCategory;
	}

	public function getByName(string $name): ?ProductCategory
	{
		return $this->productCategoryDAO->selectByName($name);
	}

	/**
	 *
	 * @return ProductCategories
	 */
	public function getAll(int $idProductCategoryType): ProductCategories
	{
		return $this->productCategoryDAO->selectAll($idProductCategoryType);
	}

	/**
	 *
	 * @return ProductCategories
	 */
	public function getAllFamilies(): ProductCategories
	{
		return $this->productCategoryDAO->selectAllFamilies();
	}

	/**
	 *
	 * @param ProductCategory $productCategory
	 * @param int $idProductCategoryType
	 */
	public function getCategories(ProductCategory $productCategory, int $idProductCategoryType): ProductCategories
	{
		if ($idProductCategoryType === 0)
			throw ProductCategoryException::newInvalidType();

		return $this->productCategoryDAO->selectByCategory($productCategory, $idProductCategoryType);
	}

	/**
	 *
	 * @param string $name
	 * @param int $idCategoryType
	 * @return ProductCategories
	 */
	public function searchByname(string $name, int $idCategoryType = 0): ProductCategories
	{
		return $this->productCategoryDAO->selectLikeName($name, $idCategoryType);
	}

	/**
	 *
	 * @param string $name
	 * @param int $idProductCategory
	 * @return ProductCategories
	 */
	public function avaiableName(string $name, int $idProductCategory = 0): bool
	{
		return !$this->productCategoryDAO->existName($name, $idProductCategory);
	}

	/**
	 *
	 * @param int $idProductCategory
	 * @param int $idProductCategoryType
	 * @return bool
	 */
	public function has(int $idProductCategory, int $idProductCategoryType): bool
	{
		return $this->productCategoryDAO->existRelationship($idProductCategory, $idProductCategoryType);
	}
}

