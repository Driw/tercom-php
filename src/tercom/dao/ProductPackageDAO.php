<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\ProductPackage;
use tercom\entities\lists\ProductPackages;
use tercom\exceptions\ProductPackageException;

/**
 * DAO para Embalagem de Produto
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes as embalagens de produto, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar, excluir e selecionar dados das embalagens de produto.
 *
 * Cada embalagem deve possuir um nome sendo obrigatório e único no sistema.
 *
 * @see GenericDAO
 * @see ProductPackage
 * @see ProductPackages
 *
 * @author Andrew
 */
class ProductPackageDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de embalagens de produto.
	 */
	public const ALL_COLUMNS = ['id', 'name'];

	/**
	 * Procedimento interno para validação dos dados de uma embalagem de produto ao inserir e/ou atualizar.
	 * Embalagens de produto devem possuir um nome e este deve ser único no sistema.
	 * @param ProductPackage $productPackage objeto do tipo emblagem de produto à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws ProductPackageException caso algum dos dados da embalagem de produto não estejam de acordo.
	 */
	private function validate(ProductPackage $productPackage, bool $validateID)
	{
		// PRIMARY KEY
		if ($validateID) {
			if ($productPackage->getId() === 0)
				throw ProductPackageException::newNotIdentified();
		} else {
			if ($productPackage->getId() !== 0)
				throw ProductPackageException::newIdentified();
		}

		// UNIQUE KEY
		if ($this->existName($productPackage->getName(), $productPackage->getId())) throw ProductPackageException::newNameUnavaiable();
	}

	/**
	 * Insere uma nova embalagem de produto no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param ProductPackage $productPackage objeto do tipo embalagem de produto à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(ProductPackage $productPackage): bool
	{
		$this->validate($productPackage, false);

		$sql = "INSERT INTO product_packages (name)
				VALUES (?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $productPackage->getName());

		if (($result = $query->execute())->isSuccessful())
			$productPackage->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 * Atualiza os dados de uma embalagem de produto já existente no banco de dados.
	 * @param ProductPackage $productPackage objeto do tipo embalagem de produto à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(ProductPackage $productPackage): bool
	{
		$this->validate($productPackage, true);

		$sql = "UPDATE product_packages
				SET name = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $productPackage->getName());
		$query->setInteger(2, $productPackage->getID());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui uma embalagem de produto do banco de dados e considera utilizações.
	 * Caso esteja sendo referenciada em outra tabela não será possível excluir.
	 * @param ProductPackage $productPackage objeto do tipo embalagem de produto à excluir.
	 * @return bool true se excluído ou false caso contrário.
	 */
	public function dalete(ProductPackage $productPackage): bool
	{
		$this->validate($productPackage, true);

		if ($this->existOnProductPrice($productPackage->getId()))
			throw ProductPackageException::newHasUses();

		$sql = "DELETE FROM product_packages
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productPackage->getID());

		return ($query->execute())->getAffectedRows() === 1;
	}

	/**
	 * Selecione os dados de uma embalagem de produto através do seu código de identificação único.
	 * @param int $idProductPackage código de identificação único da embalagem de produto.
	 * @return ProductPackage|NULL embalagem de produto com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idProductPackage): ?ProductPackage
	{
		$sql = "SELECT id, name
				FROM product_packages
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductPackage);

		$result = $query->execute();

		return $this->parseProductPackage($result);
	}

	/**
	 * Seleciona os dados de todas as embalagens de produto registradas no banco de dados sem ordenação.
	 * @return ProductPackages aquisição da lista de emblagens de produto atualmente registrados.
	 */
	public function selectAll(): ProductPackages
	{
		$sql = "SELECT id, name
				FROM product_packages";

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseProductPackages($result);
	}

	/**
	 * Seleciona os dados das embalagens de produto no banco de dados filtrados pelo nome.
	 * @param string $name nome da emblagem parcial ou completo para filtro.
	 * @return ProductPackages aquisição da lista de embalagens de produto conforme filtro.
	 */
	public function selectLikeName(string $name): ProductPackages
	{
		$sql = "SELECT id, name
				FROM product_packages
				WHERE name LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$name%");

		$result = $query->execute();

		return $this->parseProductPackages($result);
	}

	/**
	 * Verifica se um determinado código de identificação de emblagem de produto existe.
	 * @param int $idProductPackage código de identificação único da embalagem de produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function exist(int $idProductPackage): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_packages
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductPackage);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um nome de embalagem de produto está disponível par auso.
	 * @param string $name nome da emblagem de produto à verificar.
	 * @param int $idProductPackage código de identificação único da emblagem de produto
	 * ou zero caso seja uma nova embalagem de produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existName(string $name, int $idProductPackage): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_packages
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idProductPackage);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se uma embalagem de produto está sendo referenciada em algum preço de produto.
	 * @param int $idProductPackage código de identificação único da emblagem de produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existOnProductPrice(int $idProductPackage): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_prices
				WHERE idProductPackage = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductPackage);

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de embalagem de produto.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ProductPackage|NULL objeto do tipo embalagem de produto com dados carregados ou NULL se não houver resultado.
	 */
	private function parseProductPackage(Result $result): ?ProductPackage
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newProductPackage($entry);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar os objetos de embalagem de produto.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ProductPackages aquisição da lista de embalagens de produto a partir da consulta.
	 */
	private function parseProductPackages(Result $result): ProductPackages
	{
		$productPackages = new ProductPackages();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$productPackage = $this->newProductPackage($entry);
			$productPackages->add($productPackage);
		}

		return $productPackages;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo embalagem de produto e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return ProductPackage aquisição de um objeto do tipo embalagem de produto com dados carregados.
	 */
	private function newProductPackage(array $entry): ProductPackage
	{
		$productPackage = new ProductPackage();
		$productPackage->fromArray($entry);

		return $productPackage;
	}
}

