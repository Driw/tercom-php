<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\ProductCategory;
use tercom\entities\lists\ProductCategories;
use tercom\exceptions\ProductCategoryException;
use dProject\MySQL\Query;

/**
 * DAO para Categoria de Produto
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes as categorias de produto, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar, selecionar e excluir <b>se não referenciados</b>.
 *
 * Categoria de produto obrigatoriamente precisa ter o nome e o tipo que determina sua hierarquia.
 *
 * @see GenericDAO
 * @see ProductCategory
 * @see ProductCategories
 *
 * @author Andrew
 */
class ProductCategoryDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de categorias de produto.
	 */
	public const ALL_COLUMNS = ['id', 'name'];

	/**
	 * Procedimento interno para validação dos dados de uma categoria de produto ao inserir e/ou atualizar.
	 * Categorias de produto não podem ter nome e tipo não informados.
	 * @param ProductCategory $productCategory objeto do tipo categoria de produto à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws ProductCategoryException caso algum dos dados da categoria de produto não estejam de acordo.
	 */
	private function validate(ProductCategory $productCategory, bool $validateId)
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($productCategory->getId() === 0)
				throw ProductCategoryException::newNotIdentified();
		} else {
			if ($productCategory->getID() !== 0)
				throw ProductCategoryException::newIdentified();
		}

		// NOT NULL
		if (empty($productCategory->getName())) throw ProductCategoryException::newNameEmpty();
		if ($productCategory->getType() === 0) throw ProductCategoryException::newTypeEmpty();

		// UNIQUE KEY
		if ($this->existName($productCategory->getName(), $productCategory->getId())) throw ProductCategoryException::newNameUnavaiable();

		// FOREIGN KEY
		if (!$this->existType($productCategory->getType())) throw ProductCategoryException::newTypeInvalid();
	}

	/**
	 * Insere uma nova categoria de produto no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param ProductCategory $productCategory objeto do tipo categoria de produto à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(ProductCategory $productCategory): bool
	{
		$this->validate($productCategory, false);

		$sql = "INSERT INTO product_categories (name)
				VALUES (?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $productCategory->getName());

		if (($result = $query->execute())->isSuccessful())
			$productCategory->setId($result->getInsertID());

		return $productCategory->getId() !== 0;
	}

	/**
	 * Substitui uma relação entre categorias de produto por uma nova conforme os parâmetros:
	 * @param ProductCategory $productCategory objeto do tipo categoria de produto à ser relacionada.
	 * @param ProductCategory $productCategoryParent objeto do tipo categoria de produto para relacionamento.
	 * @return bool true se o conseguir substituir ou false caso contrário.
	 */
	public function replaceRelationship(ProductCategory $productCategory, ProductCategory $productCategoryParent): bool
	{
		if ($productCategoryParent->getId() === 0)
			throw ProductCategoryException::newParentNotIdentified();

		$this->validate($productCategory, true);

		$sql = "REPLACE INTO product_category_relationships (idCategoryParent, idCategory, idCategoryType)
				VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productCategoryParent->getId());
		$query->setInteger(2, $productCategory->getId());
		$query->setInteger(3, $productCategory->getType());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Aualiza uma categoria de produto já existente no banco de dados.
	 * @param ProductCategory $productCategory objeto do tipo categoria de produto à atualizar.
	 * @return bool true se conseguir atualizar ou false caso contrário.
	 */
	public function update(ProductCategory $productCategory): bool
	{
		$sql = "UPDATE product_categories
				SET name = ?
				WHERE id = ?";

		$this->validate($productCategory, true);

		$query = $this->createQuery($sql);
		$query->setString(1, $productCategory->getName());
		$query->setInteger(2, $productCategory->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui uma categoria de produto já existente no banco de dados.
	 * @param ProductCategory $productCategory objeto do tipo categoria de produto à excluir.
	 * @return bool true se conseguir excluir ou false caso contrário.
	 */
	public function delete(ProductCategory $productCategory): bool
	{
		if ($this->existOnRelationship($productCategory->getId()))
			throw ProductCategoryException::newExistOnRelationship();

		if ($this->existOnProduct($productCategory->getId()))
			throw ProductCategoryException::newExistOnProduct();

		$sql = "DELETE FROM product_categories
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productCategory->getId());

		return ($query->execute())->getAffectedRows() === 1;
	}

	/**
	 * Exclui uma relação entre categorias de produto já existentes no banco de dados.
	 * @param ProductCategory $productCategory objeto do tipo categoria de produto à excluir.
	 * @param int $idProductCategoryType código do tipo de categoria de produto à ser excluido.
	 * @return bool true se conseguir excluir ou false caso contrário.
	 */
	public function deleteRelationship(ProductCategory $productCategory, int $idProductCategoryType): bool
	{
		$sql = "DELETE FROM product_category_relationships
				WHERE idCategory = ? AND idCategoryType = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productCategory->getId());
		$query->setInteger(2, $idProductCategoryType);

		return ($query->execute())->getAffectedRows() > 0;
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		$productCategoriesColumns = $this->buildQuery(self::ALL_COLUMNS, 'product_categories');

		return "SELECT $productCategoriesColumns, product_category_types.id type
				FROM product_categories
				LEFT JOIN product_category_relationships ON product_category_relationships.idCategory = product_categories.id
				LEFT JOIN product_category_types ON product_category_types.id = product_category_relationships.idCategoryType";
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelectFamilies(): string
	{
		$productCategoriesColumns = $this->buildQuery(self::ALL_COLUMNS, 'product_categories');

		return "SELECT $productCategoriesColumns, 1 type
				FROM product_category_relationships
			    INNER JOIN product_categories ON product_categories.id = product_category_relationships.idCategoryParent
			    INNER JOIN product_category_types ON product_category_types.id = product_category_relationships.idCategoryType
				WHERE product_category_relationships.idCategoryType = 2
				UNION
				SELECT $productCategoriesColumns, 1 type
				FROM product_categories
				LEFT JOIN product_category_relationships ON (
					product_category_relationships.idCategoryParent = product_categories.id OR
					product_category_relationships.idCategory = product_categories.id
				)
				LEFT JOIN product_category_types ON product_category_types.id = product_category_relationships.idCategoryType
				WHERE product_category_relationships.idCategoryType IS NULL";
	}

	/**
	 * Selecione os dados de uma categoria de produto através do seu código de identificação único e tipo de categoria.
	 * @param int $idProductCategory código de identificação único da categoria de produto à selecionar.
	 * @param int $idProductCategoryType código de identificação único do tipo de categoria a considerar.
	 * @return ProductCategory|NULL categoria de produto com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idProductCategory, int $idProductCategoryType = ProductCategory::CATEGORY_NONE): ?ProductCategory
	{
		// Quando é família não vai possuir nenhuma relação de categoria parent, logo o tipo será nulo
		if ($idProductCategoryType === ProductCategory::CATEGORY_NONE || $idProductCategoryType === ProductCategory::CATEGORY_FAMILY)
			$idProductCategoryType = null;

		$sqlType = $idProductCategoryType === null ? 'IS NULL' : '= ?';
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE product_categories.id = ? AND product_category_types.id $sqlType";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategory);
		if ($idProductCategoryType !== null)
		$query->setInteger(2, $idProductCategoryType);

		$result = $query->execute();

		return $this->parseProductCategory($result);
	}

	/**
	 * Seleciona os dados de uma categoria de produto através do seu nome.
	 * @param string $name nome da categoria de produto à ser selecionada.
	 * @return ProductCategory|NULL categoria de produto com os dados carregados ou NULL se não encontrado.
	 */
	public function selectByName(string $name): ?ProductCategory
	{
		$sql = "SELECT id, name
				FROM product_categories
				WHERE name = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);

		$result = $query->execute();

		return $this->parseProductCategory($result);
	}

	/**
	 * Seleciona os dados de todas as categorias de produto registradas no banco de dados sem ordenação.
	 * @param int $idProductCategoryType tipo da categoria de produto do qual deseja listar tudo.
	 * @return ProductCategories aquisição da lista de categorias de produto atualmente registrados.
	 */
	public function selectAll(int $idProductCategoryType): ProductCategories
	{
		switch ($idProductCategoryType)
		{
			case ProductCategory::CATEGORY_NONE:
				$sql = "SELECT id, name
						FROM product_categories";
				$query = $this->createQuery($sql);
				break;

			case ProductCategory::CATEGORY_FAMILY:
				$sql = $this->newSelectFamilies();
				$query = $this->createQuery($sql);
				break;

			case ProductCategory::CATEGORY_GROUP:
			case ProductCategory::CATEGORY_SUBGROUP:
			case ProductCategory::CATEGORY_SECTOR:
				$sqlSelect = $this->newSelect();
				$sql = "$sqlSelect
						WHERE product_category_types.id = ?";
				$query = $this->createQuery($sql);
				$query->setInteger(1, $idProductCategoryType);
				break;
		}

		$result = $query->execute();

		return $this->parseProductCategories($result);
}

	/**
	 * Seleciona os dados de todas as categorias de produto relacionadas a uma outra categoria de produto.
	 * @param ProductCategory $productCategory objeto do tipo categoria de produto à ser filtrada.
	 * @param int $idProductCategory código de identificação do tipo de categoria à ser filtrada.
	 * @return ProductCategories aquisição da lista de categorias de produto conforme filtros.
	 */
	public function selectAllFamilies(): ProductCategories
	{
		$sqlSelect = $this->newSelectFamilies();
		$sql = "SELECT families.*
				FROM ($sqlSelect) families
				ORDER BY name ASC";

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseProductCategories($result);
	}

	/**
	 * Seleciona os dados de todas as categorias de produto relacionadas a uma outra categoria de produto.
	 * @param ProductCategory $productCategory objeto do tipo categoria de produto à ser filtrada.
	 * @param int $idProductCategory código de identificação do tipo de categoria à ser filtrada.
	 * @return ProductCategories aquisição da lista de categorias de produto conforme filtros.
	 */
	public function selectByCategory(ProductCategory $productCategory, int $idProductCategoryType): ProductCategories
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
						WHERE product_category_relationships.idCategoryParent = ? AND product_category_relationships.idCategoryType = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $productCategory->getId());
		$query->setInteger(2, $idProductCategoryType);

		$result = $query->execute();

		return $this->parseProductCategories($result);
	}

	/**
	 * Seleciona os dados de todas as categorias de produto por nome parcial e/ou completo.
	 * @param string $name nome parcial e/ou completo da categoria de produto.
	 * @param int $idProductCategory código de identificação do tipo de categoria à ser filtrada.
	 * @return ProductCategories aquisição da lista de categorias de produto conforme filtros.
	 */
	public function selectLikeName(string $name, int $idProductCategoryType = 0): ProductCategories
	{
		$sqlSelect = $this->newSelect();

		switch ($idProductCategoryType)
		{
			case 0:
			case ProductCategory::CATEGORY_FAMILY:
				$sqlFamily = $idProductCategoryType === ProductCategory::CATEGORY_FAMILY ? 'AND product_category_types.id IS NULL' : '';
				$sql = "$sqlSelect
				WHERE product_categories.name LIKE ? $sqlFamily";
				$query = $this->createQuery($sql);
				$query->setString(1, "%$name%");
				break;

			default:
				$sql = "$sqlSelect
				WHERE product_categories.name LIKE ? AND product_category_types.id = ?";
				$query = $this->createQuery($sql);
				$query->setString(1, "%$name%");
				$query->setInteger(2, $idProductCategoryType);
				break;
		}

		return $this->selectQuery($query);
	}

	/**
	 * Procedimento interno usado para concluir a execução de queries que retornem uma lista de categorias de produto.
	 * @param Query $query objeto do tipo consulta à ser executada e analisada os resultados.
	 * @return ProductCategories aquisição da lista de categorias de produto consultadas.
	 */
	private function selectQuery(Query $query): ProductCategories
	{
		$result = $query->execute();

		return $this->parseProductCategories($result);
	}

	/**
	 * Verifica se um determinado código de identificação de fornecedor existe.
	 * @param int $idProductCategory código de identificação único do fornecedor.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function exist(int $idProductCategory): bool
	{
		$sql = "SELECT COUNT(*) AS qtd
				FROM product_categories
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategory);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado nome para uma categoria de produto existe.
	 * @param string $name nome da categoria de produto à verificar.
	 * @param int $idProductCategory código de identificação da categoria de produto à desconsiderar
	 * ou zero caso seja uma nova categoria de produto.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existName(string $name, int $idProductCategory = 0): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_categories
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idProductCategory);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se uma categoria de produto existe em um determinado tipo de relacionamento entre categorias.
	 * @param int $idProductCategory código de identificação da categoria de produto à verificar.
	 * @param int $idProductCategoryType código de identificação do tipo de categoria de produto à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existRelationship(int $idProductCategory, int $idProductCategoryType): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_category_relationships
				WHERE idCategory = ? AND idCategoryType = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $idProductCategory);
		$query->setInteger(2, $idProductCategoryType);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação de tipo de categoria de produto existe.
	 * @param int $idProductCategoryType código de identificação de tipo de categoria de produto à veirficar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existType(int $idProductCategoryType): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_category_types
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategoryType);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se uma categoria de produto possui algum relacionamento entre categorias de produto.
	 * @param int $idProductCategory código de identificação único da categoria de produto à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existOnRelationship(int $idProductCategory): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_category_relationships
				WHERE idCategoryParent = ? OR idCategory = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategory);
		$query->setInteger(2, $idProductCategory);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se uma categoria de produto existe como referência em algum produto.
	 * @param int $idProductCategory código de identificação único da categoria de produto à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existOnProduct(int $idProductCategory): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM products
				WHERE idProductCategory = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProductCategory);

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de categoria de produto.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ProductCategory|NULL objeto do tipo categoria de produto com dados carregados ou NULL se não houver resultado.
	 */
	private function parseProductCategory(Result $result): ?ProductCategory
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newProductCategory($entry);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar os objetos de categoria de produto.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ProductCategories aquisição da lista de categorias de produto a partir da consulta.
	 */
	private function parseProductCategories(Result $result): ProductCategories
	{
		$productCategories = new ProductCategories();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$productCategory = $this->newProductCategory($entry);
			$productCategories->add($productCategory);
		}

		return $productCategories;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo categoria de produto e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return ProductCategory aquisição de um objeto do tipo categoria de produto com dados carregados.
	 */
	private function newProductCategory(array $entry): ProductCategory
	{
		if (!isset($entry['type']) || $entry['type'] === null)
			$entry['type'] = ProductCategory::CATEGORY_FAMILY;

		$productCategory = new ProductCategory();
		$productCategory->fromArray($entry);

		return $productCategory;
	}
}
