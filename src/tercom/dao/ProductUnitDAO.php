<?php

namespace tercom\dao;

use tercom\entities\ProductUnit;
use tercom\entities\lists\ProductUnits;
use dProject\MySQL\Result;
use tercom\exceptions\ProductUnitException;

/**
 * DAO para Unidades de Produto
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes as unidades de produto, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar, excluír* e selecionar.
 * <i>*Unidades de produto podem ser excluídas somente quando não houver dependências.</i>
 *
 * Unidades de produto não podem ter seu nome ou abreviação repetidas portanto são valores únicos.
 * O nome e abreviação são os únicos campos e por serem únicos precisam ser definidos (obrigatórios).
 *
 * @see GenericDAO
 * @see ProductUnit
 *
 * @author Andrew
 */
class ProductUnitDAO extends GenericDAO
{
	/**
	 * @var array vetor com o nome de todas as colunas.
	 */
	public const ALL_COLUMNS = ['id', 'name', 'shortName'];

	/**
	 * Procedimento interno para validação dos dados de uma unidade de produto ao inserir e/ou atualizar.
	 * Unidades de produto não podem ter seu nome e abreviação em branco, devem ser definidos e únicos.
	 * @param ProductUnit $productUnit objeto do tipo unidade de produto à ser validado.
	 * @param bool $validateId true para validar o código de identificação ou false caso contrário.
	 * @throws ProductUnitException caso algum dos dados da unidade de produto não estejam de acordo.
	 */
	private function validate(ProductUnit $productUnit, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($productUnit->getId() === 0)
				throw ProductUnitException::newNotIdentified();
		} else {
			if ($productUnit->getId() !== 0)
				throw ProductUnitException::newIdentified();
		}

		// UNIQUE KEY
		if ($this->existName($productUnit->getName(), $productUnit->getId())) throw ProductUnitException::newNameUnavaiable();
		if ($this->existShortName($productUnit->getShortName(), $productUnit->getId())) throw ProductUnitException::newShortNameUnavaiable();
	}

	/**
	 * Insere uma nova unidade de produto no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param ProductUnit $productUnit objeto do tipo unidade de produto à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(ProductUnit $productUnit): bool
	{
		$this->validate($productUnit, false);

		$sql = "INSERT INTO product_units (name, shortName)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $productUnit->getName());
		$query->setString(2, $productUnit->getShortName());

		if (($result = $query->execute())->isSuccessful())
			$productUnit->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 * Atualiza os dados de uma unidade de produto já existente no banco de dados.
	 * @param ProductUnit $productUnit objeto do tipo unidade de produto à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(ProductUnit $productUnit): bool
	{
		$this->validate($productUnit, true);

		$sql = "UPDATE product_units
				SET name = ?, shortName = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $productUnit->getName());
		$query->setString(2, $productUnit->getShortName());
		$query->setInteger(3, $productUnit->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui uma unidade de produto do banco de dados verificando anteriormente dependências.
	 * Caso haja dependências a unidade de produto não poderá ser excluída (usada por produtos).
	 * @param ProductUnit $productUnit objeto do tipo unidade de produto à excluir.
	 * @return bool true se conseguir excluir ou false caso contrário.
	 */
	public function dalete(ProductUnit $productUnit): bool
	{
		if ($this->existOnProducts($productUnit->getId()))
			throw ProductUnitException::newHasUses();

		$sql = "DELETE FROM product_units
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productUnit->getID());

		$result = $query->execute();

		return $result->getAffectedRows();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		return "SELECT id, name, shortName
				FROM product_units";
	}

	/**
	 * Selecione os dados de uma unidade de produto através do seu código de identificação único.
	 * @param int $idProductUnit código de identificação único da unidade de produto.
	 * @return ProductUnit|NULL unidade de produto com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idProductUnit): ?ProductUnit
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductUnit);

		$result = $query->execute();

		return $this->parseProductUnit($result);
	}

	/**
	 * Seleciona os dados de todas as unidades de produto registradas no banco de dados sem ordenação.
	 * @return ProductUnits aquisição da lista de unidades de produto atualmente registradas.
	 */
	public function selectAll(): ProductUnits
	{
		$sql = $this->newSelect();
		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseProductUnits($result);
	}

	/**
	 * Seleciona os dados das unidades de produto no banco de dados filtradas pelo nome.
	 * @param string $name nome parcial ou completo da unidade de produto para filtro.
	 * @return ProductUnits aquisição da lista de unidades de produto conforme filtro.
	 */
	public function selectLikeName(string $name): ProductUnits
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE name LIKE ?";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$name%");

		$result = $query->execute();

		return $this->parseProductUnits($result);
	}

	/**
	 * Verifica se um determinado código de identificação de unidade de produto existe.
	 * @param int $idProductUnit código de identificação único da unidade do produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function exist(int $idProductUnit): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_units
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductUnit);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um nome para unidade de produto existe.
	 * @param string $name nome da unidade de produto.
	 * @param int $idProductUnit código de identificação da unidade de produto ou
	 * 0 (zero) para desconsiderar qualquer unidade de produto (nova unidade).
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existName(string $name, int $idProductUnit): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_units
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idProductUnit);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se uma abreviação para unidade de produto existe.
	 * @param string $shorName abreviação da unidade de produto.
	 * @param int $idProductUnit código de identificação da unidade de produto ou
	 * 0 (zero) para desconsiderar qualquer unidade de produto (nova unidade).
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existShortName(string $shorName, int $idProductUnit): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_units
				WHERE shortName = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $shorName);
		$query->setInteger(2, $idProductUnit);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se uma determinada unidade de produto está sendo utilizada por produtos.
	 * @param int $idProductUnit código de identificação da unidade de produto.
	 * @return bool true se estiver sendo utilizada ou false caso contrário.
	 */
	public function existOnProducts(int $idProductUnit): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM products
				WHERE idProductUnit = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductUnit);

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de unidade de produto.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ProductUnit|NULL objeto do tipo unidade de produto com dados carregados ou
	 * NULL se não houver resultado.
	 */
	private function parseProductUnit(Result $result): ?ProductUnit
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newProductUnit($entry);
	}

	/**
	 * Procedimento inerno para analisar o resultado de uma consulta e criar os objetos de unidade de produto.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ProductUnits aquisição da lista de unidades de produto a partir da consulta.
	 */
	private function parseProductUnits(Result $result): ProductUnits
	{
		$productUnits = new ProductUnits();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$productUnit = $this->newProductUnit($entry);
			$productUnits->add($productUnit);
		}

		return $productUnits;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo unidade de produto e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return ProductUnit aquisição de um objeto do tipo unidade de produto com dados carregados.
	 */
	private function newProductUnit(array $array): ProductUnit
	{
		$productUnit = new ProductUnit();
		$productUnit->fromArray($array);

		return $productUnit;
	}
}

