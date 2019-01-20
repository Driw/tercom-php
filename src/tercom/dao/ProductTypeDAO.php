<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\ProductType;
use tercom\entities\lists\ProductTypes;
use tercom\exceptions\ProductTypeException;

/**
 * DAO para Tipo de Produto
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos tipos de produto, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar, excluir e selecionar dados dos tipos de produto.
 *
 * Cada tipo deve possuir um nome sendo obrigatório e único no sistema.
 *
 * @see GenericDAO
 * @see ProductType
 * @see ProductTypes
 *
 * @author Andrew
 */
class ProductTypeDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de tipos de produto.
	 */
	public const ALL_COLUMNS = ['id', 'name'];

	/**
	 * Procedimento interno para validação dos dados de um tipo de produto ao inserir e/ou atualizar.
	 * Tipos de produto devem possuir um nome e este deve ser único no sistema.
	 * @param ProductType $productType objeto do tipo tipo de produto à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws ProductTypeException caso algum dos dados do tipo de produto não estejam de acordo.
	 */
	private function validate(ProductType $productType, bool $validateID)
	{
		// PRIMARY KEY
		if ($validateID) {
			if ($productType->getId() === 0)
				throw ProductTypeException::newNotIdentified();
		} else {
			if ($productType->getId() !== 0)
				throw ProductTypeException::newIdentified();
		}

		// UNIQUE KEY
		if ($this->existName($productType->getName(), $productType->getId())) throw ProductTypeException::newNameUnavaiable();
	}

	/**
	 * Insere um novo tipo de produto no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param ProductType $productType objeto do tipo tipo de produto à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(ProductType $productType): bool
	{
		$sql = "INSERT INTO product_types (name)
				VALUES (?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $productType->getName());

		if (($result = $query->execute())->isSuccessful())
			$productType->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 * Atualiza os dados de um tipo de produto já existente no banco de dados.
	 * @param ProductType $productType objeto do tipo tipo de produto à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(ProductType $productType): bool
	{
		$sql = "UPDATE product_types
				SET name = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $productType->getName());
		$query->setInteger(2, $productType->getID());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui um tipo de produto do banco de dados e considera utilizações.
	 * Caso esteja sendo referenciada em outra tabela não será possível excluir.
	 * @param ProductType $productType objeto do tipo tipo de produto à excluir.
	 * @return bool true se excluído ou false caso contrário.
	 */
	public function dalete(ProductType $productType): bool
	{
		if ($this->existOnProductPrice($productType->getId()))
			throw ProductTypeException::newHasUses();

		$sql = "DELETE FROM product_types
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productType->getID());

		return ($query->execute())->getAffectedRows() === 1;
	}

	/**
	 * Selecione os dados de um tipo de produto através do seu código de identificação único.
	 * @param int $idProductType código de identificação único do tipo de produto.
	 * @return ProductType|NULL tipo de produto com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idProductType): ?ProductType
	{
		$sql = "SELECT id, name
				FROM product_types
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductType);

		$result = $query->execute();

		return $this->parseProductType($result);
	}

	/**
	 * Seleciona os dados de todos os tipos de produto registrados no banco de dados sem ordenação.
	 * @return ProductTypes aquisição da lista de tipos de produto atualmente registrados.
	 */
	public function selectAll(): ProductTypes
	{
		$sql = "SELECT id, name
				FROM product_types";

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseProductTypes($result);
	}

	/**
	 * Seleciona os dados dos tipos de produto no banco de dados filtrados pelo nome.
	 * @param string $name nome do tipo parcial ou completo para filtro.
	 * @return ProductTypes aquisição da lista de tipos de produto conforme filtro.
	 */
	public function selectLikeName(string $name): ProductTypes
	{
		$sql = "SELECT id, name
				FROM product_types
				WHERE name LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$name%");

		$result = $query->execute();

		return $this->parseProductTypes($result);
	}

	/**
	 * Verifica se um determinado código de identificação de tipo de produto existe.
	 * @param int $idProductType código de identificação único do tipo de produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function exist(int $idProductType): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_types
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductType);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um nome de tipo de produto está disponível par auso.
	 * @param string $name nome do tipo de produto à verificar.
	 * @param int $idProductType código de identificação único do tipo de produto
	 * ou zero caso seja um novo tipo de produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existName(string $name, int $idProductType): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_types
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idProductType);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um tipo de produto está sendo referenciada em algum preço de produto.
	 * @param int $idProductPackage código de identificação único do tipo de produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existOnProductPrice(int $idProductPackage): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_prices
				WHERE idProductType = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductPackage);

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto do tipo tipo de produto.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ProductType|NULL objeto instânciado com dados carregados ou NULL se não houver resultado.
	 */
	private function parseProductType(Result $result): ?ProductType
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newProductType($entry);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar os objetos do tipo tipo de produto.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ProductTypes aquisição da lista de tipos de produto a partir da consulta.
	 */
	private function parseProductTypes(Result $result): ProductTypes
	{
		$productTypes = new ProductTypes();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$productType = $this->newProductType($entry);
			$productTypes->add($productType);
		}

		return $productTypes;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo tipo de produto e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return ProductType aquisição de um objeto do tipo tipo de produto com dados carregados.
	 */
	private function newProductType(array $array): ProductType
	{
		$productType = new ProductType();
		$productType->fromArray($array);

		return $productType;
	}
}

