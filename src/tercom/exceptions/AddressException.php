<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

/**
 * @author Andrew
 */
class AddressException extends ApiStatusException
{
	/**
	 * @return AddressException
	 */
	public static function newIdentified(): AddressException
	{
		return new AddressException('endereço já identificado', ApiStatus::ADDRESS_IDENTIFIED);
	}

	/**
	 * @return AddressException
	 */
	public static function newNotIdentified(): AddressException
	{
		return new AddressException('endereço não identificado', ApiStatus::ADDRESS_NOT_IDENTIFIED);
	}

	/**
	 * @return AddressException
	 */
	public static function newInserted(): AddressException
	{
		return new AddressException('não foi possível adicionar o endereço', ApiStatus::ADDRESS_INSERTED);
	}

	/**
	 * @return AddressException
	 */
	public static function newUpdated(): AddressException
	{
		return new AddressException('não foi possível atualizar o endereço', ApiStatus::ADDRESS_UPDATED);
	}

	/**
	 * @return AddressException
	 */
	public static function newDeleted(): AddressException
	{
		return new AddressException('não foi possível excluir o endereço', ApiStatus::ADDRESS_DELETED);
	}

	/**
	 * @return AddressException
	 */
	public static function newSelected(): AddressException
	{
		return new AddressException('não foi possível obter o endereço', ApiStatus::ADDRESS_SELECTED);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços quando <b>cidade não informada</b>.
	 * @return AddressException aquisição da exceção instnaciada.
	 */
	public static function newStateEmpty(): AddressException
	{
		return new AddressException('estado não informada', ApiStatus::ADDRESS_STATE_EMPTY);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços quando <b>cidade não informada</b>.
	 * @return AddressException aquisição da exceção instnaciada.
	 */
	public static function newCityEmpty(): AddressException
	{
		return new AddressException('cidade não informada', ApiStatus::ADDRESS_CITY_EMPTY);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços quando <b>CEP não informado</b>.
	 * @return AddressException aquisição da exceção instnaciada.
	 */
	public static function newCepEmpty(): AddressException
	{
		return new AddressException('CEP não informado', ApiStatus::ADDRESS_CEP);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços quando <b>bairro não informado</b>.
	 * @return AddressException aquisição da exceção instnaciada.
	 */
	public static function newNeighborhoodEmpty(): AddressException
	{
		return new AddressException('bairro não informado', ApiStatus::ADDRESS_NEIGHTBORHOOD_EMPTY);
	}

	/**
	 *
	 * Instancia uma nova exceção para validação de endereços quando <b>rua não informada</b>.
	 * @return AddressException aquisição da exceção instnaciada.
	 */
	public static function newStreetEmpty(): AddressException
	{
		return new AddressException('rua não informado', ApiStatus::ADDRESS_STREET_EMPTY);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços quando <b>número não informado</b>.
	 * @return AddressException aquisição da exceção instnaciada.
	 */
	public static function newNumberEmpty(): AddressException
	{
		return new AddressException('número não informado', ApiStatus::ADDRESS_NUMBER_EMPTY);
	}
}

