<?php

namespace tercom\control;

use tercom\dao\ProductPackageDAO;
use tercom\entities\ProductPackage;
use tercom\entities\lists\ProductPackages;
use tercom\exceptions\ProductPackageException;

/**
 * Controle de Embalagem de Produto
 *
 * Através do controle é definida as especificações de ações permitidas para se gerenciar embalagem de produto.
 * Para tal existe uma comunicação direta com a DAO de embalagem de produto afim de persistir no banco de dados.
 * O uso do controle pode ser feito por qualquer outra classe necessária evitando o acesso direto a DAO.
 *
 * @see GenericControl
 * @see ProductPackageDAO
 * @see ProductPackages
 * @see ProductPackage
 *
 * @author Andrew
 */
class ProductPackageControl extends GenericControl
{
	/**
	 * @var ProductPackageDAO DAO para embalagem de produto.
	 */
	private $productPackageDAO;

	/**
	 * Construtor para inicializar a instância da DAO de embalagem de produto.
	 */
	public function __construct()
	{
		$this->productPackageDAO = new ProductPackageDAO();
	}

	/**
	 * Adiciona os dados de uma nova embalagem de produto no sistema.
	 * @param ProductPackage $productPackage objeto do tipo embalagem de produto à adicionar.
	 * @throws ProductPackageException quando não for possível adicionar.
	 */
	public function add(ProductPackage $productPackage): void
	{
		if (!$this->productPackageDAO->insert($productPackage))
			throw ProductPackageException::newNotInserted();
	}

	/**
	 * Atualiza os dados de uma embalagem de produto já existente no sistema.
	 * @param ProductPackage $productPackage objeto do tipo embalagem de produto à atualizar.
	 * @throws ProductPackageException quando não for possível atualizar.
	 */
	public function set(ProductPackage $productPackage): void
	{
		if (!$this->productPackageDAO->update($productPackage))
			throw ProductPackageException::newNotUpdated();
	}

	/**
	 * Remove os dados de uma embalagem de produto do sistema.
	 * @param ProductPackage $productPackage objeto do tipo embalagem de produto à excluir.
	 * @throws ProductPackageException quando não for possível excluir.
	 */
	public function remove(ProductPackage $productPackage): void
	{
		if (!$this->productPackageDAO->dalete($productPackage))
			throw ProductPackageException::newNotDeleted();
	}

	/**
	 * Obtém uma embalagem de produto no sistema través do seu código de identificação único.
	 * @param int $idProductPackage código de identificação único da embalagem de produto.
	 * @throws ProductPackageException quando não for possível obter os dados.
	 * @return ProductPackage aquisição do objeto de embalagem de produto.
	 */
	public function get(int $idProductPackage): ProductPackage
	{
		if (($productPackage = $this->productPackageDAO->select($idProductPackage)) === null)
			throw ProductPackageException::newNotSelected();

		return $productPackage;
	}

	/**
	 * Obtém uma lista com todas as embalagens de produto registradas no sistema.
	 * @return ProductPackages aquisição da lista de embalagens de produto encontradas.
	 */
	public function getAll(): ProductPackages
	{
		return $this->productPackageDAO->selectAll();
	}

	/**
	 * Procura por embalagens de produto através do seu nome.
	 * @param string $name nome da embalagem parcial ou completo para filtro.
	 * @return ProductPackages aquisição da lista de embalagens de produto filtradas.
	 */
	public function searchByName(string $name): ProductPackages
	{
		return $this->productPackageDAO->selectLikeName($name);
	}

	/**
	 * Vericia se o sistema possui ou não uma determinada embalagem de produto.
	 * @param int $idProductPackage código de identificação único da embalagem de produto à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function has(int $idProductPackage): bool
	{
		return $this->productPackageDAO->exist($idProductPackage);
	}

	/**
	 * Verifica se o sistema possui ou não um determinado nome de embalagem de produto.
	 * @param string $name nome da embalagem de produto à verificar.
	 * @param int $idProductPackage código de identificação único da embalagem de produto à desconsiderar
	 * ou zero caso deseja considerar todas as embalagens.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function hasName(string $name, int $idProductPackage = 0): bool
	{
		return $this->productPackageDAO->existName($name, $idProductPackage);
	}
}

