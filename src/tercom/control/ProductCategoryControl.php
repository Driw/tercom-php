<?php

namespace tercom\control;

use dProject\restful\exception\ApiException;
use tercom\dao\ProductCategoryDAO;
use tercom\entities\ProductCategory;
use tercom\entities\lists\ProductCategories;

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
	 * @param ProductCategory $productCategoryParent
	 * @throws ApiException
	 */
	public function add(ProductCategory $productCategory, ?ProductCategory $productCategoryParent = null)
	{
		if ($productCategory->getId() === 0) {
			if (!$this->productCategoryDAO->insert($productCategory))
				throw new ControlException('não foi possível inserir a categoria de produto');
		} else {
			if (!$this->productCategoryDAO->update($productCategory))
				throw new ControlException('não foi possível atualizar a categoria de produto');
		}

		if ($productCategoryParent !== null)
		{
			if ($productCategoryParent->getId() === $productCategory->getId())
				throw new ControlException('não é possível vincular uma categoria a ela mesma');

			if (!$this->productCategoryDAO->replaceRelationship($productCategory, $productCategoryParent))
				return false;
		}

		return true;
	}

	/**
	 *
	 * @param ProductCategory $productCategory
	 * @throws ApiException
	 * @return bool
	 */
	public function set(ProductCategory $productCategory, ?ProductCategory $productCategoryParent = null): bool
	{
		if ($this->productCategoryDAO->existName($productCategory->getName()))
			throw new ApiException('categoria de produto já definida');

		if ($productCategoryParent !== null)
			$this->productCategoryDAO->replaceRelationship($productCategory, $productCategoryParent);

		return $this->productCategoryDAO->update($productCategory);
	}

	/**
	 *
	 * @param ProductCategory $productCategory
	 * @param int $idProductCategoryType
	 * @return bool
	 */
	public function remove(ProductCategory $productCategory, int $idProductCategoryType): bool
	{
		return	$this->productCategoryDAO->deleteRelationship($productCategory, $idProductCategoryType) &&
				$this->productCategoryDAO->delete($productCategory);
	}

	/**
	 *
	 * @param ProductCategory $productCategoryParent
	 * @param int $idProductCategoryType
	 * @return bool
	 */
	public function removeRelationship(ProductCategory $productCategoryParent, int $idProductCategoryType): bool
	{
		return $this->productCategoryDAO->deleteRelationship($productCategoryParent, $idProductCategoryType);
	}

	/**
	 *
	 * @param int $idProductCategory
	 * @param int $idCategoryType
	 * @throws ControlException
	 * @return ProductCategory
	 */
	public function get(int $idProductCategory, int $idCategoryType = 0): ProductCategory
	{
		if (($productCategory = $this->productCategoryDAO->select($idProductCategory, $idCategoryType)) === null)
			throw new ControlException('categoria de produto não encontrada');

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
	public function getAll(): ProductCategories
	{
		return $this->productCategoryDAO->selectAll();
	}

	/**
	 *
	 * @param ProductCategory $productCategory
	 */
	public function getCategories(ProductCategory $productCategory): ProductCategories
	{
		return $this->productCategoryDAO->selectByCategory($productCategory);
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
	public function avaiable(string $name, int $idProductCategory = 0): ProductCategories
	{
		return $this->productCategoryDAO->existName($name, $idProductCategory);
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

