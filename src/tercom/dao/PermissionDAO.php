<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\dao\exceptions\DAOException;
use tercom\entities\Permission;
use tercom\entities\lists\Permissions;

/**
 * DAO para Permissão
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes as permissões, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar e selecionar e excluir permissões (se não referenciadas).
 *
 * Permissões devem estar dentro de um pacote, possuir o nome da ação e nível de assinatura mínimo
 * O pacote indica o agrupamento da permissão que geralmente é o nome do serviço,
 * A ação corresponde a ação dentro do serviço e o nível de assinatura para restringir usuários de baixo nível.
 *
 * @see GenericDAO
 * @see Permission
 * @see Permissions
 *
 * @author andrews
 */
class PermissionDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de permissões.
	 */
	public const ALL_COLUMNS = ['id', 'packet', 'action', 'assignmentLevel'];

	/**
	 * Procedimento interno para validação dos dados de uma permissão ao inserir e/ou atualizar.
	 * Permissões precisam ter pacote, ação e nível de assinatura informados.
	 * @param Permission $permission objeto do tipo permissão à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws DAOException caso algum dos dados do permissão não estejam de acordo.
	 */
	private function validatePermission(Permission $permission, bool $validateId)
	{
		// FIXME trocar DAOException para PermissionException

		// PRIMARY KEY
		if ($validateId) {
			if ($permission->getId() === 0)
				throw new DAOException('permissão não identificada');
		} else {
			if ($permission->getId() !== 0)
				throw new DAOException('permissão já identificada');
		}

		// NOT NULL
		if (StringUtil::isEmpty($permission->getPacket())) throw new DAOException('pacote não informado');
		if (StringUtil::isEmpty($permission->getAction())) throw new DAOException('ação não informada');
		if ($permission->getAssignmentLevel() < 0) throw new DAOException('nível de assinatura inválido');
	}

	/**
	 * Insere uma nova permissão no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param Permission $permission objeto do tipo permissão à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(Permission $permission): bool
	{
		$this->validatePermission($permission, false);

		$sql = "INSERT INTO permissions (packet, action, assignmentLevel)
				VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setString(1, $permission->getPacket());
		$query->setString(2, $permission->getAction());
		$query->setInteger(3, $permission->getAssignmentLevel());

		if (($result = $query->execute())->isSuccessful())
			$permission->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 * Atualiza os dados de uma permissão já existente no banco de dados.
	 * @param Permission $permission objeto do tipo permissão à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(Permission $permission): bool
	{
		$this->validatePermission($permission, true);

		$sql = "UPDATE permissions
				SET packet = ?, action = ?, assignmentLevel = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $permission->getPacket());
		$query->setString(2, $permission->getAction());
		$query->setInteger(3, $permission->getAssignmentLevel());
		$query->setInteger(4, $permission->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui os dados de uma permissão já existente no banco de dados.
	 * @param Permission $permission objeto do tipo permissão à excluir.
	 * @return bool true se for excluido ou false caso contrário.
	 * @throws DAOException se estiver sendo referênciado.
	 */
	public function delete(Permission $permission): bool
	{
		$this->validatePermission($permission, true);

		if ($this->existOnTercomProfiles($permission)) throw new DAOException('permissão referenciada e um ou mais perfis TERCOM');
		if ($this->existOnCustomerProfiles($permission)) throw new DAOException('permissão referenciada e um ou mais perfis de cliente');

		$sql = "DELETE FROM permissions
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $permission->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		return "SELECT id, packet, action, assignmentLevel
				FROM permissions";
	}

	/**
	 * Selecione os dados de uma permissão através do seu código de identificação único.
	 * @param int $idPermission código de identificação único da permissão.
	 * @return Permission|NULL permissão com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idPermission): ?Permission
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idPermission);

		$result = $query->execute();
		$permission = $this->parsePermission($result);

		return $permission;
	}

	/**
	 * Selecione os dados de uma permissão através do seu pacote e ação.
	 * @param string $packet nome do pacote da permissão à selecionar.
	 * @param string $action nome da ação da permissão à selecionar.
	 * @return Permission|NULL permissão com os dados carregados ou NULL se não encontrado.
	 */
	public function selectByPacketAction(string $packet, string $action): ?Permission
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE packet = ? AND action = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $packet);
		$query->setString(2, $action);

		$result = $query->execute();
		$permission = $this->parsePermission($result);

		return $permission;
	}

	/**
	 * Selecione os dados de todas as permissões no banco de dados por pacote.
	 * @param string $packet nome do pacote das permissões à filtrar.
	 * @return Permissions aquisição da lista de permissões filtradas.
	 */
	public function selectByPacket(string $packet): Permissions
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE packet = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $packet);

		$result = $query->execute();
		$permissions = $this->parsePermissions($result);

		return $permissions;
	}

	/**
	 * Verifica se uma determinada ação existe dentro de um pacote.
	 * @param string $packet nome do pacote da permissão à verfiicar.
	 * @param string $action nome da ação da permissão à verfiicar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existAction(string $packet, string $action, int $idPermission): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM permissions
				WHERE packet = ? AND action = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $packet);
		$query->setString(2, $action);
		$query->setInteger(3, $idPermission);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se uma permissão está referenciada em um perfil TERCOM.
	 * @param Permission $permission objeto do tipo permissão à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existOnTercomProfiles(Permission $permission): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM tercom_profile_permissions
				WHERE idPermission = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $permission->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se uma permissão está referenciada em um perfil de cliente.
	 * @param Permission $permission objeto do tipo permissão à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existOnCustomerProfiles(Permission $permission): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM customer_profile_permissions
				WHERE idPermission = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $permission->getId());

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

