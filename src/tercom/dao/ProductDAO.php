<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\entities\Customer;
use tercom\entities\Product;
use tercom\entities\ProductCategory;
use tercom\entities\lists\Products;
use tercom\exceptions\ProductException;

/**
 * DAO para Fornecedor
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos fornecedores, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar e selecionar; <b>fornecedores não podem ser excluídos</b>.
 *
 * Fornecedores não podem repetir o CNPJ, portanto cada fornecedor precisa ter um CNPJ único e válido.
 * Razão social e nome fantasia além do CNPJ são os únicos campos obrigatórios, todos os outros são opcionais.
 * Cada fornecedor pode ter até dois telefones que tem seus tipos pré-definidos como comercial e secundário.
 *
 * @see GenericDAO
 * @see Product
 * @see Products
 * @see ProductCategory
 *
 * @author Andrew
 */
class ProductDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de produtos.
	 */
	public const ALL_COLUMNS = ['id', 'name', 'description', 'utility', 'inactive', 'idProductUnit', 'idProductCategory'];

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
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

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelectCustomer(): string
	{
		$productQuery = $this->buildQuery(self::ALL_COLUMNS, 'products');
		$productUnitQuery = $this->buildQuery(ProductUnitDAO::ALL_COLUMNS, 'product_units', 'productUnit');
		$productCategoryQuery = $this->buildQuery(ProductCategoryDAO::ALL_COLUMNS, 'product_categories', 'productCategory');

		return "SELECT $productQuery, $productUnitQuery, $productCategoryQuery, product_customer.idCustom idProductCustomer
				FROM products
				LEFT JOIN product_units ON products.idProductUnit = product_units.id
				LEFT JOIN product_categories ON products.idProductCategory = product_categories.id";
	}

	/**
	 * Procedimento interno para validação dos dados de um produto ao inserir e/ou atualizar.
	 * Produtos não podem ter nome, descrição, unidade de produto não informadas.
	 * Nome deve ser único; Categoria de produto e unidade de produto devem existir.
	 * @param Product $product objeto do tipo produto à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws ProductException caso algum dos dados do produto não estejam de acordo.
	 */
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

	/**
	 * Verifica se um código de identificação de produto personalizado por cliente já exite.
	 * @param Customer $customer objeto do tipo cliente à verificar.
	 * @param Product $product objeto do tipo produto à verificar.
	 */
	public function validateCustomId(Customer $customer, Product $product): void
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_customer
				WHERE idCustomer = ? AND idCustom = ? AND idProduct <> ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setString(2, $product->getIdProductCustomer());
		$query->setInteger(3, $product->getId());

		if ($this->parseQueryExist($query))
			throw ProductException::newCustomerIdExist();
	}

	/**
	 * Insere um novo produto no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param Product $product objeto do tipo produto à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
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

	/**
	 * Atualiza os dados de um produto já existente no banco de dados.
	 * @param Product $product objeto do tipo produto à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
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

	/**
	 * Substitui o código personalizado de um cliente para um produto específico.
	 * @param Customer $customer cliente à vincular a identificação do serviço.
	 * @param Product $product objeto do tipo produto à atualizar.
	 * @return bool true se substituir ou false caso contrário.
	 */
	public function replaceCustomerId(Customer $customer, Product $product): bool
	{
		$this->validate($product, true);
		$this->validateCustomId($customer, $product);

		$sql = "REPLACE product_customer (idProduct, idCustomer, idCustom) VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $product->getId());
		$query->setInteger(2, $customer->getId());
		$query->setString(3, $product->getIdProductCustomer());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	/**
	 * Selecione os dados de um produto através do seu código de identificação único.
	 * @param int $idProduct código de identificação único do produto.
	 * @return Product|NULL produto com os dados carregados ou NULL se não encontrado.
	 */
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

	/**
	 * Seleciona os dados de todos os produtos registrados no banco de dados sem ordenação.
	 * Filtra os produtos para que somente os com código personalizado do cliente.
	 * @return Products aquisição da lista de produtos atualmente registrados.
	 */
	public function selectWithCustomer(Customer $customer): Products
	{
		$sqlSELECT = $this->newSelectCustomer();
		$sql = "$sqlSELECT
				INNER JOIN product_customer ON product_customer.idProduct = products.id
				WHERE product_customer.idCustomer = ?
				ORDER BY products.name";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	/**
	 * Seleciona os dados de todos os produtos registrados no banco de dados sem ordenação.
	 * @return Products aquisição da lista de produtos atualmente registrados.
	 */
	public function selectAll(): Products
	{
		$sql = $this->newSelect();
		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseProducts($result);
	}

	/**
	 * Seleciona os dados dos produtos no banco de dados filtrados pelo nome.
	 * @param string $name nome parcial ou completo à filtrar.
	 * @return Products aquisição da lista de produtos conforme filtro.
	 */
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

	/**
	 * Seleciona os dados dos produtos no banco de dados filtrados pelo nome.
	 * @param string $idProductCutomer cliente produto ID à filtrar.
	 * @param Customer $customer objeto do tipo cliente à filtrar.
	 * @return Products aquisição da lista de produtos conforme filtro.
	 */
	public function selectLikeIdCustom(string $idProductCutomer, Customer $customer): Products
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				INNER JOIN product_customer ON product_customer.idCustomer = ? AND product_customer.idProduct = products.id
				WHERE product_customer.idCustom LIKE ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setString(2, "%$idProductCutomer%");

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	/**
	 * Seleciona os dados dos produtos no banco de dados filtrados pela categoria de produto.
	 * @param int $idProductCategory código de identificação único da categoria de produto à filtrar.
	 * @return Products aquisição da lista de produtos conforme filtro.
	 */
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

	/**
	 * Seleciona os dados dos produtos no banco de dados filtrados pela família do produto.
	 * @param int $idProductFamily código de identificação único da família do produto à filtrar.
	 * @return Products aquisição da lista de produtos conforme filtro.
	 */
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

	/**
	 * Seleciona os dados dos produtos no banco de dados filtrados por uma categoria de produto.
	 * @param int $idProductCategory código de identificação único da categoria de produto à filtrar.
	 * @param int $idProductCategoryType código de identificação único do tipo de categoria do produto.
	 * @return Products aquisição da lista de produtos conforme filtro.
	 */
	private function selectByCategoryRelationship(int $idProductCategory, int $idProductCategoryType): Products
	{
		$sqlSELECT = $this->newSelect();
		$sql = "$sqlSELECT
				INNER JOIN product_category_relationships ON product_category_relationships.idCategory = products.idProductCategory
				WHERE products.idProductCategory = ? AND product_category_relationships.idCategoryType = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategory);
		$query->setInteger(2, $idProductCategoryType);

		$result = $query->execute();

		return $this->parseProducts($result);
	}

	/**
	 * Seleciona os dados dos produtos no banco de dados filtrados por um grupo de produtos.
	 * @param int $idProductGroup código de identificação único do grupo de produtos.
	 * @return Products aquisição da lista de produtos conforme filtro.
	 */
	public function selectByProductGroup(int $idProductGroup): Products
	{
		return $this->selectByCategoryRelationship($idProductGroup, ProductCategory::CATEGORY_GROUP);
	}

	/**
	 * Seleciona os dados dos produtos no banco de dados filtrados por um subgrupo de produtos.
	 * @param int $idProductSubGroup código de identificação único do subgrupo de produtos.
	 * @return Products aquisição da lista de produtos conforme filtro.
	 */
	public function selectByProductSubGroup(int $idProductSubGroup): Products
	{
		return $this->selectByCategoryRelationship($idProductSubGroup, ProductCategory::CATEGORY_SUBGROUP);
	}

	/**
	 * Seleciona os dados dos produtos no banco de dados filtrados por um setor de produtos.
	 * @param int $idProductSector código de identificação único do setor de produtos.
	 * @return Products aquisição da lista de produtos conforme filtro.
	 */
	public function selectByProductSector(int $idProductSector): Products
	{
		return $this->selectByCategoryRelationship($idProductSector, ProductCategory::CATEGORY_SECTOR);
	}

	/**
	 * Seleciona os dados dos produtos no banco de dados filtrados por fornecedor.
	 * @param int $idProvider código de identificação único do fornecedor à filtrar.
	 * @param bool $inactives true para considerar fornecedores inativos ou false caso contrário.
	 * @return Products aquisição da lista de produtos conforme filtro.
	 */
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

	/**
	 * Verifica se um código de identificação único de produto existe.
	 * @param int $idProduct código de identificação único do produto à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function exist(int $idProduct): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM products
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProduct);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se nome de produto já existe em outro produto.
	 * @param string $name nome do produto à verificar.
	 * @param int $idProduct código de identificação do produto à desconsiderar
	 * ou zero caso seja um novo produto.
	 * @return bool true se existir ou false caso contrário.
	 */
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

	/**
	 * Verifica se um cliente produto ID já está sendo usado por outro produto.
	 * @param Product $product objeto do tipo produto à verificar.
	 * @param Customer $customer objeto do tipo cliente à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existIdProductCustomer(Product $product, Customer $customer): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_customer
				WHERE idCustomer = ? AND idCustom = ? AND idProduct <> ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());
		$query->setString(2, $product->getIdProductCustomer());
		$query->setInteger(3, $product->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um código de identificação único de unidade de produto existe.
	 * @param int $idProductUnit código de identificação único da unidade de produto à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existProductUnit(int $idProductUnit): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_units
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductUnit);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um código de identificação único de categoria de produto existe.
	 * @param int $idProductCategory código de identificação único da categoria de produto à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existProductCategory(int $idProductCategory): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_categories
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategory);

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de produto.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Product|NULL objeto do tipo produto com dados carregados ou NULL se não houver resultado.
	 */
	private function parseProduct(Result $result): ?Product
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newProduct($entry);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar os objetos de produto.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Products aquisição da lista de produtos a partir da consulta.
	 */
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

	/**
	 * Procedimento interno para criar um objeto do tipo produto e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return Product aquisição de um objeto do tipo produto com dados carregados.
	 */
	private function newProduct(array $entry): Product
	{
		$this->parseEntry($entry, 'productUnit', 'productCategory');

		$product = new Product();
		$product->fromArray($entry);

		return $product;
	}
}

