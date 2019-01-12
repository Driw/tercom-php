<?php

namespace tercom\control;

use tercom\dao\ProductPriceDAO;
use tercom\entities\OrderItemProduct;
use tercom\entities\ProductPrice;
use tercom\entities\lists\ProductPrices;
use tercom\exceptions\ProductPriceException;
use tercom\TercomException;

/**
 * Controle de Preço de Produto
 *
 * Através do controle é definida as especificações de ações permitidas para se gerenciar preços de produto.
 * Para tal existe uma comunicação direta com a DAO de preço de produto afim de persistir no banco de dados.
 * O uso do controle pode ser feito por qualquer outra classe necessária evitando o acesso direto a DAO.
 *
 * @see GenericControl
 * @see ProductPriceDAO
 * @see ProductPrices
 * @see ProductPrice
 *
 * @author Andrew
 */
class ProductPriceControl extends GenericControl
{
	/**
	 * @var ProductPriceDAO DAO para preço de produto.
	 */
	private $productPriceDAO;

	/**
	 * Construtor para inicializar a instância da DAO para preço de produto.
	 */
	public function __construct()
	{
		$this->productPriceDAO = new ProductPriceDAO();
	}

	/**
	 * Adiciona os dados de um novo preço de produto no sistema.
	 * @param ProductPrice $productPrice objeto de preço de produto à adicionar.
	 */
	public function add(ProductPrice $productPrice): void
	{
		if (!$this->productPriceDAO->insert($productPrice))
			throw ProductPriceException::newNotInserted();
	}

	/**
	 * Atualiza os dados de um preço de produto já existente no sistema.
	 * @param ProductPrice $productPrice objeto de preço de produto à atualizar.
	 */
	public function set(ProductPrice $productPrice): void
	{
		if (!$this->productPriceDAO->update($productPrice))
			throw ProductPriceException::newNotUpdated();
	}

	/**
	 * Remove os dados de um preço de produto já existente no sistema.
	 * @param ProductPrice $productPrice objeto de preço de produto à remover.
	 */
	public function remove(ProductPrice $productPrice): bool
	{
		if (!$this->productPriceDAO->delete($productPrice))
			throw ProductPriceException::newNotDeleted();
	}

	/**
	 * Obtém os dados de um preço de produto já existente no sistema pelo código de identificação único.
	 * @param int $idProductPrice código de identificação único.
	 * @return ProductPrice aquisição dos dados do preço de produto.
	 */
	public function get(int $idProductPrice): ProductPrice
	{
		if (($productPrice = $this->productPriceDAO->select($idProductPrice)) === null)
			throw ProductPriceException::newNotSelected();

		return $productPrice;
	}

	/**
	 * Obtém os dados de preços de produto de um determinado produto.
	 * @param int $idProduct código de identificação único do produto.
	 * @return ProductPrices aquisição da lista de preços desse produto.
	 */
	public function getByProduct(int $idProduct): ProductPrices
	{
		return $this->productPriceDAO->selectPrices($idProduct);
	}

	/**
	 * Obtém os dados dos preços de produtos disponíveis para um determinado item de produto de pedido.
	 * @param OrderItemProduct $orderItemProduct objeto do tipo item de produto de pedido à filtrar.
	 * @throws TercomException apenas se não for solicitado por um funcionário da TERCOM.
	 * @return ProductPrices aquisição da lista de preços de produtos disponíveis.
	 */
	public function searchByItem(OrderItemProduct $orderItemProduct): ProductPrices
	{
		if (!$this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		return $this->productPriceDAO->selectByItem($orderItemProduct);
	}

	/**
	 * Obtém os dados de preços de produto de um determinado produto e fornecedor.
	 * @param int $idProduct código de identificação único do produto.
	 * @param int $idProvider código de identificação único do fornecedor.
	 * @return ProductPrices aquisição da lista de preços conforme filtros.
	 */
	public function searchByProvider(int $idProduct, int $idProvider): ProductPrices
	{
		return $this->productPriceDAO->selectByProvider($idProduct, $idProvider);
	}

	/**
	 * Obtém os dados de preços de produto sendo filtrados na busca pelo nome do preço.
	 * @param string $name nome do preço de produto parcial ou total para filtro.
	 * @return ProductPrices aquisição da lista d epreços conforme filtro.
	 */
	public function searchByName(string $name): ProductPrices
	{
		return $this->productPriceDAO->selectLikeName($name);
	}
}

