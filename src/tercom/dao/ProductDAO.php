<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\entities\Product;
use tercom\entities\ProductCategory;
use tercom\entities\lists\Products;
use tercom\exceptions\ProductException;

class ProductDAO extends GenericDAO
{
	public const ALL_COLUMNS = ['id', 'name', 'description', 'utility', 'inactive', 'idProductUnit', 'idProductCategory'];

	private function newSelect(): string
	{
		$productQuery = $this->buildQuery(self::ALL_COLUMNS, 'products');
		$productUnitQuery = $this->buildQuery(ProductUnitDAO::ALL_COLUMNS, 'product_units', 'productUnit');
		$productCategoryQuery = $this->buildQuery(ProductCategoryDAO::ALL_COLUMNS, 'product_categories', 'productCategory');

		return "SELECT $productQuery, $productUnitQuery, $productCategoryQuery
				FROM products
				LEFT JOIN product_units ON products.idProductUnit = product_units.id
				LEFT JOIN product_categories ON products.idProductCategory = product_categories.id";
	}

	private function validate(Product $product, bool $validateId)
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($product->getID() === 0)
				throw ProductException::newNotIdentified();
		} else {
			if ($product->getID() !== 0)
				ProductException::newIdentified();
		}

		// NOT NULL
		if (StringUtil::isEmpty($product->getName())) throw ProductException::newNameEmpty();
		if (StringUtil::isEmpty($product->getDescription())) throw ProductException::newDescriptionEmpty();

		// FOREIGN KEY
		if ($product->getProductUnitId() === 0) throw ProductException::newUnitNone();
		if (!$this->existProductUnit($product->getProductUnitId())) throw ProductException::newUnitInvalid();
		if ($product->getProductCategoryId() !== 0)
			if (!$this->existProductCategory($product->getProductCategoryId())) throw ProductException::newCategoryInvalid();

		// UNIQUE KEY
		if ($this->existName($product->getName(), $product->getId())) throw ProductException::newNameUnavaiable();
	}

	public function insert(Product $product): bool
	{
		$this->validate($product, false);

		$sql = "INSERT INTO products (name, description, utility, inactive, idProductUnit, idProductCategory)
				VALUES (?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setAllowNullValue(true);
		$query->setString(1, $product->getName());
		$query->setString(2, $product->getDescription());
		$query->setString(3, $product->getUtility());
		$query->setBoolean(4, $product->isInactive());
		$query->setInteger(5, $product->getProductUnitId());
		$query->setInteger(6, $this->parseNullID($product->getProductCategoryId()));

		if (($result = $query->execute())->isSuccessful())
			$product->setId($result->getInsertID());

		return $product->getId() != 0;
	}

	public function update(Product $product): bool
	{
		$this->validate($product, true);

		$sql = "UPDATE products
				SET name = ?, description = ?, utility = ?, inactive = ?, idProductUnit = ?, idProductCategory = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setAllowNullValue(true);
		$query->setEmptyAsNull(true);
		$query->setString(1, $product->getName());
		$query->setString(2, $product->getDescription());
		$query->setString(3, $product->getUtility());
		$query->setBoolean(4, $product->isInactive());
		$query->setInteger(5, $product->getProductUnitId());
		$query->setInteger(6, $this->parseNullID($product->getProductCategoryId()));
		$query->setInteger(7, $product->getID());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function select(int $idProduct): ?Product
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE products.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProduct);

		$result = $query->execute();

		return $this->parseProduct($result);
	}

	public function selectAll(): Products
	{
		$sql = $this->newSelect();
		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseProducts($result);
	}

	public function selectLikeName(string $name): Products
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE products.name LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$name%");

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	public function selectByProductCategory(int $idProductCategory): Products
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE	products.idProductCategory = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategory);

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	public function selectByProductFamily(int $idProductFamily): Products
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				LEFT JOIN product_category_relationships ON product_category_relationships.idCategoryParent = products.idProductCategory
				WHERE products.idProductCategory = ? AND product_category_relationships.idCategoryType IS NULL";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductFamily);

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	private function selectByCategoryRelationship(int $idProductGroup, int $idProductRelationship): Products
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				INNER JOIN product_category_relationships ON product_category_relationships.idCategory = products.idProductCategory
				WHERE products.idProductCategory = ? AND product_category_relationships.idCategoryType = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductGroup);
		$query->setInteger(2, $idProductRelationship);

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	public function selectByProductGroup(int $idProductGroup): Products
	{
		return $this->selectByCategoryRelationship($idProductGroup, ProductCategory::CATEGORY_GROUP);
	}

	public function selectByProductSubGroup(int $idProductSubGroup): Products
	{
		return $this->selectByCategoryRelationship($idProductSubGroup, ProductCategory::CATEGORY_SUBGROUP);
	}

	public function selectByProductSector(int $idProductSector): Products
	{
		return $this->selectByCategoryRelationship($idProductSector, ProductCategory::CATEGORY_SECTOR);
	}

	public function selectByProvider(int $idProvider, bool $inactives): Products
	{
		$sqlInactive = $inactives ? 'IS NOT NULL' : '= 1';
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				INNER JOIN product_values ON product_values.idProduct = products.id
				WHERE product_values.idProvider = ? AND products.inactive $sqlInactive";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProvider);

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	public function exist(int $idProduct): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM products
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProduct);

		return $this->parseQueryExist($query);
	}

	public function existName(string $name, int $idProduct): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM products
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idProduct);

		return $this->parseQueryExist($query);
	}

	public function existProductUnit(int $idProductUnit): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_units
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductUnit);

		return $this->parseQueryExist($query);
	}

	public function existProductCategory(int $idProductCategory): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_categories
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategory);

		return $this->parseQueryExist($query);
	}

	private function parseProduct(Result $result): ?Product
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newProduct($entry);
	}

	private function parseProducts(Result $result): Products
	{
		$products = new Products();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$product = $this->newProduct($entry);
			$products->add($product);
		}

		return $products;
	}

	private function newProduct(array $entry): Product
	{
		$this->parseEntry($entry, 'productUnit', 'productCategory');

		$product = new Product();
		$product->fromArray($entry);

		return $product;
	}
}

