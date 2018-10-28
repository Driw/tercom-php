<?php

namespace tercom\api\exceptions;

use tercom\api\ApiStatusException;
use tercom\api\ApiStatus;

/**
 * @see ApiStatusException
 * @author Andrew
 */

class ServiceException extends ApiStatusException
{
	/**
	 * @return ServiceException
	 */
	public static function newNotAdd(): ServiceException
	{
		return new ServiceException('não foi possível adicionar o serviço', ApiStatus::SERVICE_NOT_ADD);
	}

	/**
	 * @return ServiceException
	 */
	public static function newNotSet(): ServiceException
	{
		return new ServiceException('não foi possível atualizar o serviço', ApiStatus::SERVICE_NOT_SET);
	}

	/**
	 * @return ServiceException
	 */
	public static function newNotFound(): ServiceException
	{
		return new ServiceException('serviço não encontrado', ApiStatus::SERVICE_NOT_FOUND);
	}

	/**
	 * @return ServiceException
	 */
	public static function newFilterNotFound(): ServiceException
	{
		return new ServiceException('filtro para serviço não encontrado', ApiStatus::SERVICE_FILTER);
	}

	/**
	 * @return ServiceException
	 */
	public static function newFieldNotFound(): ServiceException
	{
		return new ServiceException('campo não encontrado', ApiStatus::SERVICE_FIELD);
	}
}

