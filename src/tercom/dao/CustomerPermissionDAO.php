<?php

namespace tercom\dao;

use tercom\entities\CustomerProfile;
use tercom\entities\Permission;
use tercom\entities\lists\Permissions;
use tercom\dao\exceptions\DAOException;
use dProject\MySQL\Result;

/**
 * DAO para Permissão de Cliente
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes as permissões dos clientes vinculadas a um perfil de cliente.
 * Estas operações consiste em: adicionar, atualizar e selecionar e excluir permissões por perfil de cliente.
 *
 * @see Customer
 * @see CustomerProfile
 * @see Permission
 * @see Permissions
 * @see GenericDAO
 *
 * @author Andrew
 */
class CustomerPermissionDAO extends GenericDAO
{
	/**
	 * Procedimento interno para validação dos dados da permissão e perfil de cliente ao inserir e/ou atualizar.
	 * Tanto o perfil de cliente quanto a permissão devem existir e terem sido informadas.
	 * @param CustomerProfile $customerProfile objeto do tipo perfil de cliente.
	 * @param Permission $permission objeto do tipo permissão que será vinculada ao perfil de cliente.
	 * @throws DAOException caso algum dos dados não estejam de acordo.
	 */
	private function validate(CustomerProfile $customerProfile, Permission $permission)
	{
		// FIXME trocar DAOException por CustomerPermissionException

		// PRIMARY KEY
		if ($customerProfile->getId() === 0) throw new DAOException('perfil de cliente não identificado');
		if ($permission->getId() === 0) throw new DAOException('permissão não identificada');
	}

	/**
	 * Insere uma nova permissão à lista de permissões de um perfil de cliente no banco de dados.
	 * @param CustomerProfile $customerProfile objeto do tipo perfil de cliente.
	 * @param Permission $permission objeto do tipo permissão à adicionar ao perfil de cliente.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(CustomerProfile $customerProfile, Permission $permission): bool
	{
		$this->validate($customerProfile, $permission);

		$sql = "INSERT INTO customer_profile_permissions (idCustomerProfile, idPermission)
				VALUES (?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());
		$query->setInteger(2, $permission->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui uma permissão da lista de permissões de um perfil de cliente no banco de dados.
	 * @param CustomerProfile $customerProfile objeto do tipo perfil de cliente.
	 * @param Permission $permission objeto do tipo permissão à adicionar ao perfil de cliente.
	 * @return bool true se conseguir excluir ou false caso contrário.
	 */
	public function delete(CustomerProfile $customerProfile, Permission $permission): bool
	{
		$this->validate($customerProfile, $permission);

		$sql = "DELETE FROM customer_profile_permissions
				WHERE idCustomerProfile = ? AND idPermission = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());
		$query->setInteger(2, $permission->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * Query com INNER JOIN para os dados da permissão.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelectPermission(): string
	{
		$permissionColumns = $this->buildQuery(PermissionDAO::ALL_COLUMNS, 'permissions');

		return "SELECT $permissionColumns
				FROM permissions
				INNER JOIN customer_profile_permissions ON customer_profile_permissions.idPermission = permissions.id";
	}

	/**
	 * Selecione os dados de uma permissão vinculada a um perfil de cliente através do seu código de identificação.
	 * @param CustomerProfile $customerProfile objeto do tipo perfil de cliente à filtrar.
	 * @param int $idCustomerEmployee código de identificação único da permissão.
	 * @return Permission|NULL permissão com os dados carregados ou NULL se não encontrado.
	 */
	public function select(CustomerProfile $customerProfile, int $idPermission): ?Permission
	{
		$sqlPermission = $this->newSelectPermission();
		$sql = "$sqlPermission
				WHERE customer_profile_permissions.idCustomerProfile = ? AND customer_profile_permissions.idPermission = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());
		$query->setInteger(2, $idPermission);

		$result = $query->execute();

		return $this->parsePermission($result);
	}

	/**
	 * Seleciona os dados de todas as permissões vinculadas a um perfil de cliente.
	 * @param CustomerProfile $customerProfile objeto do tipo perfil de cliente à filtrar.
	 * @return Permission aquisição da lista de permissões do perfil de cliente.
	 */
	public function selectByCustomer(CustomerProfile $customerProfile): Permissions
	{
		$sqlPermission = $this->newSelectPermission();
		$sql = "$sqlPermission
				WHERE customer_profile_permissions.idCustomerProfile = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());

		$result = $query->execute();

		return $this->parsePermissions($result);
	}

	/**
	 * Verifica se uma determinada permissão já foi vinculada a um perfil de cliente.
	 * @param CustomerProfile $customerProfile objeto do tipo perfil de cliente à considerar.
	 * @param Permission $permission objeto do tipo permissão à considerar.
	 * @return bool true se já existir ou false caso contrário.
	 */
	public function exist(CustomerProfile $customerProfile, Permission $permission): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_profile_permissions
				WHERE idCustomerProfile = ? AND idPermission = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerProfile->getId());
		$query->setInteger(2, $permission->getId());

		$result = $query->execute();
		$entry = $result->next();
		$result->free();

		return intval($entry['qty']) > 0;
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

