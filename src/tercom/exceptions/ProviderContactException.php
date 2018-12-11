<?php

namespace tercom\exceptions;

use dProject\restful\exception\ApiException;
use tercom\api\ApiStatus;

/**
 * @see ApiException
 * @author Andrew
 */
class ProviderContactException extends ApiException
{
	/**
	 * @return ProviderContactException
	 */
	public static function newNotIdentified(): ProviderContactException
	{
		return new ProviderContactException('contato de fornecedor não identificado', ApiStatus::PROVIDER_CONTACT_NOT_IDENTIFIED);
	}

	/**
	 * @return ProviderContactException
	 */
	public static function newIdentified(): ProviderContactException
	{
		return new ProviderContactException('contato de fornecedor já identificado', ApiStatus::PROVIDER_CONTACT_IDENTIFIED);
	}

	/**
	 * @return ProviderContactException
	 */
	public static function newNameEmpty(): ProviderContactException
	{
		return new ProviderContactException('nome não informado', ApiStatus::PROVIDER_CONTACT_NAME_EMPTY);
	}

	/**
	 * @return ProviderContactException
	 */
	public static function newEmailEmpty(): ProviderContactException
	{
		return new ProviderContactException('endereço de e-mail não informado', ApiStatus::PROVIDER_CONTACT_EMAIL_EMPTY);
	}

	/**
	 * @return ProviderContactException
	 */
	public static function newNotFound(): ProviderContactException
	{
		return new ProviderContactException('contato de fornecedor não encontrado', ApiStatus::PROVIDER_CONTACT_NOT_FOUND);
	}
}

