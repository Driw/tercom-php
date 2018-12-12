<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @see ApiStatusException
 * @author Andrew
 */
class ManufacturerException extends ApiStatusException
{
	/**
	 * @return ManufacturerException
	 */
	public static function newIdentified(): ManufacturerException
	{
		return new ManufacturerException('fabricante já identificado', ApiStatus::MANUFACTURER_IDENTIFIED);
	}

	/**
	 * @return ManufacturerException
	 */
	public static function newNotIdentified(): ManufacturerException
	{
		return new ManufacturerException('fabricante não identificado', ApiStatus::MANUFACTURER_NOT_IDENTIFIED);
	}

	/**
	 * @return ProviderException
	 */
	public static function newNotInserted(): ProviderException
	{
		return new ProviderException('não foi possível adicionar o fabricante', ApiStatus::MANUFACTURER_NOT_INSERTED);
	}

	/**
	 * @return ProviderException
	 */
	public static function newNotUpdated(): ProviderException
	{
		return new ProviderException('não foi possível atualizar os dados do fabricante', ApiStatus::MANUFACTURER_NOT_UPDATED);
	}

	/**
	 * @return ProviderException
	 */
	public static function newNotDeleted(): ProviderException
	{
		return new ProviderException('não foi possível excluir o fabricante', ApiStatus::MANUFACTURER_NOT_DELETED);
	}

	/**
	 * @return ProviderException
	 */
	public static function newNotSelected(): ProviderException
	{
		return new ProviderException('fabricante não encontrado', ApiStatus::MANUFACTURER_NOT_SELECTED);
	}

	/**
	 * @return ManufacturerException
	 */
	public static function newFantasyNameEmpty(): ManufacturerException
	{
		return new ManufacturerException('nome fantasia não informado', ApiStatus::MANUFACTURER_FANTASY_NAME_EMPTY);
	}

	/**
	 * @return ManufacturerException
	 */
	public static function newFantasyNameUnavaiable(): ManufacturerException
	{
		return new ManufacturerException('nome fantasia indisponível', ApiStatus::MANUFACTURER_FANTASY_NAME_UNAVAIABLE);
	}

	/**
	 * @return ManufacturerException
	 */
	public static function newNotFound(): ManufacturerException
	{
		return new ManufacturerException('fabricante não encontrado', ApiStatus::MANUFACTURER_NOT_FOUND);
	}

	/**
	 * @return ManufacturerException
	 */
	public static function newHasUses(): ManufacturerException
	{
		return new ManufacturerException('fabricante sendo utilizado', ApiStatus::MANUFACTURER_HAS_USES);
	}
}

