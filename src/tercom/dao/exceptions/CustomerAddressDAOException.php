<?php

namespace tercom\dao\exceptions;

use tercom\api\ApiStatus;

/**
 * Exceção da DAO de Endereços de Clientes
 *
 * Exceções geradas somente durante a validação dos dados de endereços de clientes que estão para persistir no banco de dados.
 *
 * @see DAOException
 *
 * @author Andrew
 */
class CustomerAddressDAOException extends DAOException
{
	/**
	 * Instancia uma nova exceção para validação de endereços de clientes quando <b>cliente não identificado</b>.
	 * @return AddressDAOException aquisição da exceção instnaciada.
	 */
	public static function newCustomerNotIdentified(): AddressDAOException
	{
		return new AddressDAOException('cliente não identificado', ApiStatus::CUS_ADD_CUSTOMER_NOT_INDEFIED);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços de clientes quando <b>endereço não identificado</b>.
	 * @return AddressDAOException aquisição da exceção instnaciada.
	 */
	public static function newAddressNotIdentified(): AddressDAOException
	{
		return new AddressDAOException('endereço não identificado', ApiStatus::CUS_ADD_ADDRESS_NOT_INDEFIED);
	}
}

