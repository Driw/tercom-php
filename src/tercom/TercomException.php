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
}

