<?php

namespace tercom\api;

class ApiStatus
{
	public const FAILURE = 0;
	public const SUCCESSFUL = 1;
	public const PARAMETER = 2;
	public const PARSE_PARAMETER = 3;
	public const FILTER = 4;
	public const RELATIONSHIP = 5;

	public const PROVIDER_IDENTIFIED = 101;
	public const PROVIDER_NOT_IDENTIFIED = 102;
	public const PROVIDER_CNPJ_EMPTY = 103;
	public const PROVIDER_COMPANY_NAME_EMPTY = 104;
	public const PROVIDER_FANTASY_NAME_EMPTY = 105;
	public const PROVIDER_CNPJ_UNAVAIABLE = 106;
	public const PROVIDER_NOT_FOUND = 107;

	public const PROVIDER_CONTACT_NOT_IDENTIFIED = 151;
	public const PROVIDER_CONTACT_IDENTIFIED = 152;
	public const PROVIDER_CONTACT_NAME_EMPTY = 153;
	public const PROVIDER_CONTACT_EMAIL_EMPTY = 154;
	public const PROVIDER_CONTACT_NOT_FOUND = 155;

	public const MANUFACTURER_IDENTIFIED = 201;
	public const MANUFACTURER_NOT_IDENTIFIED = 202;
	public const MANUFACTURER_FANTASY_NAME_EMPTY = 203;
	public const MANUFACTURER_FANTASY_NAME_UNAVAIABLE = 204;
	public const MANUFACTURER_NOT_FOUND = 205;
	public const MANUFACTURER_HAS_USES = 206;

	public const SERVICE_NOT_ADD = 401;
	public const SERVICE_NOT_SET = 402;
	public const SERVICE_NOT_FOUND = 403;
	public const SERVICE_FILTER = 404;
	public const SERVICE_FIELD = 405;

	public const SERVICE_PRICE_SERVICE = 451;
	public const SERVICE_PRICE_PROVIDER = 452;
	public const SERVICE_PRICE_NOT_ADD = 453;
	public const SERVICE_PRICE_NOT_SET = 454;
	public const SERVICE_PRICE_NOT_FOUND = 455;
}

