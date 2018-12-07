<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\ProductCategory;
use tercom\entities\lists\ProductCategories;
use dProject\MySQL\MySQLException;

class ProductCategoryDAO extends GenericDAO
{
	public const ALL_COLUMNS = ['id', 'name'];
	public const ALL_TYPE_COLUMNS = ['id', 'name'];

	private function validate(ProductCategory $productCategory, bool $validateID)
	{
		if ($validateID) {
			if ($productCategory->getId() === 0)
				throw new DAOException('categoria de produto não identificado');
		} else {
			if ($productCategory->getID() !== 0)
				throw new DAOException('categoria de produto já identificado');
		}

		if (empty($productCategory->getName())) throw new DAOException('nome da categoria não definida');
		if ($productCategory->getType() === 0) throw new DAOException('tipo da categoria não definida');
		if ($this->existName($productCategory->getName(), $productCategory->getId())) throw new DAOException('nome da categoria indisponível');
	}

	public function insert(ProductCategory $productCategory): bool
	{
		$this->validate($productCategory, false);
		$sql = "INSERT INTO product_categories (name)
				VALUES (?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $productCategory->getName());

		$result = $query->execute();

		if ($result->isSuccessful())
			$productCategory->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	public function replaceRelationship(ProductCategory $productCategory, ProductCategory $productCategoryParent): bool
	{
		if ($productCategoryParent->getId() === 0)
			throw new DAOException('categoria de produto à vincular não identificado');

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

		$query = $this->createQuery($sql);
		$query->setString(1, $productCategory->getName());
		$query->setInteger(2, $productCategory->getId());

		return ($query->execute())->isSuccessful();
	}

	public function delete(ProductCategory $productCategory): bool
	{
		$sql = "DELETE FROM product_categories
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productCategory->getId());

		try {
			$result = $query->execute();
			return $result->getAffectedRows() === 1;
		} catch (MySQLException $e) {
			switch ($e->getCode())
			{
				case self::ER_ROW_IS_REFERENCED_2:
					throw new DAOException('categoria de produto possui vinculo com outra(s) categoria(s)');
			}
		}
	}

	public function deleteRelationship(ProductCategory $productCategory, int $idProductCategoryType): bool
	{
		$sql = "DELETE FROM product_category_relationships
				WHERE idCategory = ? AND idCategoryType = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productCategory->getId());
		$query->setInteger(2, $idProductCategoryType);

		$result = $query->execute();

		return $result->getAffectedRows() === 1;
	}

	private function newSelect(): string
	{
		$productCategoriesColumns = $this->buildQuery(self::ALL_COLUMNS, 'product_categories');

		return "SELECT $productCategoriesColumns, product_category_types.id type
				FROM product_categories
				LEFT JOIN product_category_relationships ON product_category_relationships.idCategory = product_categories.id
				LEFT JOIN product_category_types ON product_category_types.id = product_category_relationships.idCategoryType";
	}

	public function select(int $idProductCategory, int $idCategoryType = 0): ?ProductCategory
	{
		// Quando é família não vai possuir nenhuma relação de categoria parent, logo o tipo será nulo
		if ($idCategoryType === ProductCategory::CATEGORY_FAMILY)
			$idCategoryType = null;

		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE product_categories.id = ? AND (product_category_types.id = ? OR ? = 0)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategory);
		$query->setInteger(2, $idCategoryType);
		$query->setInteger(3, $idCategoryType);

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

	public function selectByCategory(ProductCategory $productCategory): ProductCategories
	{
		$sqlType = $productCategory->getType() !== 0 ? format('AND product_category_relationships.idCategoryType = ?') : '';
		$sql = "SELECT product_categories.id, product_categories.name, product_category_types.id type
				FROM product_categories
				INNER JOIN product_category_relationships ON product_category_relationships.idCategory = product_categories.id
				INNER JOIN product_category_types ON product_category_types.id = product_category_relationships.idCategoryType
				WHERE product_category_relationships.idCategoryParent = ?
					$sqlType";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productCategory->getId());
		if ($productCategory->getType() !== 0)
		$query->setInteger(2, $productCategory->getType());

		$result = $query->execute();

		return $this->parseProductCategories($result);
	}

	public function selectLikeName(string $name, int $idCategoryType = 0): ProductCategories
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE product_categories.name LIKE ? AND (product_category_types.id = ? OR ? = 0)";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$name%");
		$query->setInteger(2, $idCategoryType);
		$query->setInteger(3, $idCategoryType);

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

		$result = $query->execute();
		$entry = $result->next();

		return intval($entry['qtd']) === 1;
	}

	public function existName(string $name, int $idProductCategory = 0): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_categories
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idProductCategory);

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) > 0;
	}

	public function existRelationship(int $idProductCategory, int $idProductCategoryType): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_category_relationships
				WHERE idCategory = ? AND idCategoryType = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $idProductCategory);
		$query->setInteger(2, $idProductCategoryType);

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) > 0;
	}

	private function parseProductCategory(Result $result): ?ProductCategory
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$productCategory = $this->newProductCategory($array);

		return $productCategory;
	}

	private function parseProductCategories(Result $result): ProductCategories
	{
		$productCategories = new ProductCategories();

		while ($result->hasNext())
		{
			$array = $result->next();
			$productCategory = $this->newProductCategory($array);
			$productCategories->add($productCategory);
		}

		return $productCategories;
	}

	private function newProductCategory(array $array): ProductCategory
	{
		$productCategory = new ProductCategory();
		$productCategory->fromArray($array);

		return $productCategory;
	}
}
