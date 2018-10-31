<?php

namespace tercom\api\exceptions;

use tercom\api\ApiStatus;
use tercom\api\ApiStatusException;

class ServicePriceException extends ApiStatusException
{
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
}

