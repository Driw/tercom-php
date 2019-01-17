<?php

namespace tercom\dao\exceptions;

use tercom\api\ApiStatus;
use tercom\exceptions\AddressException;

/**
 * Exceção da DAO de Endereços de Clientes
 *
 * Exceções geradas somente durante a validação dos dados de endereços de clientes que estão para persistir no banco de dados.
 *
 * @see AddressException
 *
 * @author Andrew
 */
class CustomerAddressException extends AddressException
{
	/**
	 * Instancia uma nova exceção para validação de endereços de clientes quando <b>cliente não identificado</b>.
	 * @return CustomerAddressException aquisição da exceção instnaciada.
	 */
	public static function newCustomerNotIdentified(): CustomerAddressException
	{
		return new CustomerAddressException('cliente não identificado', ApiStatus::CUS_ADD_CUSTOMER_NOT_INDEFIED);
	}

	/**
	 * Instancia uma nova exceção para validação de endereços de clientes quando <b>endereço não identificado</b>.
	 * @return CustomerAddressException aquisição da exceção instnaciada.
	 */
	public static function newAddressNotIdentified(): CustomerAddressException
	{
		return new CustomerAddressException('endereço não identificado', ApiStatus::CUS_ADD_ADDRESS_NOT_INDEFIED);
	}
}

