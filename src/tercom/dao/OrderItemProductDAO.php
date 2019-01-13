<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\Manufacturer;
use tercom\entities\OrderItemProduct;
use tercom\entities\OrderRequest;
use tercom\entities\Product;
use tercom\entities\Provider;
use tercom\entities\lists\OrderItemProducts;
use tercom\exceptions\OrderItemProductException;

/**
 * DAO para Item de Produto de Pedido
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados dos itens de produto de pedido, incluindo todas operações.
 * Estas operações consiste em: adicionar, inserir, atualizar excluir e selcionar itens de produto de pedido</b>.
 *
 * Item de produto de pedido obrigatoriamente precisa ter um funcionário de cliente, produto e solicitação de pedido de cotação.
 * Se informado fabricante deve existir e se informado um fornecedor também deve existir.
 *
 * @see GenericDAO
 * @see OrderRequest
 * @see OrderItemProduct
 * @see OrderItemProducts
 *
 * @author Andrew
 */
class OrderItemProductDAO extends GenericDAO
{
	/**
	 * @var array vetor com o nome das colunas da tabela de item de produto de pedido.
	 */
	public const ALL_COLUMNS = ['id', 'idOrderRequest', 'idProduct', 'idProvider', 'idManufacturer', 'betterPrice', 'observations'];

	/**
	 * Procedimento interno para validação dos dados de um item de produto de pedido ao inserir, atualizar e excluir.
	 * Cotação de produto de pedido precisa ter apenas um item de produto de pedido existente.
	 * @param OrderRequest|NULL $orderRequest objeto do tipo solicitação de pedido de cotação à considerar,
	 * caso seja informado o valor NULL será desconsiderado a validação do mesmo.
	 * @param OrderItemProduct $orderItemProduct objeto do tipo item de produto de pedido à validar.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws OrderItemProductException caso algum dos dados da cotação de produto de pedido não estejam de acordo.
	 */
	private function validate(?OrderRequest $orderRequest, OrderItemProduct $orderItemProduct, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($orderItemProduct->getId() === 0)
				throw OrderItemProductException::newNotIdentified();
		} else {
			if ($orderItemProduct->getId() !== 0)
				throw OrderItemProductException::newIdentified();
		}

		// UNIQUE KEY
		if ($orderRequest !== null && $this->exist($orderRequest, $orderItemProduct->getProduct())) throw OrderItemProductException::newExist();

		// NOT NULL
		if ($orderItemProduct->getProductId() === 0) throw OrderItemProductException::newProductEmpty();

		// FOREIGN KEY
		if (!$this->existProduct($orderItemProduct->getProduct())) throw OrderItemProductException::newProviderInvalid();
		if ($orderItemProduct->getProviderId() !== 0 && !$this->existProvider($orderItemProduct->getProvider())) throw OrderItemProductException::newProviderInvalid();
		if ($orderItemProduct->getManufacturerId() !== 0 && !$this->existManufacturer($orderItemProduct->getManufacturer())) throw OrderItemProductException::newManufacturerInvalid();
	}

	/**
	 * Insere um novo item de produto de pedido no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param OrderRequest $orderRequest objeto do tipo solicitação de pedido de cotação à vincular.
	 * @param OrderItemProduct $orderItemProduct objeto do tipo item de produto de pedido à adicionar.
	 * @return bool true se conseguir inserir ou false caso contrário.
	 */
	public function insert(OrderRequest $orderRequest, OrderItemProduct $orderItemProduct): bool
	{
		$this->validate($orderRequest, $orderItemProduct, false);

		$sql = "INSERT INTO order_item_products (idOrderRequest, idProduct, idProvider, idManufacturer, betterPrice, observations)
				VALUES (?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $orderRequest->getId());
		$query->setInteger(2, $orderItemProduct->getProductId());
		$query->setInteger(3, $this->parseNullID($orderItemProduct->getProviderId()));
		$query->setInteger(4, $this->parseNullID($orderItemProduct->getManufacturerId()));
		$query->setBoolean(5, $orderItemProduct->isBetterPrice());
		$query->setString(6, $orderItemProduct->getObservations());

		if (($result = $query->execute())->isSuccessful())
			$orderItemProduct->setId($result->getInsertID());

		return $orderItemProduct->getId() !== 0;
	}

	/**
	 * Atualiza os dados de um item de produto de pedido já existente no banco de dados.
	 * @param OrderItemProduct $orderItemProduct objeto do tipo item de produto de pedido à atualizar.
	 * @return bool true se conseguir atualizar ou false caso contrário.
	 */
	public function update(OrderItemProduct $orderItemProduct): bool
	{
		$this->validate(null, $orderItemProduct, true);

		$sql = "UPDATE order_item_products
				SET idProvider = ?, idManufacturer = ?, betterPrice = ?, observations = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $this->parseNullID($orderItemProduct->getProviderId()));
		$query->setInteger(2, $this->parseNullID($orderItemProduct->getManufacturerId()));
		$query->setBoolean(3, $orderItemProduct->isBetterPrice());
		$query->setString(4, $orderItemProduct->getObservations());
		$query->setString(5, $orderItemProduct->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exlui os dados de um item de produto de pedido já existente no banco de dados.
	 * @param OrderItemProduct $orderItemProduct objeto do tipo item de produto de pedido à exluir.
	 * @return bool true se conseguir exluir ou false caso contrário.
	 */
	public function delete(OrderItemProduct $orderItemProduct): bool
	{
		$this->validate(null, $orderItemProduct, true);

		$sql = "DELETE FROM order_item_products
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderItemProduct->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exlui todos os itens de produto de pedido vinculados a uma solicitação de pedido de cotação.
	 * @param OrderRequest $orderRequest objeto do tipo solicitação de pedido de cotação à considerar.
	 * @return bool true se conseguir excluir ou false caso contrário.
	 */
	public function deleteAll(OrderRequest $orderRequest): bool
	{
		$sql = "DELETE FROM order_item_products
				WHERE idOrderRequest = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		$orderItemProductColumns = $this->buildQuery(self::ALL_COLUMNS, 'order_item_products');
		$productColumns = $this->buildQuery(ProductDAO::ALL_COLUMNS, 'products', 'product');
		$providerColumns = $this->buildQuery(ProviderDAO::ALL_COLUMNS, 'providers', 'provider');
		$manufacturerColumns = $this->buildQuery(ManufacturerDAO::ALL_COLUMNS, 'manufacturers', 'manufacturer');

		return "SELECT $orderItemProductColumns, $productColumns, $providerColumns, $manufacturerColumns
				FROM order_item_products
				INNER JOIN products ON products.id = order_item_products.idProduct
				LEFT JOIN providers ON providers.id = order_item_products.idProvider
				LEFT JOIN manufacturers ON manufacturers.id = order_item_products.idManufacturer";
	}

	/**
	 * Seleciona os dados de um item de produto de pedido através do código de identificação.
	 * @param int $idOrderItemProduct código de identificação único do item de produto de pedido.
	 * @return OrderItemProduct|NULL aquisição do item de produto de pedido selecionado.
	 */
	public function select(int $idOrderItemProduct): ?OrderItemProduct
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_item_products.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idOrderItemProduct);

		$result = $query->execute();

		return $this->parseOrderItemProduct($result);
	}

	/**
	 * Seleciona os dados de um item de produto de pedido através da solicitação e item do pedido de cotação.
	 * @param int $idOrderRequest objeto do tipo solicitação de pedido de cotação à considerar.
	 * @param int $idOrderItemProduct objeto do tipo item de produto de cotação à considerar.
	 * @return OrderItemProduct|NULL aquisição do item de produto de pedido selecionado.
	 */
	public function selectWithOrderRequest(int $idOrderRequest, int $idOrderItemProduct): ?OrderItemProduct
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_item_products.id = ? AND order_item_products.idOrderRequest = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idOrderItemProduct);
		$query->setInteger(2, $idOrderRequest);

		$result = $query->execute();

		return $this->parseOrderItemProduct($result);
	}

	/**
	 * Seleciona os dados dos itens de produto de pedido através de uma solicitação de pedido de cotação.
	 * @param OrderRequest $orderRequest objeto do tipo solicitação de pedido de cotação à considerar.
	 * @return OrderItemProducts aquisição da lista com os itens de produto de pedido selecionados.
	 */
	public function selectAll(OrderRequest $orderRequest): OrderItemProducts
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_item_products.idOrderRequest = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());

		$result = $query->execute();

		return $this->parseOrderItemProducts($result);
	}

	/**
	 * Verifica se um determinado produto já existe como item de produto de pedido.
	 * @param OrderRequest $orderRequest objeto do tipo solicitação de pedido de cotação à considerar.
	 * @param Product $product objeto do tipo produto do qual será verificado.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function exist(OrderRequest $orderRequest, Product $product): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM order_item_products
				WHERE idOrderRequest = ? AND idProduct = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());
		$query->setInteger(2, $product->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado produto existe no banco de dados.
	 * @param Product $product objeto do tipo produto à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existProduct(Product $product): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM products
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $product->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado fornecedor existe no banco de dados.
	 * @param Provider $provider objeto do tipo fornecedor à verficiar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existProvider(Provider $provider): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM providers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $provider->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado fabricante existe no banco de dados.
	 * @param Manufacturer $manufacturer objeto do tipo fabricante à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existManufacturer(Manufacturer $manufacturer): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM manufacturers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $manufacturer->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de item de produto de pedido.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return OrderItemProduct|NULL objeto do tipo cotação de produto de pedido com dados carregados ou NULL se não houver resultado.
	 */
	private function parseOrderItemProduct(Result $result): ?OrderItemProduct
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newOrderItemProduct($entry);
	}

	/**
	 * Procedimento inerno para analisar o resultado de uma consulta e criar os itens de produto de pedido.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return OrderItemProducts aquisição da lista dos itens de produto de pedido a partir da consulta.
	 */
	private function parseOrderItemProducts(Result $result): OrderItemProducts
	{
		$orderItemProducts = new OrderItemProducts();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$orderItemProduct = $this->newOrderItemProduct($entry);
			$orderItemProducts->add($orderItemProduct);
		}

		return $orderItemProducts;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo item de produto de pedido e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return OrderItemProduct aquisição de um objeto do tipo item de produto de pedido com dados carregados.
	 */
	private function newOrderItemProduct(array $entry): OrderItemProduct
	{
		$this->parseEntry($entry, 'product', 'provider', 'manufacturer');

		$orderItemProduct = new OrderItemProduct();
		$orderItemProduct->fromArray($entry);

		return $orderItemProduct;
	}
}

