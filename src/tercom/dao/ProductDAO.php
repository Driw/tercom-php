<?php

namespace tercom\dao;

use tercom\entities\Product;
use tercom\entities\lists\Products;
use dProject\MySQL\Result;
use tercom\Functions;

class ProductDAO extends GenericDAO
{
	private function newSelect(): string
	{
		$productColumns = ['id', 'name', 'description', 'inactive', 'idProductUnit', 'idProductFamily', 'idProductGroup', 'idProductSubGroup', 'idProductSector'];
		$productUnitColumns = ['id', 'name', 'shortName'];
		$productFamilyColumns = ['name'];
		$productGroupColumns = ['name'];
		$productSubGroupColumns = ['name'];
		$productSectorColumns = ['name'];
		$productQuery = $this->buildQuery($productColumns, 'products');
		$productUnitQuery = $this->buildQuery($productUnitColumns, 'product_units', 'productUnit');
		$productFamilyQuery = $this->buildQuery($productFamilyColumns, 'product_families', 'productFamily');
		$productGroupQuery = $this->buildQuery($productGroupColumns, 'product_groups', 'productGroup');
		$productSubGroupQuery = $this->buildQuery($productSubGroupColumns, 'product_subgroups', 'productSubGroup');
		$productSectorQuery = $this->buildQuery($productSectorColumns, 'product_sectores', 'productSector');

		return "SELECT $productQuery, $productUnitQuery, $productFamilyQuery, $productGroupQuery, $productSubGroupQuery, $productSectorQuery
				FROM products
				LEFT JOIN product_units ON products.idProductUnit = product_units.id
				LEFT JOIN product_families ON products.idProductFamily = product_families.id
				LEFT JOIN product_groups ON products.idProductGroup = product_groups.id
				LEFT JOIN product_subgroups ON products.idProductSubGroup = product_subgroups.id
				LEFT JOIN product_sectores ON products.idProductSector = product_sectores.id";
	}

	public function insert(Product $product): bool
	{
		$sql = "INSERT INTO products (name, description, utility, inactive, idProductUnit, idProductFamily, idProductGroup, idProductSubGroup, idProductSector)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $product->getName());
		$query->setString(2, $product->getDescription());
		$query->setString(3, $product->getUtility());
		$query->setBoolean(4, $product->isInactive());
		$query->setInteger(5, $product->getUnit()->getID());
		$query->setInteger(6, $this->parseNullID($product->getCategory()->getFamily()->getID()));
		$query->setInteger(7, $this->parseNullID($product->getCategory()->getGroup()->getID()));
		$query->setInteger(8, $this->parseNullID($product->getCategory()->getSubgroup()->getID()));
		$query->setInteger(9, $this->parseNullID($product->getCategory()->getSector()->getID()));
		$query->setAllowNullValue(true);

		$result = $query->execute();

		if ($result->isSuccessful())
			$product->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	public function update(Product $product): bool
	{
		$sql = "UPDATE products
				SET name = ?, description = ?, utility = ?, inactive = ?, idProductUnit = ?, idProductFamily = ?, idProductGroup = ?, idProductSubGroup = ?, idProductSector = ?
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $product->getName());
		$query->setString(2, $product->getDescription());
		$query->setString(3, $product->getUtility());
		$query->setBoolean(4, $product->isInactive());
		$query->setInteger(5, $product->getUnit()->getID());
		$query->setInteger(6, $this->parseNullID($product->getCategory()->getFamily()->getID()));
		$query->setInteger(7, $this->parseNullID($product->getCategory()->getGroup()->getID()));
		$query->setInteger(8, $this->parseNullID($product->getCategory()->getSubgroup()->getID()));
		$query->setInteger(9, $this->parseNullID($product->getCategory()->getSector()->getID()));
		$query->setInteger(10, $product->getID());
		$query->setAllowNullValue(true);
		$query->setEmptyAsNull(true);

		$result = $query->execute();

		return $result->isSuccessful();
	}

	public function select(int $idProduct): ?Product
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE products.id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idProduct);

		$result = $query->execute();

		return $this->parseProduct($result);
	}

	public function selectAll(): Products
	{
		$sql = $this->newSelect();
		$query = $this->mysql->createQuery($sql);
		$result = $query->execute();

		return $this->parseProducts($result);
	}

	public function selectByName(string $name): Products
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE products.name LIKE ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, "%$name%");

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	public function selectByProductFamily(int $idProductFamily): Products
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE products.idProductFamily = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idProductFamily);

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	public function selectByProductGroup(int $idProductGroup): Products
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE products.idProductGroup = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idProductGroup);

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	public function selectByProductSubGroup(int $idProductSubGroup): Products
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE products.idProductSubGroup = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idProductSubGroup);

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	public function selectByProductSector(int $idProductSector): Products
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				WHERE products.idProductSector = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idProductSector);

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	public function existName(string $name, int $idProduct): bool
	{
		$sql = "SELECT COUNT(*) AS qtd
				FROM products
				WHERE name = ? AND id <> ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idProduct);

		$result = $query->execute();
		$products = $result->next();

		return intval($products['qtd']) === 1;
	}

	public function selectByProvider(int $idProvider, bool $inactives): Products
	{
		$sqlInactive = $inactives ? 'IS NOT NULL' : '= 1';
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				INNER JOIN product_values ON product_values.idProduct = products.id
				WHERE product_values.idProvider = ? AND products.inactive $sqlInactive";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idProvider);

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	private function parseProduct(Result $result): ?Product
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$product = $this->newProduct($array);

		return $product;
	}

	private function parseProducts(Result $result): Products
	{
		$products = new Products();

		while ($result->hasNext())
		{
			$array = $result->next();
			$product = $this->newProduct($array);
			$products->add($product);
		}

		return $products;
	}

	private function newProduct(array $array): Product
	{
		$idProductFamily = intval($array['idProductFamily']);
		$idProductGroup = intval($array['idProductGroup']);
		$idProductSubGroup = intval($array['idProductSubGroup']);
		$idProductSector = intval($array['idProductSector']);

		$unitArray = Functions::parseEntrySQL($array, 'productUnit');
		$familyArray = Functions::parseEntrySQL($array, 'productFamily');
		$groupArray = Functions::parseEntrySQL($array, 'productGroup');
		$subGroupArray = Functions::parseEntrySQL($array, 'productSubGroup');
		$sectorArray = Functions::parseEntrySQL($array, 'productSector');

		$product = new Product();
		$product->fromArray($array);
		$product->getUnit()->fromArray($unitArray);

		if ($idProductFamily !== 0) $product->getCategory()->getFamily()->fromArray($familyArray);
		if ($idProductGroup !== 0) $product->getCategory()->getGroup()->fromArray($groupArray);
		if ($idProductSubGroup !== 0) $product->getCategory()->getSubgroup()->fromArray($subGroupArray);
		if ($idProductSector !== 0) $product->getCategory()->getSector()->fromArray($sectorArray);

		return $product;
	}
}

