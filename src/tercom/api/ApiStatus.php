<?php

namespace tercom\api;

class ApiStatus
{
	public const FAILURE = 0;
	public const SUCCESSFUL = 1;
	public const PARAMETER = 2;
	public const PARSE_PARAMETER = 3;
	public const FILTER = 4;

	public const PROVIDER_NOTFOUND = 101;

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

