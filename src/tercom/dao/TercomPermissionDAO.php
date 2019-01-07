<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\dao\exceptions\DAOException;
use tercom\entities\Permission;
use tercom\entities\TercomProfile;
use tercom\entities\lists\Permissions;

/**
 * DAO para Permissão de Perfil TERCOM
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes as permissões de perfil TERCOM, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar, selecionar e excluir permissões TERCOM.
 *
 * Toda permissão adicionada deve ser vinculada a um perfil TERCOM, quando um perfil é excluído as permissões são removidas.
 * Permissões removidas de perfis não são excluídas do sistema, apenas são excluídas da lista de permissões do perfil.
 *
 * @see GenericDAO
 * @see TercomProfile
 * @see Permission
 * @see Permissions
 *
 * @author Andrew
 */
class TercomPermissionDAO extends GenericDAO
{
	/**
	 * Procedimento interno para validação dos dados de uma permissão para perfil TERCOM ao inserir e/ou atualizar.
	 * A permissão e perfil TERCOM devem ter sido informadas e existir no sistema.
	 * @param TercomProfile $tercomProfile
	 * @param Permission $permission
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws DAOException caso algum dos dados da permissão  não estejam de acordo.
	 */
	private function validate(TercomProfile $tercomProfile, Permission $permission)
	{
		// FIXME trocar DAOException para TercomPermissionException

		// NOT NULL
		if ($tercomProfile->getId() === 0) throw new DAOException('perfil da TERCOM não informado');
		if ($permission->getId() === 0) throw new DAOException('permissão não informado');

		// FOREIGN KEY
		if (!$this->existTercomProfile($tercomProfile)) throw new DAOException('perfil da TERCOM desconhecido');
		if (!$this->existPermission($permission)) throw new DAOException('permissão desconhecido');
	}

	/**
	 * Insere uma nova permissão à lista de permissões de um perfil TERCOM.
	 * @param TercomProfile $tercomProfile objeto do tipo perfil TERCOM.
	 * @param Permission $permission objeto do tipo permissão à inserir.
	 * @return bool true se conseguir inserir ou false caso contrário.
	 */
	public function insert(TercomProfile $tercomProfile, Permission $permission): bool
	{
		$this->validate($tercomProfile, $permission);

		$sql = "INSERT INTO tercom_profile_permissions (idTercomProfile, idPermission)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());
		$query->setInteger(2, $permission->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui uma nova permissão da lista de permissões de um perfil TERCOM.
	 * @param TercomProfile $tercomProfile objeto do tipo perfil TERCOM.
	 * @param Permission $permission objeto do tipo permissão à excluir.
	 * @return bool true se conseguir excluir ou false caso contrário.
	 */
	public function delete(TercomProfile $tercomProfile, Permission $permission): bool
	{
		$this->validate($tercomProfile, $permission);

		$sql = "DELETE FROM tercom_profile_permissions
				WHERE idTercomProfile = ? AND idPermission = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());
		$query->setInteger(2, $permission->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelectPermission(): string
	{
		$permissionColumns = $this->buildQuery(PermissionDAO::ALL_COLUMNS, 'permissions');

		return "SELECT $permissionColumns
				FROM permissions
				INNER JOIN tercom_profile_permissions ON tercom_profile_permissions.idPermission = permissions.id";
	}

	/**
	 * Selecione os dados de uma permissão da lista de permissões de um perfil TERCOM através do seu código de identificação único.
	 * @param TercomProfile $tercomProfile objeto do tipo perfil TERCOM.
	 * @param int $idPermission código de identificação da permissão à selecionar.
	 * @return Permission|NULL fornecedor com os dados carregados ou NULL se não encontrado.
	 */
	public function select(TercomProfile $tercomProfile, int $idPermission): ?Permission
	{
		$sqlPermission = $this->newSelectPermission();
		$sql = "$sqlPermission
				WHERE tercom_profile_permissions.idTercomProfile = ? AND tercom_profile_permissions.idPermission = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());
		$query->setInteger(2, $idPermission);

		$result = $query->execute();

		return $this->parsePermission($result);
	}

	/**
	 * Seleciona os dados de todas as permissões da lista de permissões de um perfil TERCOM.
	 * @param TercomProfile $tercomProfile objeto do tipo perfil TERCOM.
	 * @return Permissions aquisição da lista de permissões selecionadas.
	 */
	public function selectByTercom(TercomProfile $tercomProfile): Permissions
	{
		$sqlPermission = $this->newSelectPermission();
		$sql = "$sqlPermission
				WHERE tercom_profile_permissions.idTercomProfile = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());

		$result = $query->execute();

		return $this->parsePermissions($result);
	}

	/**
	 * Verifica se um perfil TERCOM possui uma permissão em sua lista de permissões.
	 * @param TercomProfile $tercomProfile objeto do tipo perfil TERCOM.
	 * @param Permission $permission objeto do tipo permissão à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function exist(TercomProfile $tercomProfile, Permission $permission): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_profile_permissions
				WHERE idTercomProfile = ? AND idPermission = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());
		$query->setInteger(2, $permission->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado perfil TERCOM existe no sistema.
	 * @param TercomProfile $tercomProfile objeto do tipo perfil TERCOM à verficiar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existTercomProfile(TercomProfile $tercomProfile): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_profiles
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomProfile->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se uma determinada permissão existe no sistema.
	 * @param Permission $permission objeto do tipo permissão à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existPermission(Permission $permission): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM permissions
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $permission->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de permissão.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Permission|NULL objeto do tipo permissão com dados carregados ou NULL se não houver resultado.
	 */
	private function parsePermission(Result $result): ?Permission
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newPermission($entry);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar os objetos de permissão.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Permissions aquisição da lista de permissões a partir da consulta.
	 */
	private function parsePermissions(Result $result): Permissions
	{
		$permissions = new Permissions();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$permission = $this->newPermission($entry);
			$permissions->add($permission);
		}

		return $permissions;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo permissão e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return Permission aquisição de um objeto do tipo permissão com dados carregados.
	 */
	private function newPermission(array $entry): Permission
	{
		$permission = new Permission();
		$permission->fromArray($entry);

		return $permission;
	}
}

