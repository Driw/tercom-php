<?php

namespace tercom\control;

use tercom\dao\ProductTypeDAO;
use tercom\entities\ProductType;
use tercom\entities\lists\ProductTypes;
use tercom\exceptions\ProductTypeException;

/**
 * Controle de Tipo de Produto
 *
 * Através do controle é definida as especificações de ações permitidas para se gerenciar tipos de produto.
 * Para tal existe uma comunicação direta com a DAO de tipo de produto afim de persistir no banco de dados.
 * O uso do controle pode ser feito por qualquer outra classe necessária evitando o acesso direto a DAO.
 *
 * @see GenericControl
 * @see ProductTypeDAO
 *
 * @author Andrew
 */
class ProductTypeControl extends GenericControl
{
	/**
	 * @var ProductTypeDAO DAO para tipo de produto.
	 */
	private $productTypeDAO;

	/**
	 * Construtor par ainicializar a instância da DAO de tipo de produto.
	 */
	public function __construct()
	{
		$this->productTypeDAO = new ProductTypeDAO();
	}

	/**
	 * Adiciona os dados de um novo tipo de produto no sistema.
	 * @param ProductType $productType objeto do tipo tipo de produto à adicionar.
	 * @throws ProductTypeException quando não for possível adicionar.
	 */
	public function add(ProductType $productType): void
	{
		if (!$this->productTypeDAO->insert($productType))
			throw ProductTypeException::newNotInserted();
	}

	/**
	 * Atualiza os dados de um tipo de produto já existente no sistema.
	 * @param ProductType $productType objeto do tipo tipo de produto à atualizar.
	 * @throws ProductTypeException quando não for possível atualizar.
	 */
	public function set(ProductType $productType): void
	{
		if (!$this->productTypeDAO->update($productType))
			throw ProductTypeException::newNotUpdated();
	}

	/**
	 * Remove os dados de um tipo de produto do sistema.
	 * @param ProductType $productType objeto do tipo tipo de produto à excluir.
	 * @throws ProductTypeException quando não for possível excluir.
	 */
	public function remove(ProductType $productType): void
	{
		if (!$this->productTypeDAO->dalete($productType))
			throw ProductTypeException::newNotDeleted();
	}

	/**
	 * Obtém um tipo de produto no sistema través do seu código de identificação único.
	 * @param int $idProductType código de identificação único do tipo de produto.
	 * @throws ProductTypeException quando não for possível obter os dados.
	 * @return ProductType aquisição do objeto do tipo de produto.
	 */
	public function get(int $idProductType): ProductType
	{
		if (($productType = $this->productTypeDAO->select($idProductType)) === null)
			throw ProductTypeException::newNotSelected();

		return $productType;
	}

	/**
	 * Obtém uma lista com todas os tipos de produto registradas no sistema.
	 * @return ProductTypes aquisição da lista de tipos de produto encontradas.
	 */
	public function getAll(): ProductTypes
	{
		return $this->productTypeDAO->selectAll();
	}

	/**
	 * Procura por tipos de produto através do seu nome.
	 * @param string $name nome do tipo parcial ou completo para filtro.
	 * @return ProductTypes aquisição da lista de tipos de produto filtrados.
	 */
	public function searchByName(string $name): ProductTypes
	{
		return $this->productTypeDAO->selectLikeName($name);
	}

	/**
	 * Vericia se o sistema possui ou não um determinado tipo de produto.
	 * @param int $idProductType código de identificação único do tipo de produto à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function has(int $idProductType): bool
	{
		return $this->productTypeDAO->exist($idProductType);
	}

	/**
	 * Verifica se o sistema possui ou não um determinado nome do tipo de produto.
	 * @param string $name nome do tipo de produto à verificar.
	 * @param int $idProductType código de identificação único do tipo de produto à desconsiderar
	 * ou zero caso deseja considerar todos os tipos.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function hasName(string $name, int $idProductType = 0): bool
	{
		return $this->productTypeDAO->existName($name, $idProductType);
	}
}

