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

	public const PRODUCT_UNIT_IDENTIFIED = 251;
	public const PRODUCT_UNIT_NOT_IDENTIFIED = 252;
	public const PRODUCT_UNIT_NAME_EMPTY = 253;
	public const PRODUCT_UNIT_SHORT_NAME_EMPTY = 254;
	public const PRODUCT_UNIT_NAME_UNAVAIABLE = 255;
	public const PRODUCT_UNIT_SHORT_NAME_UNAVAIABLE = 256;
	public const PRODUCT_UNIT_NOT_FOUND = 257;
	public const PRODUCT_UNIT_HAS_USES = 258;
	public const PRODUCT_UNIT_INSERTED = 259;
	public const PRODUCT_UNIT_UPDATED = 260;
	public const PRODUCT_UNIT_DELETED = 261;
	public const PRODUCT_UNIT_SELECTED = 262;

	public const PRODUCT_CATEGORY_IDENTIFIED = 301;
	public const PRODUCT_CATEGORY_NOT_IDENTIFIED = 302;
	public const PRODUCT_CATEGORY_NAME_EMPTY = 303;
	public const PRODUCT_CATEGORY_TYPE_EMPTY = 304;
	public const PRODUCT_CATEGORY_NAME_UNAVAIABLE = 305;
	public const PRODUCT_CATEGORY_TYPE_INVALID = 306;
	public const PRODUCT_CATEGORY_NOT_FOUND = 307;
	public const PRODUCT_CATEGORY_HAS_USES = 308;
	public const PRODUCT_CATEGORY_INSERTED = 309;
	public const PRODUCT_CATEGORY_UPDATED = 310;
	public const PRODUCT_CATEGORY_DELETED = 311;
	public const PRODUCT_CATEGORY_DELETED_RELATIONSHIP = 312;
	public const PRODUCT_CATEGORY_SELECTED = 313;
	public const PRODUCT_CATEGORY_RELATIONSHIP = 314;
	public const PRODUCT_CATEGORY_PARENT = 315;
	public const PRODUCT_CATEGORY_PARENT_INVALID = 316;
	public const PRODUCT_CATEGORY_ON_RELATIONSHIP = 317;
	public const PRODUCT_CATEGORY_ON_PRODUCT = 318;
	public const PRODUCT_CATEGORY_INVALID_TYPE = 319;

	public const PRODUCT_IDENTIFIED = 351;
	public const PRODUCT_NOT_IDENTIFIED = 352;
	public const PRODUCT_NAME_EMPTY = 353;
	public const PRODUCT_DESCRIPTION_EMPTY = 354;
	public const PRODUCT_UNIT_NONE = 355;
	public const PRODUCT_NAME_UNAVAIABLE = 356;
	public const PRODUCT_UNIT_INVALID = 357;
	public const PRODUCT_CATEGORY_INVALID = 358;
	public const PRODUCT_NOT_INSERTED = 359;
	public const PRODUCT_NOT_UPDATED = 360;
	public const PRODUCT_NOT_SELECTED = 361;

	public const PRODUCT_TYPE_IDENTIFIED = 401;
	public const PRODUCT_TYPE_NOT_IDENTIFIED = 402;
	public const PRODUCT_TYPE_NAME_EMPTY = 403;
	public const PRODUCT_TYPE_NAME_UNAVAIABLE = 404;
	public const PRODUCT_TYPE_HAS_USES = 405;
	public const PRODUCT_TYPE_INSERTED = 406;
	public const PRODUCT_TYPE_UPDATED = 407;
	public const PRODUCT_TYPE_DELETED = 408;
	public const PRODUCT_TYPE_SELECTED = 409;

	public const PRODUCT_PACKAGE_IDENTIFIED = 451;
	public const PRODUCT_PACKAGE_NOT_IDENTIFIED = 452;
	public const PRODUCT_PACKAGE_NAME_EMPTY = 453;
	public const PRODUCT_PACKAGE_NAME_UNAVAIABLE = 454;
	public const PRODUCT_PACKAGE_HAS_USES = 455;
	public const PRODUCT_PACKAGE_INSERTED = 456;
	public const PRODUCT_PACKAGE_UPDATED = 457;
	public const PRODUCT_PACKAGE_DELETED = 458;
	public const PRODUCT_PACKAGE_SELECTED = 459;

	public const PRODUCT_PRICE_IDENTIFIED = 501;
	public const PRODUCT_PRICE_NOT_IDENTIFIED = 502;
	public const PRODUCT_PRICE_INSERTED = 503;
	public const PRODUCT_PRICE_UPDATED = 504;
	public const PRODUCT_PRICE_DELETED = 505;
	public const PRODUCT_PRICE_SELECTED = 506;
	public const PRODUCT_PRICE_HAS_USES = 507;
	public const PRODUCT_PRICE_NAME_EMPTY = 508;
	public const PRODUCT_PRICE_NAME_UNAVAIABLE = 509;
	public const PRODUCT_PRICE_AMOUNT_EMPTY = 510;
	public const PRODUCT_PRICE_PRICE_EMPTY = 511;
	public const PRODUCT_PRICE_PRODUCT_NONE = 512;
	public const PRODUCT_PRICE_PROVIDER_NONE = 513;
	public const PRODUCT_PRICE_PRODUCT_PACKAGE_NONE = 514;
	public const PRODUCT_PRICE_PRODUCT_INVALID = 515;
	public const PRODUCT_PRICE_MANUFACTURER_INVALID = 516;
	public const PRODUCT_PRICE_PROVIDER_INVALID = 517;
	public const PRODUCT_PRICE_PRODUCT_PACKAGE_INVALID = 518;
	public const PRODUCT_PRICE_PRODUCT_TYPE_INVALID = 519;

	public const SERVICE_NOT_ADD = 501;
	public const SERVICE_NOT_SET = 502;
	public const SERVICE_NOT_FOUND = 503;
	public const SERVICE_FILTER = 504;
	public const SERVICE_FIELD = 505;

	public const SERVICE_PRICE_SERVICE = 551;
	public const SERVICE_PRICE_PROVIDER = 552;
	public const SERVICE_PRICE_NOT_ADD = 553;
	public const SERVICE_PRICE_NOT_SET = 554;
	public const SERVICE_PRICE_NOT_FOUND = 555;
}

