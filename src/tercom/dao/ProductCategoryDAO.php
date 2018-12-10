<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\ProductCategory;
use tercom\entities\lists\ProductCategories;
use tercom\exceptions\ProductCategoryException;
use dProject\MySQL\Query;

class ProductCategoryDAO extends GenericDAO
{
	public const ALL_COLUMNS = ['id', 'name'];

	private function validate(ProductCategory $productCategory, bool $validateId)
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($productCategory->getId() === 0)
				throw ProductCategoryException::newNotIdentified();
		} else {
			if ($productCategory->getID() !== 0)
				throw ProductCategoryException::newIdentified();
		}

		// NOT NULL
		if (empty($productCategory->getName())) throw ProductCategoryException::newNameEmpty();
		if ($productCategory->getType() === 0) throw ProductCategoryException::newTypeEmpty();

		// UNIQUE KEY
		if ($this->existName($productCategory->getName(), $productCategory->getId())) throw ProductCategoryException::newNameUnavaiable();

		// FOREIGN KEY
		if (!$this->existType($productCategory->getType())) throw ProductCategoryException::newTypeInvalid();
	}

	public function insert(ProductCategory $productCategory): bool
	{
		$this->validate($productCategory, false);

		$sql = "INSERT INTO product_categories (name)
				VALUES (?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $productCategory->getName());

		if (($result = $query->execute())->isSuccessful())
			$productCategory->setId($result->getInsertID());

		return $productCategory->getId() !== 0;
	}

	public function replaceRelationship(ProductCategory $productCategory, ProductCategory $productCategoryParent): bool
	{
		if ($productCategoryParent->getId() === 0)
			throw ProductCategoryException::newParentNotIdentified();

		$this->validate($productCategory, true);

		$sql = "REPLACE INTO product_category_relationships (idCategoryParent, idCategory, idCategoryType)
				VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productCategoryParent->getId());
		$query->setInteger(2, $productCategory->getId());
		$query->setInteger(3, $productCategory->getType());

		return ($query->execute())->isSuccessful();
	}

	public function update(ProductCategory $productCategory): bool
	{
		$sql = "UPDATE product_categories
				SET name = ?
				WHERE id = ?";

		$this->validate($productCategory, true);

		$query = $this->createQuery($sql);
		$query->setString(1, $productCategory->getName());
		$query->setInteger(2, $productCategory->getId());

		return ($query->execute())->isSuccessful();
	}

	public function delete(ProductCategory $productCategory): bool
	{
		if ($this->existOnRelationship($productCategory->getId()))
			throw ProductCategoryException::newExistOnRelationship();

		if ($this->existOnProduct($productCategory->getId()))
			throw ProductCategoryException::newExistOnProduct();

		$sql = "DELETE FROM product_categories
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productCategory->getId());

		return ($query->execute())->getAffectedRows() === 1;
	}

	public function deleteRelationship(ProductCategory $productCategory, int $idProductCategoryType): bool
	{
		$sql = "DELETE FROM product_category_relationships
				WHERE idCategory = ? AND idCategoryType = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productCategory->getId());
		$query->setInteger(2, $idProductCategoryType);

		return ($query->execute())->getAffectedRows() > 0;
	}

	private function newSelect(): string
	{
		$productCategoriesColumns = $this->buildQuery(self::ALL_COLUMNS, 'product_categories');

		return "SELECT $productCategoriesColumns, product_category_types.id type
				FROM product_categories
				LEFT JOIN product_category_relationships ON product_category_relationships.idCategory = product_categories.id
				LEFT JOIN product_category_types ON product_category_types.id = product_category_relationships.idCategoryType";
	}

	public function select(int $idProductCategory, int $idProductCategoryType = 0): ?ProductCategory
	{
		// Quando é família não vai possuir nenhuma relação de categoria parent, logo o tipo será nulo
		if ($idProductCategoryType === ProductCategory::CATEGORY_FAMILY)
			$idProductCategoryType = null;

		$sqlType = $idProductCategoryType === null ? 'IS NULL' : '= ?';
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE product_categories.id = ? AND product_category_types.id $sqlType";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategory);
		if ($idProductCategoryType !== null)
		$query->setInteger(2, $idProductCategoryType);

		$result = $query->execute();

		return $this->parseProductCategory($result);
	}

	public function selectByName(string $name): ?ProductCategory
	{
		$sql = "SELECT id, name
				FROM product_categories
				WHERE name = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);

		$result = $query->execute();

		return $this->parseProductCategory($result);
	}

	public function selectAll(): ProductCategories
	{
		$sql = "SELECT id, name
				FROM product_categories";

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseProductCategories($result);
	}

	public function selectByCategory(ProductCategory $productCategory, int $idProductCategory): ProductCategories
	{
		$sqlSelect = $this->newSelect();

		switch ($productCategory->getType())
		{
			case ProductCategory::CATEGORY_FAMILY:
				$sql = "$sqlSelect
						WHERE product_category_relationships.idCategoryParent = ?
							AND (product_category_relationships.idCategoryType = ? OR product_category_relationships.idCategoryType IS NULL)";
				break;

			default:
				$sql = "$sqlSelect
						WHERE product_category_relationships.idCategoryParent = ? AND product_category_relationships.idCategoryType = ?";
				break;
		}

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productCategory->getId());
		$query->setInteger(2, $idProductCategory);

		$result = $query->execute();

		return $this->parseProductCategories($result);
	}

	public function selectLikeName(string $name, int $idProductCategoryType = 0): ProductCategories
	{
		$sqlSelect = $this->newSelect();

		switch ($idProductCategoryType)
		{
			case 0:
			case ProductCategory::CATEGORY_FAMILY:
				$sqlFamily = $idProductCategoryType === ProductCategory::CATEGORY_FAMILY ? 'AND product_category_types.id IS NULL' : '';
				$sql = "$sqlSelect
				WHERE product_categories.name LIKE ? $sqlFamily";
				$query = $this->createQuery($sql);
				$query->setString(1, "%$name%");
				break;

			default:
				$sql = "$sqlSelect
				WHERE product_categories.name LIKE ? AND product_category_types.id = ?";
				$query = $this->createQuery($sql);
				$query->setString(1, "%$name%");
				$query->setInteger(2, $idProductCategoryType);
				break;
		}

		return $this->selectQuery($query);
	}

	private function selectQuery(Query $query): ProductCategories
	{
		$result = $query->execute();

		return $this->parseProductCategories($result);
	}

	public function exist(int $idProductCategory): bool
	{
		$sql = "SELECT COUNT(*) AS qtd
				FROM product_categories
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategory);

		return $this->parseQueryExist($query);
	}

	public function existName(string $name, int $idProductCategory = 0): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_categories
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idProductCategory);

		return $this->parseQueryExist($query);
	}

	public function existRelationship(int $idProductCategory, int $idProductCategoryType): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_category_relationships
				WHERE idCategory = ? AND idCategoryType = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $idProductCategory);
		$query->setInteger(2, $idProductCategoryType);

		return $this->parseQueryExist($query);
	}

	public function existType(int $idProductCategoryType): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_category_types
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategoryType);

		return $this->parseQueryExist($query);
	}

	public function existOnRelationship(int $idProductCategory): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_category_relationships
				WHERE idCategoryParent = ? OR idCategory = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategory);
		$query->setInteger(2, $idProductCategory);

		return $this->parseQueryExist($query);
	}

	public function existOnProduct(int $idProductCategory): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM products
				WHERE idProductCategory = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategory);

		return $this->parseQueryExist($query);
	}

	private function parseProductCategory(Result $result): ?ProductCategory
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newProductCategory($entry);
	}

	private function parseProductCategories(Result $result): ProductCategories
	{
		$productCategories = new ProductCategories();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$productCategory = $this->newProductCategory($entry);
			$productCategories->add($productCategory);
		}

		return $productCategories;
	}

	private function newProductCategory(array $entry): ProductCategory
	{
		if (!isset($entry['type']) || $entry['type'] === null)
			$entry['type'] = ProductCategory::CATEGORY_FAMILY;

		$productCategory = new ProductCategory();
		$productCategory->fromArray($entry);

		return $productCategory;
	}
}
