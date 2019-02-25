<?php

namespace tercom\exceptions;

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
	public static function newIdentified(): ServiceException
	{
		return new ServiceException('serviço já identificado', ApiStatus::SERVICE_IDENTIFIED);
	}

	/**
	 * @return ServiceException
	 */
	public static function newNotIdentified(): ServiceException
	{
		return new ServiceException('serviço não identificado', ApiStatus::SERVICE_NOT_IDENTIFIED);
	}

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

	/**
	 * @return ServiceException
	 */
	public static function newCustomerIdExist(): ServiceException
	{
		return new ServiceException('não foi possível definir o código de serviço exclusivo', ApiStatus::SERVICE_CUSTOMER_ID_EXIST);
	}

	/**
	 * @return ServiceException
	 */
	public static function newCustomerId(): ServiceException
	{
		return new ServiceException('não foi possível definir o código de serviço exclusivo', ApiStatus::SERVICE_CUSTOMER_ID);
	}

	/**
	 * @return ServiceException
	 */
	public static function newEmptyName(): ServiceException
	{
		return new ServiceException('nome não informado', ApiStatus::SERVICE_EMPTY_NAME);
	}

	/**
	 * @return ServiceException
	 */
	public static function newEmptyDescription(): ServiceException
	{
		return new ServiceException('descrição não informada', ApiStatus::SERVICE_EMPTY_DESCRIPTION);
	}

	/**
	 * @return ServiceException
	 */
	public static function newNameExist(): ServiceException
	{
		return new ServiceException('nome já utilizado', ApiStatus::SERVICE_NAME_EXIST);
	}
}

