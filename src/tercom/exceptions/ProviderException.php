<?php

namespace tercom\exceptions;

use dProject\restful\exception\ApiExceptionResult;
use tercom\api\ApiStatus;

/**
 * @see ApiStatusException
 * @author Andrew
 */

class ProviderException extends ApiExceptionResult
{
	/**
	 * @return ProviderException
	 */
	public static function newIdentified(): ProviderException
	{
		return new ProviderException('fornecedor já identificado', ApiStatus::PROVIDER_IDENTIFIED);
	}

	/**
	 * @return ProviderException
	 */
	public static function newNotIdentified(): ProviderException
	{
		return new ProviderException('fornecedor não identificado', ApiStatus::PROVIDER_NOT_IDENTIFIED);
	}

	/**
	 * @return ProviderException
	 */
	public static function newCnpjEmpty(): ProviderException
	{
		return new ProviderException('CNPJ não informado', ApiStatus::PROVIDER_CNPJ_EMPTY);
	}

	/**
	 * @return ProviderException
	 */
	public static function newCompanyNameEmpty(): ProviderException
	{
		return new ProviderException('razão social não informada', ApiStatus::PROVIDER_COMPANY_NAME_EMPTY);
	}

	/**
	 * @return ProviderException
	 */
	public static function newFantasyNameEmpty(): ProviderException
	{
		return new ProviderException('nome fantasia não informado', ApiStatus::PROVIDER_FANTASY_NAME_EMPTY);
	}

	/**
	 * @return ProviderException
	 */
	public static function newCnpjUnavaiable(): ProviderException
	{
		return new ProviderException('CNPJ indisponível', ApiStatus::PROVIDER_CNPJ_UNAVAIABLE);
	}
}

