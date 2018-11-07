<?php

namespace tercom\dao\exceptions;

/**
 * @see DAOException
 * @author Andrew
 */
class CustomerDAOException extends DAOException
{
	/**
	 *
	 * @return CustomerDAOException
	 */
	public static function newNoId(): CustomerDAOException
	{
		return new CustomerDAOException('cliente não identificado');
	}

	/**
	 *
	 * @return CustomerDAOException
	 */
	public static function newHasId(): CustomerDAOException
	{
		return new CustomerDAOException('cliente já identificado');
	}

	/**
	 *
	 * @return CustomerDAOException
	 */
	public static function newStateRegistryEmpty(): CustomerDAOException
	{
		return new CustomerDAOException('inscrição estadual não informada');
	}

	/**
	 *
	 * @return CustomerDAOException
	 */
	public static function newCnpjEmpty(): CustomerDAOException
	{
		return new CustomerDAOException('CNPJ não informado');
	}

	/**
	 *
	 * @return CustomerDAOException
	 */
	public static function newCompanyNameEmpty(): CustomerDAOException
	{
		return new CustomerDAOException('razão social não informado');
	}

	/**
	 *
	 * @return CustomerDAOException
	 */
	public static function newFantasyNameEmpty(): CustomerDAOException
	{
		return new CustomerDAOException('nome fantasia não informado');
	}

	/**
	 *
	 * @return CustomerDAOException
	 */
	public static function newEmailEmpty(): CustomerDAOException
	{
		return new CustomerDAOException('endereço de e-mail não informado');
	}
}

