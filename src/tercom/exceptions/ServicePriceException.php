<?php

namespace tercom\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

class ServicePriceException extends ApiStatusException
{
	public static function newIdentified(): ServicePriceException
	{
		return new ServicePriceException('serviço já identificado', ApiStatus::SERVICE_PRICE_IDENTIFIED);
	}

	public static function newNotIdentified(): ServicePriceException
	{
		return new ServicePriceException('fornecedor não encontrado', ApiStatus::SERVICE_PRICE_NOT_IDENTIFIED);
	}

	public static function newServiceNotFound(): ServicePriceException
	{
		return new ServicePriceException('serviço não encontrado', ApiStatus::SERVICE_PRICE_SERVICE);
	}

	public static function newProviderNotFound(): ServicePriceException
	{
		return new ServicePriceException('fornecedor não encontrado', ApiStatus::SERVICE_PRICE_PROVIDER);
	}

	public static function newNotAdd(): ServicePriceException
	{
		return new ServicePriceException('não foi possível adicionar o preço de serviço', ApiStatus::SERVICE_PRICE_NOT_ADD);
	}

	public static function newNotSet(): ServicePriceException
	{
		return new ServicePriceException('não foi possível atualizar o preço de serviço', ApiStatus::SERVICE_PRICE_NOT_SET);
	}

	public static function newNotFound(): ServicePriceException
	{
		return new ServicePriceException('preço de serviço não encontrado', ApiStatus::SERVICE_PRICE_NOT_FOUND);
	}

	public static function newEmptyName(): ServicePriceException
	{
		return new ServicePriceException('nome não informado', ApiStatus::SERVICE_PRICE_EMPTY_NAME);
	}

	public static function newEmptyPrice(): ServicePriceException
	{
		return new ServicePriceException('preço não informado', ApiStatus::SERVICE_PRICE_EMPTY_PRICE);
	}

	public static function newEmptyProvider(): ServicePriceException
	{
		return new ServicePriceException('preço não informado', ApiStatus::SERVICE_PRICE_EMPTY_PROVIDER);
	}

	public static function newServiceInvalid(): ServicePriceException
	{
		return new ServicePriceException('serviço não encontrado', ApiStatus::SERVICE_PRICE_SERVICE_INVALID);
	}

	public static function newProviderInvalid(): ServicePriceException
	{
		return new ServicePriceException('fornecedor não encontrado', ApiStatus::SERVICE_PRICE_PROVIDER_INVALID);
	}
}

