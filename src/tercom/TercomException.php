<?php

namespace tercom;

use tercom\api\ApiStatus;

/**
 * @see \Exception
 * @author Andrew
 */
class TercomException extends \Exception
{
	/**
	 * @return TercomException
	 */
	public static function newApiConnection(): TercomException
	{
		return new TercomException('conexão de API não inicializada', ApiStatus::API_CONNECTION);
	}

	/**
	 * @return TercomException
	 */
	public static function newPermissionNotEnought(): TercomException
	{
		return new TercomException('permissão insuficiente', ApiStatus::PERMISSION_NOT_ENOUGHT);
	}

	/**
	 * @return TercomException
	 */
	public static function newPermissionTercomEmployee(): TercomException
	{
		return new TercomException('permissão insuficiente', ApiStatus::PERMISSION_TERCOM_EMPLOYEE);
	}

	/**
	 * @return TercomException
	 */
	public static function newPermissionCustomerEmployee(): TercomException
	{
		return new TercomException('permissão insuficiente', ApiStatus::PERMISSION_CUSTOMER_EMPLOYEE);
	}

	/**
	 * @return TercomException
	 */
	public static function newLoginUnexpected(): TercomException
	{
		return new TercomException('acesso inesperado', ApiStatus::LOGIN_UNEXPECTED);
	}

	/**
	 * @return TercomException
	 */
	public static function newPermissionLowLevel(): TercomException
	{
		return new TercomException('nível de permissão insuficiente', ApiStatus::PERMISSION_LOW_LEVEL);
	}

	/**
	 * @return TercomException
	 */
	public static function newPermissionRestrict(): TercomException
	{
		return new TercomException('permissão restrita', ApiStatus::PERMISSION_RESTRICTED);
	}

	/**
	 * @return TercomException
	 */
	public static function newCustomerInvliad(): TercomException
	{
		return new TercomException('ação não autorizada', ApiStatus::PERMISSION_CUSTOMER_INVALID);
	}

	/**
	 * @return TercomException
	 */
	public static function newResponsability(): TercomException
	{
		return new TercomException('você não é reponsável por estes dados', ApiStatus::PERMISSION_RESPONSABILITY);
	}
}

