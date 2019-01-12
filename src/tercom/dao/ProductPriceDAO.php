<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\entities\OrderItemProduct;
use tercom\entities\ProductPrice;
use tercom\entities\lists\ProductPrices;
use tercom\exceptions\ProductPriceException;

/**
 * DAO para Preço de Produto
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos preços de produto, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar, excluir e selecionar dados dos preços de produto.
 *
 * @see GenericDAO
 * @see ProductPrice
 * @see ProductPrices
 *
 * @author Andrew
 */
class ProductPriceDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de preços de produto.
	 */
	public const ALL_COLUMNS = ['id', 'idProduct', 'idProvider', 'idManufacturer', 'idProductPackage', 'idProductType', 'name', 'amount', 'price', 'lastUpdate'];

	/**
	 * Procedimento interno para validação dos dados de um preço de produto ao inserir e/ou atualizar.
	 * Preços de produto precisam ter quantidade, preço, produto, fornecedor e embalagem informadas,
	 * fabricante e tipo de produto são informações opcionais e todas são validadas se existem.
	 * @param ProductPrice $productPrice objeto do tipo preço de produto à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws ProductPriceException caso algum dos dados do preço de produto não estejam de acordo.
	 */
	private function validate(ProductPrice $productPrice, bool $validateID)
	{
		// PRIMARY KEY
		if ($validateID) {
			if ($productPrice->getId() === 0)
				throw ProductPriceException::newNotIdentified();
		} else {
			if ($productPrice->getId() !== 0)
				throw ProductPriceException::newIdentified();
		}

		// NOT NULL
		if (StringUtil::isEmpty($productPrice->getName())) throw ProductPriceException::newNameEmpty();
		if ($productPrice->getAmount() === 0) throw ProductPriceException::newAmountEmpty();
		if ($productPrice->getPrice() === 0.0) throw ProductPriceException::newPriceEmpty();
		if ($productPrice->getProductId() === 0) throw ProductPriceException::newProductNone();
		if ($productPrice->getProviderId() === 0) throw ProductPriceException::newProviderNone();
		if ($productPrice->getProductPackageId() === 0) throw ProductPriceException::newProductPackageNone();

		// FOREIGN KEY
		if (!$this->existProduct($productPrice->getProductId())) throw ProductPriceException::newProductInvalid();
		if (!$this->existProvider($productPrice->getProviderId())) throw ProductPriceException::newProviderInvalid();
		if (!$this->existProductPackage($productPrice->getProductPackageId())) throw ProductPriceException::newProductPackageInvalid();
		if ($productPrice->getManufacturerId() !== 0)
			if (!$this->existManufacturer($productPrice->getManufacturerId())) throw ProductPriceException::newManufacturerInvalid();
		if ($productPrice->getProductTypeId() !== 0)
			if (!$this->existProductType($productPrice->getProductType())) throw ProductPriceException::newProductTypeInvalid();
	}

	/**
	 * Insere um novo preço de produto no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param ProductPrice $productPrice objeto do tipo preço de produto à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(ProductPrice $productPrice): bool
	{
		$sql = "INSERT INTO product_prices (idProduct, idProvider, idManufacturer, idProductPackage, idProductType, name, amount, price, lastUpdate)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productPrice->getProductId());
		$query->setInteger(2, $productPrice->getProviderId());
		$query->setInteger(3, $this->parseNullID($productPrice->getManufacturerId()));
		$query->setInteger(4, $productPrice->getProductPackageId());
		$query->setInteger(5, $this->parseNullID($productPrice->getProductTypeId()));
		$query->setString(6, $productPrice->getName());
		$query->setInteger(7, $productPrice->getAmount());
		$query->setFloat(8, $productPrice->getPrice());
		$query->setDateTime(9, $productPrice->getLastUpdate());

		if (($result = $query->execute())->isSuccessful())
			$productPrice->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 * Atualiza os dados de um preço de produto já existente no banco de dados.
	 * @param ProductPrice $productPrice objeto do tipo preço de produto à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(ProductPrice $productPrice): bool
	{
		$productPrice->getLastUpdate()->setTimestamp(time());

		$sql = "UPDATE product_prices
				SET idProvider = ?, idManufacturer = ?, idProductPackage = ?, idProductType = ?, name = ?, amount = ?, price = ?, lastUpdate = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productPrice->getProviderId());
		$query->setInteger(2, $this->parseNullID($productPrice->getManufacturerId()));
		$query->setInteger(3, $productPrice->getProductPackageId());
		$query->setInteger(4, $this->parseNullID($productPrice->getProductTypeId()));
		$query->setString(5, $productPrice->getName());
		$query->setInteger(6, $productPrice->getAmount());
		$query->setFloat(7, $productPrice->getPrice());
		$query->setDateTime(8, $productPrice->getLastUpdate());
		$query->setInteger(9, $productPrice->getID());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui um PREÇO de produto do banco de dados e considera utilizações.
	 * @param ProductPrice $productPrice objeto do tipo preço de produto à excluir.
	 * @return bool true se excluído ou false caso contrário.
	 */
	public function delete(ProductPrice $productPrice): bool
	{
		$sql = "DELETE FROM product_prices
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productPrice->getID());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para se obter os dados básicos para se selecionar dados de um preço de pruto.
	 * Não envolve  os dados referentes ao produto do preço e seus detalhes (unidade e categoria).
	 * @return string aquisição da query básica para seleção de dados para preço de produtos.
	 */
	private function newBasicSelect(): string
	{
		$productPriceColumns = $this->buildQuery(self::ALL_COLUMNS, 'product_prices');
		$productProviderColumns = $this->buildQuery(ProviderDAO::ALL_COLUMNS, 'providers', 'provider');
		$productManufacturerColumns = $this->buildQuery(ManufacturerDAO::ALL_COLUMNS, 'manufacturers', 'manufacturer');
		$productPackageColumns = $this->buildQuery(ProductPackageDAO::ALL_COLUMNS, 'product_packages', 'productPackage');
		$productTypeColumns = $this->buildQuery(ProductTypeDAO::ALL_COLUMNS, 'product_types', 'productType');

		return "SELECT $productPriceColumns, $productProviderColumns, $productManufacturerColumns, $productPackageColumns, $productTypeColumns
				FROM product_prices
				INNER JOIN products ON product_prices.idProduct = products.id
				INNER JOIN product_packages ON product_prices.idProductPackage = product_packages.id
				LEFT JOIN product_types ON product_prices.idProductType = product_types.id
				LEFT JOIN manufacturers ON product_prices.idManufacturer = manufacturers.id
				INNER JOIN providers ON product_prices.idProvider = providers.id";
	}

	/**
	 * Procedimento interno para se obter os dados completos para se selecionar dados de um preço de pruto.
	 * Envolve todos dados referentes ao produto do preço e seus detalhes (unidade e categoria).
	 * @return string aquisição da query completa para seleção de dados para preço de produtos.
	 */
	private function newFullSelect(): string
	{
		$productPriceColumns = $this->buildQuery(self::ALL_COLUMNS, 'product_prices');
		$productColumns = $this->buildQuery(ProductDAO::ALL_COLUMNS, 'products', 'product');
		$productUnitColumns = $this->buildQuery(ProductUnitDAO::ALL_COLUMNS, 'product_units', 'product_productUnit');
		$productCategoryColumns = $this->buildQuery(ProductCategoryDAO::ALL_COLUMNS, 'product_categories', 'product_productCategory');
		$productProviderColumns = $this->buildQuery(ProviderDAO::ALL_COLUMNS, 'providers', 'provider');
		$productManufacturerColumns = $this->buildQuery(ManufacturerDAO::ALL_COLUMNS, 'manufacturers', 'manufacturer');
		$productPackageColumns = $this->buildQuery(ProductPackageDAO::ALL_COLUMNS, 'product_packages', 'productPackage');
		$productTypeColumns = $this->buildQuery(ProductTypeDAO::ALL_COLUMNS, 'product_types', 'productType');

		return "SELECT $productPriceColumns, $productColumns, $productUnitColumns, $productCategoryColumns, $productProviderColumns,
					$productManufacturerColumns, $productPackageColumns, $productTypeColumns
				FROM product_prices
				INNER JOIN products ON product_prices.idProduct = products.id
				INNER JOIN product_units ON products.idProductUnit = product_units.id
				LEFT JOIN product_categories ON products.idProductCategory = product_categories.id
				INNER JOIN product_packages ON product_prices.idProductPackage = product_packages.id
				LEFT JOIN product_types ON product_prices.idProductType = product_types.id
				LEFT JOIN manufacturers ON product_prices.idManufacturer = manufacturers.id
				INNER JOIN providers ON product_prices.idProvider = providers.id";
	}

	/**
	 * Selecione os dados de um preço de produto através do seu código de identificação único.
	 * @param int $idProductPrice código de identificação único do preço de produto.
	 * @return ProductPrice|NULL preço de produto com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idProductPrice): ?ProductPrice
	{
		$sqlSELECT = $this->newFullSelect();
		$sql = "$sqlSELECT
				WHERE product_prices.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductPrice);

		$result = $query->execute();

		return $this->parseProductPrice($result);
	}

	/**
	 * Selecione os dados dos preços de produto de um produto através do código de identificação único do produto.
	 * @param int $idProduct código de identificação único do produto à selecionar os preços.
	 * @return ProductPrice|NULL preços de produto com os dados carregados.
	 */
	public function selectPrices(int $idProduct): ProductPrices
	{
		$sqlSELECT = $this->newBasicSelect();
		$sql = "$sqlSELECT
				WHERE product_prices.idProduct = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProduct);

		$result = $query->execute();

		return $this->parseProductPrices($result);
	}

	/**
	 * Selecione os dados dos preços de produto de um produto usando o fornecedor como filtro.
	 * @param int $idProduct código de identificação único do produto à selecionar os preços.
	 * @param int $idProvider código de identificação único do fornecedo à filtrar.
	 * @return ProductPrice|NULL preços de produto com os dados carregados.
	 */
	public function selectByProvider(int $idProduct, int $idProvider): ProductPrices
	{
		$sqlSELECT = $this->newBasicSelect();
		$sql = "$sqlSELECT
				WHERE product_prices.idProduct = ? AND (? = 0 OR product_prices.idProvider = ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProduct);
		$query->setInteger(2, $idProvider);
		$query->setInteger(3, $idProvider);

		$result = $query->execute();

		return $this->parseProductPrices($result);
	}

	/**
	 * Seleciona os dados dos preços de produtos disponíveis para um determinado item de produto de pedido.
	 * @param OrderItemProduct $orderItemProduct objeto do tipo item de produto de pedido à filtrar.
	 * @return ProductPrices aquisição da lista de preços de produtos disponíveis.
	 */
	public function selectByItem(OrderItemProduct $orderItemProduct): ProductPrices
	{
		$sqlOrder = $orderItemProduct->isBetterPrice() ? 'ASC' :  'DESC';
		$sqlSelect = $this->newFullSelect();
		$sql = "$sqlSelect
				WHERE	product_prices.idProduct = ?
					AND ? IN (product_prices.idProvider, 0)
					AND ? IN (product_prices.idManufacturer, 0)
				ORDER BY product_prices.price $sqlOrder";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderItemProduct->getProductId());
		$query->setInteger(2, $orderItemProduct->getProviderId());
		$query->setInteger(3, $orderItemProduct->getManufacturerId());

		$result = $query->execute();

		return $this->parseProductPrices($result);
	}

	/**
	 * Selecione os dados dos preços de produto usando como filtro o nome no preço de produto.
	 * @param string $name nome de divulgação do preço de produto.
	 * @return ProductPrice|NULL preços de produto com os dados carregados.
	 */
	public function selectLikeName(string $name): ProductPrices
	{
		$sqlSELECT = $this->newBasicSelect();
		$sql = "$sqlSELECT
				WHERE products.name LIKE ? OR product_prices.name LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$name%");
		$query->setString(2, "%$name%");

		$result = $query->execute();

		return $this->parseProductPrices($result);
	}

	/**
	 * Verifica se um determinado código de identificação de preço de produto existe.
	 * @param int $idProduct código de identificação único do preço de produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existProduct(int $idProduct): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM products
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProduct);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação de fornecedor existe.
	 * @param int $idProvider código de identificação único do fornecedor.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existProvider(int $idProvider): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM providers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProvider);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação de fabricante existe.
	 * @param int $idManufacturer código de identificação único do fabricante.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existManufacturer(int $idManufacturer): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM manufacturers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idManufacturer);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação da embalade de produto existe.
	 * @param int $idProductPackage código de identificação único da embalade de produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existProductPackage(int $idProductPackage): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_packages
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductPackage);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação do tipo de produto existe.
	 * @param int $idProductType código de identificação único do tipo de produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existProductType(int $idProductType): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_types
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductType);

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto do tipo preço de produto.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ProductPrice|NULL objeto instânciado com dados carregados ou NULL se não houver resultado.
	 */
	private function parseProductPrice(Result $result): ?ProductPrice
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newProductPrice($entry);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar os objetos do tipo preço de produto.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ProductPrices aquisição da lista de preços de produto a partir da consulta.
	 */
	private function parseProductPrices(Result $result): ProductPrices
	{
		$productPrices = new ProductPrices();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$productPrice = $this->newProductPrice($entry);
			$productPrices->add($productPrice);
		}

		return $productPrices;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo preço de produto e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return ProductPrice aquisição de um objeto do tipo preço de produto com dados carregados.
	 */
	private function newProductPrice(array $entry): ProductPrice
	{
		$this->parseEntry($entry, 'product', 'provider', 'manufacturer', 'productPackage', 'productType');
		$this->parseEntry($entry['product'], 'productUnit', 'productCategory');

		$productPrice = new ProductPrice();
		$productPrice->fromArray($entry);

		return $productPrice;
	}
}
