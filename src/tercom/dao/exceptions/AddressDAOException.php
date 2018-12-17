<?php

namespace tercom\dao\exceptions;

use tercom\api\ApiStatus;

/**
 * Exceção da DAO de Endereços
 *
 * Exceções geradas somente durante a validação dos dados de endereços que estão para persistir no banco de dados.
 *
 * @see DAOException
 *
 * @author Andrew
 */
class AddressDAOException extends DAOException
{
	/**
	 * Instancia uma nova exceção para validação de endereços quando <b>endereço não identificado</b>.
	 * @return AddressDAOException aquisição da exceção instnaciada.
	 */
	public static function newNotIdentified(): AddressDAOException
	{
		return new AddressDAOException('endereço não identificado', ApiStatus::ADDRESS_NOT_IDENTIFIED);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços quando <b>endereço já identificado</b>.
	 * @return AddressDAOException aquisição da exceção instnaciada.
	 */
	public static function newIdentified(): AddressDAOException
	{
		return new AddressDAOException('endereço já identificado', ApiStatus::ADDRESS_IDENTIFIED);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços quando <b>cidade não informada</b>.
	 * @return AddressDAOException aquisição da exceção instnaciada.
	 */
	public static function newStateEmpty(): AddressDAOException
	{
		return new AddressDAOException('estado não informada', ApiStatus::ADDRESS_STATE_EMPTY);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços quando <b>cidade não informada</b>.
	 * @return AddressDAOException aquisição da exceção instnaciada.
	 */
	public static function newCityEmpty(): AddressDAOException
	{
		return new AddressDAOException('cidade não informada', ApiStatus::ADDRESS_CITY_EMPTY);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços quando <b>CEP não informado</b>.
	 * @return AddressDAOException aquisição da exceção instnaciada.
	 */
	public static function newCepEmpty(): AddressDAOException
	{
		return new AddressDAOException('CEP não informado', ApiStatus::ADDRESS_CEP);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços quando <b>bairro não informado</b>.
	 * @return AddressDAOException aquisição da exceção instnaciada.
	 */
	public static function newNeighborhoodEmpty(): AddressDAOException
	{
		return new AddressDAOException('bairro não informado', ApiStatus::ADDRESS_NEIGHTBORHOOD_EMPTY);
	}

	/**
	 *
	 * Instancia uma nova exceção para validação de endereços quando <b>rua não informada</b>.
	 * @return AddressDAOException aquisição da exceção instnaciada.
	 */
	public static function newStreetEmpty(): AddressDAOException
	{
		return new AddressDAOException('rua não informado', ApiStatus::ADDRESS_STREET_EMPTY);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços quando <b>número não informado</b>.
	 * @return AddressDAOException aquisição da exceção instnaciada.
	 */
	public static function newNumberEmpty(): AddressDAOException
	{
		return new AddressDAOException('número não informado', ApiStatus::ADDRESS_NUMBER_EMPTY);
	}
}

