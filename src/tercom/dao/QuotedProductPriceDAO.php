<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\entities\ProductPrice;
use tercom\entities\QuotedProductPrice;
use tercom\exceptions\QuotedProductPriceException;

/**
 * DAO para Preço de Produto Cotado
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos preços de produto quotado, incluindo todas operações.
 * Estas operações consiste em: adicionar, excluir e selecionar dados dos preços de produto cotados.
 *
 * Os dados de preços de produtos cotados são réplicas dos preços de produtos em algum momento do sistema.
 *
 * @see GenericDAO
 * @see QuotedProductPrice
 * @see QuotedProductPrices
 *
 * @author Andrew
 */
class QuotedProductPriceDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de preços de produto.
	 */
	public const ALL_COLUMNS = ['id', 'idProduct', 'idProvider', 'idManufacturer', 'idProductPackage', 'idProductType', 'name', 'amount', 'price', 'lastUpdate'];
	/**
	 * @var array nome das colunas da tabela de preços de produto.
	 */
	public const CLONE_COLUMNS = ['idProduct', 'idProvider', 'idManufacturer', 'idProductPackage', 'idProductType', 'name', 'amount', 'price', 'lastUpdate'];

	/**
	 * Procedimento interno para validação dos dados de um preço de produto cotado ao inserir.
	 * Preços de produto cotado precisam ter quantidade, preço, produto, fornecedor e embalagem informadas,
	 * fabricante e tipo de produto são informações opcionais e todas são validadas se existem.
	 * @param QuotedProductPrice $quotedProductPrice objeto do tipo preço de produto à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws QuotedProductPriceException caso algum dos dados do preço de produto não estejam de acordo.
	 */
	private function validate(QuotedProductPrice $quotedProductPrice, bool $validateID)
	{
		// NOT NULL
		if (StringUtil::isEmpty($quotedProductPrice->getName())) throw QuotedProductPriceException::newNameEmpty();
		if ($quotedProductPrice->getAmount() === 0) throw QuotedProductPriceException::newAmountEmpty();
		if ($quotedProductPrice->getPrice() === 0.0) throw QuotedProductPriceException::newPriceEmpty();
		if ($quotedProductPrice->getProductId() === 0) throw QuotedProductPriceException::newProductNone();
		if ($quotedProductPrice->getProviderId() === 0) throw QuotedProductPriceException::newProviderNone();
		if ($quotedProductPrice->getProductPackageId() === 0) throw QuotedProductPriceException::newProductPackageNone();

		// FOREIGN KEY
		if (!$this->existProduct($quotedProductPrice->getProductId())) throw QuotedProductPriceException::newProductInvalid();
		if (!$this->existProvider($quotedProductPrice->getProviderId())) throw QuotedProductPriceException::newProviderInvalid();
		if (!$this->existProductPackage($quotedProductPrice->getProductPackageId())) throw QuotedProductPriceException::newProductPackageInvalid();
		if ($quotedProductPrice->getManufacturerId() !== 0)
			if (!$this->existManufacturer($quotedProductPrice->getManufacturerId())) throw QuotedProductPriceException::newManufacturerInvalid();
		if ($quotedProductPrice->getProductTypeId() !== 0)
			if (!$this->existProductType($quotedProductPrice->getProductType())) throw QuotedProductPriceException::newProductTypeInvalid();
	}

	/**
	 * Insere um novo preço de produto no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param ProductPrice $productPrice objeto do tipo preço de produto à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function clone(ProductPrice $productPrice): ?QuotedProductPrice
	{
		$sqlColumnas = implode(', ', self::CLONE_COLUMNS);
		$sql = "INSERT INTO quoted_product_prices ($sqlColumnas)
				SELECT $sqlColumnas FROM product_prices
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productPrice->getId());
		$quotedProductPrice = null;

		if (($result = $query->execute())->isSuccessful())
		{
			$quotedProductPrice = new QuotedProductPrice();
			$quotedProductPrice->fromArray($productPrice->toArray(true));
			$quotedProductPrice->setId($result->getInsertID());
		}

		return $quotedProductPrice;
	}

	/**
	 * Exclui um PREÇO de produto do banco de dados e considera utilizações.
	 * @param QuotedProductPrice $quotedProductPrice objeto do tipo preço de produto à excluir.
	 * @return bool true se excluído ou false caso contrário.
	 */
	public function delete(QuotedProductPrice $quotedProductPrice): bool
	{
		$sql = "DELETE FROM quoted_product_prices
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $quotedProductPrice->getID());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para se obter os dados completos para se selecionar dados de um preço de pruto.
	 * Envolve todos dados referentes ao produto do preço e seus detalhes (unidade e categoria).
	 * @return string aquisição da query completa para seleção de dados para preço de produtos.
	 */
	private function newBasicSelect(): string
	{
		$quotedProductPriceColumns = $this->buildQuery(self::ALL_COLUMNS, 'quoted_product_prices');
		$productColumns = $this->buildQuery(ProductDAO::ALL_COLUMNS, 'products', 'product');
		$productUnitColumns = $this->buildQuery(ProductUnitDAO::ALL_COLUMNS, 'product_units', 'product_productUnit');
		$productCategoryColumns = $this->buildQuery(ProductCategoryDAO::ALL_COLUMNS, 'product_categories', 'product_productCategory');
		$productProviderColumns = $this->buildQuery(ProviderDAO::ALL_COLUMNS, 'providers', 'provider');
		$productManufacturerColumns = $this->buildQuery(ManufacturerDAO::ALL_COLUMNS, 'manufacturers', 'manufacturer');
		$productPackageColumns = $this->buildQuery(ProductPackageDAO::ALL_COLUMNS, 'product_packages', 'productPackage');
		$productTypeColumns = $this->buildQuery(ProductTypeDAO::ALL_COLUMNS, 'product_types', 'productType');

		return "SELECT $quotedProductPriceColumns, $productColumns, $productUnitColumns, $productCategoryColumns, $productProviderColumns,
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
	 * @param int $idQuotedProductPrice código de identificação único do preço de produto.
	 * @return QuotedProductPrice|NULL preço de produto com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idQuotedProductPrice): ?QuotedProductPrice
	{
		$sqlSELECT = $this->newFullSelect();
		$sql = "$sqlSELECT
				WHERE product_prices.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idQuotedProductPrice);

		$result = $query->execute();

		return $this->parseQuotedProductPrice($result);
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
	 * @return QuotedProductPrice|NULL objeto instânciado com dados carregados ou NULL se não houver resultado.
	 */
	private function parseQuotedProductPrice(Result $result): ?QuotedProductPrice
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newQuotedProductPrice($entry);
	}

	/**
	 * Procedimento interno para criar um objeto do tipo preço de produto e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return QuotedProductPrice aquisição de um objeto do tipo preço de produto com dados carregados.
	 */
	private function newQuotedProductPrice(array $entry): QuotedProductPrice
	{
		$this->parseEntry($entry, 'product', 'provider', 'manufacturer', 'productPackage', 'productType');
		$this->parseEntry($entry['product'], 'productUnit', 'productCategory');

		$quotedProductPrice = new QuotedProductPrice();
		$quotedProductPrice->fromArray($entry);

		return $quotedProductPrice;
	}
}
