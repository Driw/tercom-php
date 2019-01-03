<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\dao\exceptions\DAOException;
use tercom\entities\TercomProfile;
use tercom\entities\lists\TercomProfiles;
use tercom\Functions;

/**
 * DAO para Perfil TERCOM
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos perfis TERCOM, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar, selecionar e excluir <b>se não referenciados</b>.
 *
 * Perfis TERCOM possui apenas um nome que deve ser único e um nível de assinatura.
 *
 * @see GenericDAO
 * @see TercomProfile
 * @see TercomProfiles
 *
 * @author Andrew
 */
class TercomProfileDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de perfis TERCOM.
	 */
	public const ALL_COLUMNS = ['id', 'name', 'assignmentLevel'];

	/**
	 * Procedimento interno para validação dos dados de um perfil TERCOM ao inserir e/ou atualizar.
	 * Perfis TERCOM não podem ter nome e nível de assinatura não informados.
	 * @param TercomProfile $tercomProfile objeto do tipo perfil TERCOM à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws DAOException caso algum dos dados do perfil TERCOM não estejam de acordo.
	 */
	private function validate(TercomProfile $tercomProfile, bool $validateId)
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($tercomProfile->getId() === 0)
				throw new DAOException('perfil não identificado');
		} else {
			if ($tercomProfile->getId() !== 0)
				throw new DAOException('perfil já identificado');
		}

		// NOT NULL
		if (StringUtil::isEmpty($tercomProfile->getName())) throw new DAOException('nome não definido');
		if ($tercomProfile->getAssignmentLevel() === 0) throw new DAOException('nível de assinatura não definido');
	}

	/**
	 * Insere um novo perfil TERCOM no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param TercomProfile $tercomProfile objeto do tipo perfil TERCOM à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(TercomProfile $tercomProfile): bool
	{
		$this->validate($tercomProfile, false);

		$sql = "INSERT INTO tercom_profiles (name, assignmentLevel)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $tercomProfile->getName());
		$query->setInteger(2, $tercomProfile->getAssignmentLevel());

		if (($result = $query->execute())->isSuccessful())
			$tercomProfile->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 * Atualiza os dados de um perfil TERCOM já existente no banco de dados.
	 * @param TercomProfile $tercomProfile objeto do tipo perfil TERCOM à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(TercomProfile $tercomProfile): bool
	{
		$this->validate($tercomProfile, true);

		$sql = "UPDATE tercom_profiles
				SET name = ?, assignmentLevel = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $tercomProfile->getName());
		$query->setInteger(2, $tercomProfile->getAssignmentLevel());
		$query->setInteger(3, $tercomProfile->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui os dados de um perfil TERCOM já existente no banco de dados.
	 * @param TercomProfile $tercomProfile objeto do tipo perfil TERCOM à excluir.
	 * @return bool true se for excluído ou false caso contrário.
	 * @throws DAOException um ou mais funcionários definidos no perfil.
	 */
	public function delete(TercomProfile $tercomProfile): bool
	{
		$this->validate($tercomProfile, true);

		if ($this->existOnTercomEmployees($tercomProfile))
			throw new DAOException('um ou mais funcionários definido no perfil');

		$sql = "DELETE FROM tercom_profiles
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newBaseSelect(): string
	{
		return "SELECT id, name, assignmentLevel
				FROM tercom_profiles";
	}

	/**
	 * Selecione os dados de um perfil TERCOM através do seu código de identificação único.
	 * @param int $idTercomProfile código de identificação único do perfil TERCOM.
	 * @return TercomProfile|NULL perfil TERCOM com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idTercomProfile): ?TercomProfile
	{
		$sqlBase = $this->newBaseSelect();
		$sql = "$sqlBase
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idTercomProfile);

		$result = $query->execute();

		return $this->parseTercomProfile($result);
	}

	/**
	 * Seleciona os dados de um perfil TERCOM através do seu nível de assinatura.
	 * @param int $assignmentLevel nível de assinatura máximo à filtrar.
	 * @return TercomProfiles aquisição da lista de perfil TERCOM conforme filtro.
	 */
	public function selectByAssignmentLevel(int $assignmentLevel): TercomProfiles
	{
		$sqlBase = $this->newBaseSelect();
		$sql = "$sqlBase
				WHERE assignmentLevel <= ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $assignmentLevel);

		$result = $query->execute();

		return $this->parseTercomProfiles($result);
	}

	/**
	 * Seleciona os dados de todos os perfil TERCOM registrados no banco de dados sem ordenação.
	 * @return TercomProfiles aquisição da lista de perfil TERCOM atualmente registrados.
	 */
	public function selectAll(): TercomProfiles
	{
		$sql = $this->newBaseSelect();

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseTercomProfiles($result);
	}

	/**
	 * Verifica se um determinado código de identificação de perfil TERCOM existe.
	 * @param int $idTercomProfile código de identificação único do perfil TERCOM.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function exist(int $idTercomProfile): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_profiles
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idTercomProfile);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado nome de perfil está disponível para perfil TERCOM.
	 * @param string $name nome do perfil à verificar.
	 * @param int $idTercomProfile código de identificação do perfil TERCOM à desconsiderar
	 * ou zero caso seja um novo perfil TERCOM.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existName(string $name, int $idTercomProfile): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_profiles
				WHERE name = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $name);
		$query->setInteger(2, $idTercomProfile);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um perfil TERCOM está sendo referenciado em algum funcionário TERCOM.
	 * @param TercomProfile $tercomProfile perfil TERCOM à ser verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existOnTercomEmployees(TercomProfile $tercomProfile): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_employees
				WHERE idTercomProfile = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de perfil TERCOM.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return TercomProfile|NULL objeto do tipo perfil TERCOM com dados carregados ou NULL se não houver resultado.
	 *
	 * @param Result $result
	 * @return TercomProfile|NULL
	 */
	private function parseTercomProfile(Result $result): ?TercomProfile
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newTercomProfile($entry);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar os objetos de perfil TERCOM.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return TercomProfiles aquisição da lista de perfis TERCOM a partir da consulta.
	 *
	 * @param Result $result
	 * @return TercomProfiles
	 */
	private function parseTercomProfiles(Result $result): TercomProfiles
	{
		$tercomProfiles = new TercomProfiles();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$tercomProfile = $this->newTercomProfile($entry);
			$tercomProfiles->add($tercomProfile);
		}

		return $tercomProfiles;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo perfil TERCOM e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return TercomProfile aquisição de um objeto do tipo perfil TERCOM com dados carregados.
	 */
	private function newTercomProfile(array $entry): TercomProfile
	{
		Functions::parseArrayJoin($entry);

		$tercomProfile = new TercomProfile();
		$tercomProfile->fromArray($entry);

		return $tercomProfile;
	}
}

