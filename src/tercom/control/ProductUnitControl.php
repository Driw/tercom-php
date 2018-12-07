<?php

namespace tercom\control;

use tercom\dao\ProductUnitDAO;
use tercom\entities\ProductUnit;
use tercom\entities\lists\ProductUnits;
use tercom\exceptions\ProductUnitException;

/**
 * Controle para Unidade de Produto
 *
 * Através do controle é definida as especificações de ações permitidas para se gerenciar unidades de produto.
 * Para tal existe uma comunicação direta com a DAO de unidades de produto afim de persistir no banco de dados.
 * O uso do controle pode ser feito por qualquer outra classe necessária evitando o acesso direto a DAO.
 *
 * @see GenericControl
 * @see ProductUnitDAO
 * @see ProductUnit
 *
 * @author Andrew
 */
class ProductUnitControl extends GenericControl
{
	/**
	 * @var ProductUnitDAO DAO para unidade de produto.
	 */
	private $productUnitDAO;

	/**
	 * Construtor para inicializar a instância da DAO para unidades de produto.
	 */
	public function __construct()
	{
		$this->productUnitDAO = new ProductUnitDAO();
	}

	/**
	 * Adiciona uma nova unidade de produto no sistema.
	 * @param ProductUnit $productUnit objeto do tipo unidade de produto à adicionar.
	 * @throws ProductUnitException falha na validação dos dados ou não adicionado.
	 */
	public function add(ProductUnit $productUnit): void
	{
		if (!$this->productUnitDAO->insert($productUnit))
			throw ProductUnitException::newNotInserted();
	}

	/**
	 * Define os dados de uma nova unidade de produto no sistema.
	 * @param ProductUnit $productUnit objeto do tipo unidade de produto à definir.
	 * @throws ProductUnitException falha na validação dos dados ou não definida.
	 */
	public function set(ProductUnit $productUnit): void
	{
		if (!$this->productUnitDAO->update($productUnit))
			throw ProductUnitException::newNotUpdated();
	}

	/**
	 * Remove os dados de uma unidade de produto já existente no sistema.
	 * @param ProductUnit $productUnit objeto do tipo unidade de produto à remover.
	 * @throws ProductUnitException somente se não for possível remover.
	 */
	public function remove(ProductUnit $productUnit): void
	{
		if (!$this->productUnitDAO->dalete($productUnit))
			throw ProductUnitException::newNotDeleted();
	}

	/**
	 * Obtém os dados individuais de uma unidade de produto no sistema.
	 * @param int $idProductUnit código de identificação único da unidade de produto à obter.
	 * @return ProductUnit aquisição de um objeto do tipo unidade de produto a partir do código.
	 * @throws ProductUnitException somente se o código for inválido/inexistente.
	 */
	public function get(int $idProductUnit): ProductUnit
	{
		if (($productUnit = $this->productUnitDAO->select($idProductUnit)) === null)
			throw ProductUnitException::newNotSelected();

		return $productUnit;
	}

	/**
	 * Obtém uma lista com os dados de todas as unidades de produto no sistema.
	 * @return ProductUnits aquisição da lista com objetos do tipo unidade de produto.
	 */
	public function getAll(): ProductUnits
	{
		return $this->productUnitDAO->selectAll();
	}

	/**
	 * Obtém uma lista com os dados das unidades de produtos no sistema filtradas.
	 * O filtro é feito pelo nome da unidade de produto seja ela parcial ou completa.
	 * @param string $name nome da unidade de produto à ser filtrada.
	 * @return ProductUnits aquisição da lista com as unidades de produto filtradas.
	 */
	public function searchByName(string $name): ProductUnits
	{
		return $this->productUnitDAO->selectLikeName($name);
	}

	/**
	 * Verifica se um determinado código de unidade de produto existe no sistema.
	 * @param int $idProductUnit código de identificação da unidade de produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function has(int $idProductUnit): bool
	{
		return $this->productUnitDAO->exist($idProductUnit);
	}

	/**
	 * Verifica se um determinado nome para unidade de produto existe no sistema.
	 * @param string $name nome da unidade de produto a ser verificado.
	 * @param int $idProductUnit código de idenficação da unidade de produto atual ou
	 * 0 (zero) caso seja uma nova unidade de produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function hasName(string $name, int $idProductUnit = 0): bool
	{
		return $this->productUnitDAO->existName($name, $idProductUnit);
	}

	/**
	 * Verifica se um determinado abreviação para unidade de produto existe no sistema.
	 * @param string $shortName abreviação da unidade de produto a ser verificado.
	 * @param int $idProductUnit código de idenficação da unidade de produto atual ou
	 * 0 (zero) caso seja uma nova unidade de produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function hasShortName(string $shortName, int $idProductUnit = 0): bool
	{
		return $this->productUnitDAO->existShortName($shortName, $idProductUnit);
	}
}

