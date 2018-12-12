<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\Manufacturer;
use tercom\entities\lists\Manufacturers;
use tercom\exceptions\ManufacturerException;
use dProject\Primitive\StringUtil;

/**
 * DAO para Fabricante
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos fabricantes, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar, excluir e selcionar dados de fabricantes.
 *
 * Fabricantes possuem apenas o nome fantasia e este não pode ser repetido sendo único.
 *
 * @see GenericDAO
 * @see Manufacturer
 * @see Manufacturers
 *
 * @author Andrew
 */
class ManufacturerDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de fabricantes.
	 */
	public const ALL_COLUMNS = ['id', 'fantasyName'];

	/**
	 * Procedimento interno para validação dos dados de um fabricante ao inserir e/ou atualizar.
	 * Fornecedores não podem possuir nome fantasia em branco e devem ser únicos.
	 * @param Manufacturer $manufacturer objeto do tipo fabricante à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws ManufacturerException caso algum dos dados do fabricante não estejam de acordo.
	 */
	private function validate(Manufacturer $manufacturer, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($manufacturer->getId() === 0)
				throw ManufacturerException::newNotIdentified();
		} else {
			if ($manufacturer->getId() !== 0)
				throw ManufacturerException::newIdentified();
		}

		// NOT NULL
		if (StringUtil::isEmpty($manufacturer->getFantasyName())) throw ManufacturerException::newFantasyNameEmpty();

		// UNIQUE KEY
		if ($this->existFantasyName($manufacturer->getFantasyName(), $manufacturer->getId())) throw ManufacturerException::newFantasyNameUnavaiable();
	}

	/**
	 * Insere um novo fabricante no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param Manufacturer $manufacturer objeto do tipo fabricante à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(Manufacturer $manufacturer): bool
	{
		$this->validate($manufacturer, false);

		$sql = "INSERT INTO manufacturers (fantasyName)
				VALUES (?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $manufacturer->getFantasyName());

		if (($result = $query->execute())->isSuccessful())
			$manufacturer->setId($result->getInsertID());

		return $manufacturer->getId() !== 0;
	}

	/**
	 * Atualiza os dados de um fabricante já existente no banco de dados.
	 * @param Manufacturer $manufacturer objeto do tipo fabricante à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(Manufacturer $manufacturer): bool
	{
		$this->validate($manufacturer, true);

		$sql = "UPDATE manufacturers
				SET fantasyName = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $manufacturer->getFantasyName());
		$query->setInteger(2, $manufacturer->getId());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	/**
	 * Exclui os dados de um fabricante já existente no banco de dados.
	 * @param Manufacturer $manufacturer objeto do tipo fabricante à excluir.
	 * @return bool true se for excluído ou false caso contrário.
	 */
	public function dalete(Manufacturer $manufacturer): bool
	{
		if ($this->existOnProductPrice($manufacturer->getId()))
			throw ManufacturerException::newHasUses();

		$sql = "DELETE FROM manufacturers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $manufacturer->getId());

		$result = $query->execute();

		return $result->getAffectedRows() > 0;
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		return "SELECT id, fantasyName
				FROM manufacturers";
	}

	/**
	 * Selecione os dados de um fabricante através do seu código de identificação único.
	 * @param int $idManufacturer código de identificação único do fabricante.
	 * @return Manufacturer|NULL fabricante com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idManufacturer): ?Manufacturer
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idManufacturer);

		$result = $query->execute();

		return $this->parseManufacturer($result);
	}

	/**
	 * Seleciona os dados de todos os fabricantes registrados no banco de dados sem ordenação.
	 * @return Manufacturers aquisição da lista de fabricantes atualmente registrados.
	 */
	public function selectAll(): Manufacturers
	{
		$sql = $this->newSelect();
		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseManufacturers($result);
	}

	/**
	 * Seleciona os dados dos fabricantes no banco de dados filtrados pelo nome fantasia.
	 * @param string $fantasyName número parcial ou completo do nome fantasia para filtro.
	 * @return Manufacturers aquisição da lista de fabricantes conforme filtro.
	 */
	public function selectLikeFantasyName(string $fantasyName): Manufacturers
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE fantasyName LIKE ?
				ORDER BY fantasyName";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$fantasyName%");

		$result = $query->execute();

		return $this->parseManufacturers($result);
	}

	/**
	 * Verifica se um determinado código de identificação de fabricante existe.
	 * @param int $idManufacturer código de identificação único do fabricante.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function exist(int $idManufacturer): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM manufacturers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idManufacturer);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado nome fantasia está disponível para um fabricante.
	 * @param string $fantasyName nome fantasia à ser verificado.
	 * @param int $idManufacturerr código de identificação do fabricante à desconsiderar
	 * ou zero caso seja um novo fabricante.
	 * @return bool true se estiver disponível ou false caso contrário.
	 */
	public function existFantasyName(string $fantasyName, int $idManufacturerr): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM manufacturers
				WHERE fantasyName = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $fantasyName);
		$query->setInteger(2, $idManufacturerr);

		return $this->parseQueryExist($query);
	}


	/**
	 * Verifica se um determinado código de identificação de fabricante está em uso.
	 * @param int $idManufacturer código de identificação único do fabricante.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existOnProductPrice(int $idManufacturer): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM product_prices
				WHERE idManufacturer = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idManufacturer);

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de fabricante.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Manufacturer|NULL objeto do tipo fabricante com dados carregados ou NULL se não houver resultado.
	 */
	private function parseManufacturer(Result $result): ?Manufacturer
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newManufacturer($entry);
	}

	/**
	 * Procedimento inerno para analisar o resultado de uma consulta e criar os objetos de fabricante.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Manufacturers aquisição da lista de fabricantes a partir da consulta.
	 */
	private function parseManufacturers(Result $result): Manufacturers
	{
		$manufacturers = new Manufacturers();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$manufacturer = $this->newManufacturer($entry);
			$manufacturers->add($manufacturer);
		}

		return $manufacturers;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo fabricante e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return Manufacturer aquisição de um objeto do tipo fabricante com dados carregados.
	 */
	private function newManufacturer(array $entry): Manufacturer
	{
		$manufacturer = new Manufacturer();
		$manufacturer->fromArray($entry);

		return $manufacturer;
	}
}

