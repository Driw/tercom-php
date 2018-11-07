<?php

namespace tercom\dao\exceptions;

/**
 * @author Andrew
 */
class AddressDAOException extends DAOException
{
	/**
	 *
	 * @return AddressDAOException
	 */
	public static function newNoId(): AddressDAOException
	{
		return new AddressDAOException('endereço não identificado');
	}

	/**
	 *
	 * @return AddressDAOException
	 */
	public static function newHasId(): AddressDAOException
	{
		return new AddressDAOException('endereço já identificado');
	}

	/**
	 *
	 * @return AddressDAOException
	 */
	public static function newCityEmpty(): AddressDAOException
	{
		return new AddressDAOException('cidade não informada');
	}

	/**
	 *
	 * @return AddressDAOException
	 */
	public static function newCepEmpty(): AddressDAOException
	{
		return new AddressDAOException('CEP não informado');
	}

	/**
	 *
	 * @return AddressDAOException
	 */
	public static function newNeighborhoodEmpty(): AddressDAOException
	{
		return new AddressDAOException('bairro não informado');
	}

	/**
	 *
	 * @return AddressDAOException
	 */
	public static function newStreetEmpty(): AddressDAOException
	{
		return new AddressDAOException('rua não informado');
	}

	/**
	 *
	 * @return AddressDAOException
	 */
	public static function newNumberEmpty(): AddressDAOException
	{
		return new AddressDAOException('número não informado');
	}
}

