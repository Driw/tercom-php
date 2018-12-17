<?php

namespace tercom\dao\exceptions;

use tercom\api\ApiStatus;

/**
 * @see DAOException
 * @author Andrew
 */
class CustomerDAOException extends DAOException
{
	/**
	 * Instancia uma nova exceção para validação de endereços de clientes quando <b>cliente não identificado</b>.
	 * @return CustomerDAOException aquisição da exceção instnaciada.
	 */
	public static function newNotIdentified(): CustomerDAOException
	{
		return new CustomerDAOException('cliente não identificado', ApiStatus::CUSTOMER_NOT_IDENTIFIED);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços de clientes quando <b>cliente já identificado</b>.
	 * @return CustomerDAOException aquisição da exceção instnaciada.
	 */
	public static function newIdentified(): CustomerDAOException
	{
		return new CustomerDAOException('cliente já identificado', ApiStatus::CUSTOMER_IDENTIFIED);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços de clientes quando <b>inscrição estadual não informada</b>.
	 * @return CustomerDAOException aquisição da exceção instnaciada.
	 */
	public static function newStateRegistryEmpty(): CustomerDAOException
	{
		return new CustomerDAOException('inscrição estadual não informada', ApiStatus::CUSTOMER_STATE_REGISTRY_EMPTY);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços de clientes quando <b>CNPJ não informado</b>.
	 * @return CustomerDAOException aquisição da exceção instnaciada.
	 */
	public static function newCnpjEmpty(): CustomerDAOException
	{
		return new CustomerDAOException('CNPJ não informado', ApiStatus::CUSTOMER_CNPJ_EMPTY);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços de clientes quando <b>razão social não informado</b>.
	 * @return CustomerDAOException aquisição da exceção instnaciada.
	 */
	public static function newCompanyNameEmpty(): CustomerDAOException
	{
		return new CustomerDAOException('razão social não informado', ApiStatus::CUSTOMER_COMPANY_NAME_EMPTY);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços de clientes quando <b>nome fantasia não informado</b>.
	 * @return CustomerDAOException aquisição da exceção instnaciada.
	 */
	public static function newFantasyNameEmpty(): CustomerDAOException
	{
		return new CustomerDAOException('nome fantasia não informado', ApiStatus::CUSTOMER_FANTASY_NAME_EMPTY);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços de clientes quando <b>endereço de e-mail não informado</b>.
	 * @return CustomerDAOException aquisição da exceção instnaciada.
	 */
	public static function newEmailEmpty(): CustomerDAOException
	{
		return new CustomerDAOException('endereço de e-mail não informado', ApiStatus::CUSTOMER_EMAIL_EMPTY);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços de clientes quando <b>razão social indisponível</b>.
	 * @return CustomerDAOException aquisição da exceção instnaciada.
	 */
	public static function newUnavaiableCompanyName(): CustomerDAOException
	{
		return new CustomerDAOException('razão social indisponível', ApiStatus::CUSTOMER_UNAVAIABLE_COMPANY_NAME);
	}
}

