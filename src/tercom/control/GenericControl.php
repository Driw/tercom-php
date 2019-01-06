<?php

namespace tercom\control;

use tercom\entities\Customer;

/**
 * @author Andrew
 */
class GenericControl
{
	/**
	 * @var int resultado de uma query de replace sem resultado.
	 */
	const REPLACE_NONE = 0;
	/**
	 * @var int resultado de uma query de replace que adicionou um registro.
	 */
	const REPLACE_INSERTED = 1;
	/**
	 * @var int resultado de uma query de replace que substituiu um registro.
	 */
	const REPLACE_UPDATED = 2;


	/**
	 * @var int entrada duplicada.
	 */
	const ER_DUP_ENTRY = 1062;
	/**
	 * @var int não pode atualizar/inserir uma coluna por falha com chave estrangeira
	 */
	const ER_NO_REFERENCED_ROW_2 = 1452;

	/**
	 * @var bool modo de gerenciamento por acesso TERCOM.
	 */
	private static $tercomManagement = false;
	/**
	 * @var Customer cliente atualmente acessado no sistema.
	 */
	private static $customerLogged = null;

	/**
	 * @return boolean aquisição se o sistema deve estar em modo de gerenciamento por acesso TERCOM.
	 */
	public static function isTercomManagement()
	{
		return self::$tercomManagement;
	}

	/**
	 * Quando este modo é habilitado algumas operações adicionais podem ser feitas por ser um funcionário TERCOM.
	 * @param boolean $tercomManagement sistema deve estar em modo de gerenciamento por acesso TERCOM.
	 */
	public static function setTercomManagement($tercomManagement)
	{
		self::$tercomManagement = $tercomManagement;
	}

	/**
	 * @return Customer aquisição do cliente atualmente acessado no sistema.
	 */
	public static function getCustomerLogged(): Customer
	{
		return GenericControl::$customerLogged;
	}

	/**
	 * @return bool true se o usuário acessado for um cliente.
	 */
	public static function hasCustomerLogged(): bool
	{
		return GenericControl::$customerLogged !== null;
	}

	/**
	 * @param Customer $customerLogged cliente atualmente acessado no sistema.
	 */
	public static function setCustomerLogged(Customer $customerLogged): void
	{
		GenericControl::$customerLogged = $customerLogged;
	}
}

