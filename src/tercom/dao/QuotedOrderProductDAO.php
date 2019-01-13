<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\OrderItemProduct;
use tercom\entities\OrderRequest;
use tercom\entities\QuotedOrderProduct;
use tercom\entities\lists\QuotedOrderProducts;
use tercom\exceptions\QuotedOrderProductException;

/**
 * DAO para Cotação de Produto de Pedido
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados as cotações de produto de pedido, incluindo todas operações.
 * Estas operações consiste em: adicionar, excluir e selecionar, <b>não há necessidade de atualizar</b>.
 *
 * Cotação de produto de pedido precisa ter apenas um item de produto de pedido existente.
 *
 * @see GenericDAO
 * @see OrderItemProduct
 * @see OrderRequest
 * @see QuotedOrderProduct
 * @see QuotedOrderProducts
 *
 * @author Andrew
 */
class QuotedOrderProductDAO extends GenericDAO
{
	/**
	 * @var array vetor com o nome das colunas da tabela de cotação de produto de pedido.
	 */
	public const ALL_COLUMNS = ['id', 'idOrderItemProduct', 'idQuotedProductPrice', 'observations'];

	/**
	 * Procedimento interno para validação dos dados de uma cotação de produto de pedido ao inserir.
	 * Cotação de produto de pedido precisa ter apenas um item de produto de pedido existente.
	 * @param QuotedOrderProduct $quotedOrderProduct objeto do tipo cotação de produto de pedido à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws QuotedOrderProductException caso algum dos dados da cotação de produto de pedido não estejam de acordo.
	 */
	public function validate(QuotedOrderProduct $quotedOrderProduct, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($quotedOrderProduct->getId() === 0)
				throw QuotedOrderProductException::newNotIdentified();
		} else {
			if ($quotedOrderProduct->getId() !== 0)
				throw QuotedOrderProductException::newIdentified();
		}

		// FOREIGN KEY
		if (!$this->existOrderItemProduct($quotedOrderProduct->getOrderItemProduct())) throw QuotedOrderProductException::newItemInvalid();
	}

	/**
	 * Insere uma nova cotação de produto de pedido no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param QuotedOrderProduct $quotedOrderProduct objeto do tipo cotação de produto de pedido à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(QuotedOrderProduct $quotedOrderProduct): bool
	{
		$this->validate($quotedOrderProduct, false);

		$sql = "INSERT INTO quoted_order_products (idOrderItemProduct, idQuotedProductPrice, observations)
				VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $quotedOrderProduct->getOrderItemProductId());
		$query->setInteger(2, $quotedOrderProduct->getQuotedProductPriceId());
		$query->setString(3, $quotedOrderProduct->getObservations());

		if (($result = $query->execute())->isSuccessful())
			$quotedOrderProduct->setId($result->getInsertID());

		return $quotedOrderProduct->getId() !== 0;
	}

	/**
	 * Exclui uma cotação de produto de pedido do banco de dados.
	 * @param QuotedOrderProduct $quotedOrderProduct objeto do tipo cotação de produto de pedido à excluir.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function delete(QuotedOrderProduct $quotedOrderProduct): bool
	{
		$sql = "DELETE FROM quoted_order_products
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $quotedOrderProduct->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui todas as cotações feitas a partir de um item de produto de pedido para cotação.
	 * @param OrderRequest $orderRequest objeto do tipo pedido de solicitação de cotação do item.
	 * @param OrderItemProduct $orderItemProduct objeto do tipo item de produto de pedido à excluir.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function deleteAll(OrderRequest $orderRequest, OrderItemProduct $orderItemProduct): bool
	{
		$sql = "DELETE quoted_order_products
				FROM quoted_order_products
				INNER JOIN order_item_products ON order_item_products.id = quoted_order_products.idOrderItemProduct
				WHERE quoted_order_products.idOrderItemProduct = ? AND order_item_products.idOrderRequest = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderItemProduct->getId());
		$query->setInteger(2, $orderRequest->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		$quotedOrderProductColumns = $this->buildQuery(self::ALL_COLUMNS, 'quoted_order_products');
		$orderItemProductColumns = $this->buildQuery(OrderItemProductDAO::ALL_COLUMNS, 'order_item_products', 'orderItemProduct');
		$quotedProductPriceColumns = $this->buildQuery(QuotedProductPriceDAO::ALL_COLUMNS, 'quoted_product_prices', 'quotedProductPrice');
		$productColumns = $this->buildQuery(ProductDAO::ALL_COLUMNS, 'products', 'quotedProductPrice_product');

		return "SELECT $quotedOrderProductColumns, $orderItemProductColumns, $quotedProductPriceColumns, $productColumns
				FROM quoted_order_products
				INNER JOIN order_item_products ON order_item_products.id = quoted_order_products.idOrderItemProduct
				INNER JOIN quoted_product_prices ON quoted_product_prices.id = quoted_order_products.idQuotedProductPrice
				INNER JOIN products ON products.id = quoted_product_prices.idProduct";
	}

	/**
	 * Seleciona os dados de uma cotação de produto de pedido através do código de identificação.
	 * @param int $idQuotedOrderProduct código de identificação único da cotação de produto de pedido.
	 * @return QuotedOrderProduct|NULL aquisição da cotação de produto de pedido selecionado.
	 */
	public function select(int $idQuotedOrderProduct): ?QuotedOrderProduct
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE quoted_order_products.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idQuotedOrderProduct);

		$result = $query->execute();

		return $this->parseQuotedOrderProduct($result);
	}

	/**
	 * Seleciona os dados de todas as cotações de produto de pedido de um item de produto de pedido.
	 * @param OrderItemProduct $orderItemProduct objeto do tipo item de produto de pedido à considerar.
	 * @return QuotedOrderProducts aquisição da lista das cotações de produto de pedido feita do item.
	 */
	public function selectAll(OrderItemProduct $orderItemProduct): QuotedOrderProducts
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE quoted_order_products.idOrderItemProduct = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderItemProduct->getId());

		$result = $query->execute();

		return $this->parseQuotedOrderProducts($result);
	}

	private function existOrderItemProduct(OrderItemProduct $orderItemProduct): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_categories
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderItemProduct->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se uma determinada cotação de produto de pedido pertence a uma solicitação de pedido de cotação.
	 * @param OrderRequest $orderRequest objeto do tipo solicitação de pedido de cotação à considerar.
	 * @param QuotedOrderProduct $quotedOrderProduct objeto do tipo cotação de produto de pedido à validar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existOnOrderRequest(OrderRequest $orderRequest, QuotedOrderProduct $quotedOrderProduct): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM quoted_order_products
				INNER JOIN quoted_product_prices ON quoted_product_prices.id = quoted_order_products.idQuotedProductPrice
				INNER JOIN order_item_products ON order_item_products.id = quoted_order_products.idOrderItemProduct
				WHERE order_item_products.idOrderRequest = ? AND quoted_order_products.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());
		$query->setInteger(2, $quotedOrderProduct->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de cotação de produto de pedido.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return QuotedOrderProduct|NULL objeto do tipo cotação de produto de pedido com dados carregados ou NULL se não houver resultado.
	 */
	private function parseQuotedOrderProduct(Result $result): ?QuotedOrderProduct
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newQuotedOrderProduct($entry);
	}

	/**
	 * Procedimento inerno para analisar o resultado de uma consulta e criar as cotações de produto de pedido.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return QuotedOrderProducts aquisição da lista de cotação de produto de pedido a partir da consulta.
	 */
	private function parseQuotedOrderProducts(Result $result): QuotedOrderProducts
	{
		$quotedOrderProducts = new QuotedOrderProducts();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$quotedOrderProduct = $this->newQuotedOrderProduct($entry);
			$quotedOrderProducts->add($quotedOrderProduct);
		}

		return $quotedOrderProducts;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo cotação de produto de pedido e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return QuotedOrderProduct aquisição de um objeto do tipo cotação de produto de pedido com dados carregados.
	 */
	private function newQuotedOrderProduct(array $entry): QuotedOrderProduct
	{
		$this->parseEntry($entry, 'quotedProductPrice', 'orderItemProduct', 'productPrice');
		$this->parseEntry($entry['quotedProductPrice'], 'product');

		$quotedOrderProduct = new QuotedOrderProduct();
		$quotedOrderProduct->fromArray($entry);

		return $quotedOrderProduct;
	}
}

