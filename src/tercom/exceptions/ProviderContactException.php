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
	public static function newNotInserted(): ProviderContactException
	{
		return new ProviderContactException('não foi possível adicionar o contato de fornecedor', ApiStatus::PROVIDER_CONTACT_NOT_INSERTED);
	}

	/**
	 * @return ProviderContactException
	 */
	public static function newNotUpdated(): ProviderContactException
	{
		return new ProviderContactException('não foi possível atualizar o contato de fornecedor', ApiStatus::PROVIDER_CONTACT_NOT_UPDATED);
	}

	/**
	 * @return ProviderContactException
	 */
	public static function newNotDeleted(): ProviderContactException
	{
		return new ProviderContactException('não foi possível excluir o contato de fornecedor', ApiStatus::PROVIDER_CONTACT_NOT_DELETED);
	}

	/**
	 * @return ProviderContactException
	 */
	public static function newNotSelected(): ProviderContactException
	{
		return new ProviderContactException('contato de fornecedor não encontrado', ApiStatus::PROVIDER_CONTACT_NOT_SELECTED);
	}

	/**
	 * @return ProviderContactException
	 */
	public static function newPhoneNotUpdated(): ProviderContactException
	{
		return new ProviderContactException('um ou mais dos telefones não foram atualizados', ApiStatus::PROVIDER_CONTACT_PHONE_NOT_UPDATED);
	}

	/**
	 * @return ProviderContactException
	 */
	public static function newCommercialNotDeleted(): ProviderContactException
	{
		return new ProviderContactException('não foi possível excluir o telefone comercial', ApiStatus::PROVIDER_CONTACT_COMMERCIAL_NOT_DELETED);
	}

	/**
	 * @return ProviderContactException
	 */
	public static function newOtherphoneNotDeleted(): ProviderContactException
	{
		return new ProviderContactException('não foi possível excluir o telefone secundário', ApiStatus::PROVIDER_CONTACT_OTHERPHONE_NOT_DELETED);
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
}

