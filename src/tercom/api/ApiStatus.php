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
	public const NOT_LOGGED = 6;
	public const API_CONNECTION = 7;
	public const LOGIN_UNEXPECTED = 8;
	public const DASHBOARD_CONNECTION = 9;
	public const LOGIN_TERCOM_NOT_FOUND = 10;
	public const LOGIN_CUSTOMER_NOT_FOUND = 11;

	public const PERMISSION_NOT_ENOUGHT = 51;
	public const PERMISSION_TERCOM_EMPLOYEE = 52;
	public const PERMISSION_CUSTOMER_EMPLOYEE = 53;
	public const PERMISSION_LOW_LEVEL = 54;
	public const PERMISSION_RESTRICTED = 55;
	public const PERMISSION_CUSTOMER_INVALID = 56;
	public const PERMISSION_RESPONSABILITY = 57;

	public const PROVIDER_IDENTIFIED = 101;
	public const PROVIDER_NOT_IDENTIFIED = 102;
	public const PROVIDER_NOT_INSERTED = 103;
	public const PROVIDER_NOT_UPDATED = 104;
	public const PROVIDER_NOT_SELECTED = 106;
	public const PROVIDER_CNPJ_EMPTY = 107;
	public const PROVIDER_COMPANY_NAME_EMPTY = 108;
	public const PROVIDER_FANTASY_NAME_EMPTY = 109;
	public const PROVIDER_CNPJ_UNAVAIABLE = 110;
	public const PROVIDER_CNPJ_INVALID = 111;

	public const PROVIDER_CONTACT_NOT_IDENTIFIED = 151;
	public const PROVIDER_CONTACT_IDENTIFIED = 152;
	public const PROVIDER_CONTACT_NOT_INSERTED = 153;
	public const PROVIDER_CONTACT_NOT_UPDATED = 154;
	public const PROVIDER_CONTACT_NOT_DELETED = 155;
	public const PROVIDER_CONTACT_NOT_SELECTED = 156;
	public const PROVIDER_CONTACT_PHONE_NOT_UPDATED = 157;
	public const PROVIDER_CONTACT_COMMERCIAL_NOT_DELETED = 158;
	public const PROVIDER_CONTACT_OTHERPHONE_NOT_DELETED = 159;
	public const PROVIDER_CONTACT_NAME_EMPTY = 160;
	public const PROVIDER_CONTACT_EMAIL_EMPTY = 161;

	public const MANUFACTURER_IDENTIFIED = 201;
	public const MANUFACTURER_NOT_IDENTIFIED = 202;
	public const MANUFACTURER_NOT_INSERTED = 203;
	public const MANUFACTURER_NOT_UPDATED = 204;
	public const MANUFACTURER_NOT_DELETED = 205;
	public const MANUFACTURER_NOT_SELECTED = 206;
	public const MANUFACTURER_FANTASY_NAME_EMPTY = 207;
	public const MANUFACTURER_FANTASY_NAME_UNAVAIABLE = 208;
	public const MANUFACTURER_NOT_FOUND = 209;
	public const MANUFACTURER_HAS_USES = 210;

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
	public const PRODUCT_CUSTOMER_ID_EXIST = 362;
	public const PRODUCT_CUSTOMER_ID = 363;

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

	public const SERVICE_IDENTIFIED = 501;
	public const SERVICE_NOT_IDENTIFIED = 502;
	public const SERVICE_NOT_ADD = 503;
	public const SERVICE_NOT_SET = 504;
	public const SERVICE_NOT_FOUND = 505;
	public const SERVICE_FILTER = 506;
	public const SERVICE_FIELD = 507;
	public const SERVICE_CUSTOMER_ID_EXIST = 508;
	public const SERVICE_CUSTOMER_ID = 509;
	public const SERVICE_EMPTY_NAME = 510;
	public const SERVICE_EMPTY_DESCRIPTION = 511;
	public const SERVICE_NAME_EXIST = 512;

	public const SERVICE_PRICE_IDENTIFIED = 551;
	public const SERVICE_PRICE_NOT_IDENTIFIED = 552;
	public const SERVICE_PRICE_SERVICE = 553;
	public const SERVICE_PRICE_PROVIDER = 554;
	public const SERVICE_PRICE_NOT_ADD = 555;
	public const SERVICE_PRICE_NOT_SET = 556;
	public const SERVICE_PRICE_NOT_FOUND = 557;
	public const SERVICE_PRICE_EMPTY_NAME = 558;
	public const SERVICE_PRICE_EMPTY_PRICE = 559;
	public const SERVICE_PRICE_EMPTY_PROVIDER = 560;
	public const SERVICE_PRICE_SERVICE_INVALID = 561;
	public const SERVICE_PRICE_PROVIDER_INVALID = 562;

	public const ADDRESS_IDENTIFIED = 601;
	public const ADDRESS_NOT_IDENTIFIED = 602;
	public const ADDRESS_INSERTED = 603;
	public const ADDRESS_UPDATED = 604;
	public const ADDRESS_DELETED = 605;
	public const ADDRESS_SELECTED = 606;
	public const ADDRESS_STATE_EMPTY = 607;
	public const ADDRESS_CITY_EMPTY = 608;
	public const ADDRESS_NEIGHTBORHOOD_EMPTY = 609;
	public const ADDRESS_STREET_EMPTY = 610;
	public const ADDRESS_NUMBER_EMPTY = 611;
	public const ADDRESS_CEO_EMPTY = 612;

	public const CUS_ADD_CUSTOMER_NOT_INDEFIED = 651;
	public const CUS_ADD_ADDRESS_NOT_INDEFIED = 652;

	public const CUSTOMER_IDENTIFIED = 701;
	public const CUSTOMER_NOT_IDENTIFIED = 702;
	public const CUSTOMER_INSERTED = 703;
	public const CUSTOMER_UPDATED = 704;
	public const CUSTOMER_DELETED = 705;
	public const CUSTOMER_SELECTED = 706;
	public const CUSTOMER_STATE_REGISTRY_EMPTY = 707;
	public const CUSTOMER_CNPJ_EMPTY = 708;
	public const CUSTOMER_COMPANY_NAME_EMPTY = 709;
	public const CUSTOMER_FANTASY_NAME_EMPTY = 710;
	public const CUSTOMER_EMAIL_EMPTY = 711;
	public const CUSTOMER_UNAVAIABLE_CNPJ = 712;
	public const CUSTOMER_UNAVAIABLE_COMPANY_NAME = 713;

	public const ORDER_REQUEST_IDENTIFIED = 851;
	public const ORDER_REQUEST_NOT_IDENTIFIED = 852;
	public const ORDER_REQUEST_INSERTED = 853;
	public const ORDER_REQUEST_UPDATED = 854;
	public const ORDER_REQUEST_SELECTED = 855;
	public const ORDER_REQUEST_CUSTOMER_EMPLOYEE_EMPTY = 856;
	public const ORDER_REQUEST_CUSTOMER_EMPLOYEE = 857;
	public const ORDER_REQUEST_TERCOM_EMPLOYEE = 858;
	public const ORDER_REQUEST_CUSTOMER_INVALID = 859;
	public const ORDER_REQUEST_CANCELED_BY_CUSTOMER = 860;
	public const ORDER_REQUEST_CANCELED_BY_TERCOM = 861;
	public const ORDER_REQUEST_CUSTOMER_EMPLOYEE_ERROR = 862;
	public const ORDER_REQUEST_TERCOM_EMPLOYEE_ERROR = 863;
	public const ORDER_REQUEST_TERCOM_EMPLOYEE_SETTED = 864;
	public const ORDER_REQUEST_NOT_MANAGING = 865;
	public const ORDER_REQUEST_NOT_QUEUED = 866;
	public const ORDER_REQUEST_NOT_QUOTING = 867;

	public const ORDER_ITEM_PRODUCT_IDENTIFIED = 901;
	public const ORDER_ITEM_PRODUCT_NOT_IDENTIFIED = 902;
	public const ORDER_ITEM_PRODUCT_INSERTED = 903;
	public const ORDER_ITEM_PRODUCT_UPDATED = 904;
	public const ORDER_ITEM_PRODUCT_DELETED = 905;
	public const ORDER_ITEM_PRODUCT_DELETED_ALL = 906;
	public const ORDER_ITEM_PRODUCT_SELECTED = 907;
	public const ORDER_ITEM_PRODUCT_PRODUCT_INVALID = 908;
	public const ORDER_ITEM_PRODUCT_PROVIDER_INVALID = 909;
	public const ORDER_ITEM_PRODUCT_MANUFACTURER_INVALID = 910;
	public const ORDER_ITEM_PRODUCT_PRODUCT_EMPTY = 911;
	public const ORDER_ITEM_PRODUCT_EXIST = 912;
	public const ORDER_ITEM_PRODUCT_BINDED = 913;

	public const ORDER_ITEM_SERVICE_IDENTIFIED = 951;
	public const ORDER_ITEM_SERVICE_NOT_IDENTIFIED = 952;
	public const ORDER_ITEM_SERVICE_INSERTED = 953;
	public const ORDER_ITEM_SERVICE_UPDATED = 954;
	public const ORDER_ITEM_SERVICE_DELETED = 955;
	public const ORDER_ITEM_SERVICE_DELETED_ALL = 956;
	public const ORDER_ITEM_SERVICE_SELECTED = 957;
	public const ORDER_ITEM_SERVICE_SERVICE_INVALID = 958;
	public const ORDER_ITEM_SERVICE_PROVIDER_INVALID = 959;
	public const ORDER_ITEM_SERVICE_MANUFACTURER_INVALID = 960;
	public const ORDER_ITEM_SERVICE_SERVICE_EMPTY = 961;
	public const ORDER_ITEM_SERVICE_EXIST = 962;
	public const ORDER_ITEM_SERVICE_BINDED = 963;

	public const ORDER_QUOTE_IDENTIFIED = 1001;
	public const ORDER_QUOTE_NOT_IDENTIFIED = 1002;
	public const ORDER_QUOTE_INSERTED = 1003;
	public const ORDER_QUOTE_UPDATED = 1004;
	public const ORDER_QUOTE_SELECTED = 1007;
	public const ORDER_QUOTE_ALREADY_QUOTED = 1008;
	public const ORDER_QUOTE_ORDER_REQUEST_NOT_FOUND = 1009;
	public const ORDER_QUOTE_ORDER_REQUEST_NOT_QUEUED = 1010;

	public const QUOTED_ORDER_PRODUCT_IDENTIFIED = 1051;
	public const QUOTED_ORDER_PRODUCT_NOT_IDENTIFIED = 1052;
	public const QUOTED_ORDER_PRODUCT_INSERTED = 1053;
	public const QUOTED_ORDER_PRODUCT_UPDATED = 1054;
	public const QUOTED_ORDER_PRODUCT_DELETED = 1055;
	public const QUOTED_ORDER_PRODUCT_DELETED_ALL = 1056;
	public const QUOTED_ORDER_PRODUCT_SELECTED = 1057;
	public const QUOTED_ORDER_PRODUCT_ALREADY_USED = 1058;
	public const QUOTED_ORDER_PRODUCT_ITEM_INVALID = 1059;
	public const QUOTED_ORDER_PRODUCT_PRICE_INVALID = 1060;
	public const QUOTED_ORDER_PRODUCT_PRICE_ERROR = 1061;
	public const QUOTED_ORDER_PRODUCT_ORDER_REQUEST = 1062;

	public const QUOTED_PRODUCT_PRICE_INSERTED = 1101;
	public const QUOTED_PRODUCT_PRICE_DELETED = 1102;
	public const QUOTED_PRODUCT_PRICE_SELECTED = 1103;
	public const QUOTED_PRODUCT_PRICE_NAME_EMPTY = 1104;
	public const QUOTED_PRODUCT_PRICE_AMOUNT_EMPTY = 1105;
	public const QUOTED_PRODUCT_PRICE_PRICE_EMPTY = 1106;
	public const QUOTED_PRODUCT_PRICE_PRODUCT_NONE = 1107;
	public const QUOTED_PRODUCT_PRICE_PROVIDER_NONE = 1108;
	public const QUOTED_PRODUCT_PRICE_PRODUCT_PACKAGE_NONE = 1109;
	public const QUOTED_PRODUCT_PRICE_PRODUCT_INVALID = 1110;
	public const QUOTED_PRODUCT_PRICE_MANUFACTURER_INVALID = 1111;
	public const QUOTED_PRODUCT_PRICE_PROVIDER_INVALID = 1112;
	public const QUOTED_PRODUCT_PRICE_PRODUCT_PACKAGE_INVALID = 1113;
	public const QUOTED_PRODUCT_PRICE_PRODUCT_TYPE_INVALID = 1114;

	public const QUOTED_ORDER_SERVICE_IDENTIFIED = 1151;
	public const QUOTED_ORDER_SERVICE_NOT_IDENTIFIED = 1152;
	public const QUOTED_ORDER_SERVICE_INSERTED = 1153;
	public const QUOTED_ORDER_SERVICE_UPDATED = 1154;
	public const QUOTED_ORDER_SERVICE_DELETED = 1155;
	public const QUOTED_ORDER_SERVICE_DELETED_ALL = 1156;
	public const QUOTED_ORDER_SERVICE_SELECTED = 1157;
	public const QUOTED_ORDER_SERVICE_ALREADY_USED = 1158;
	public const QUOTED_ORDER_SERVICE_ITEM_INVALID = 1159;
	public const QUOTED_ORDER_SERVICE_PRICE_INVALID = 1160;
	public const QUOTED_ORDER_SERVICE_PRICE_ERROR = 1161;
	public const QUOTED_ORDER_SERVICE_ORDER_REQUEST = 1162;

	public const QUOTED_SERVICE_PRICE_INSERTED = 1201;
	public const QUOTED_SERVICE_PRICE_DELETED = 1202;
	public const QUOTED_SERVICE_PRICE_SELECTED = 1203;
	public const QUOTED_SERVICE_PRICE_NAME_EMPTY = 1204;
	public const QUOTED_SERVICE_PRICE_AMOUNT_EMPTY = 1205;
	public const QUOTED_SERVICE_PRICE_PRICE_EMPTY = 1206;
	public const QUOTED_SERVICE_PRICE_SERVICE_NONE = 1207;
	public const QUOTED_SERVICE_PRICE_PROVIDER_NONE = 1208;
	public const QUOTED_SERVICE_PRICE_SERVICE_INVALID = 1209;
	public const QUOTED_SERVICE_PRICE_PROVIDER_INVALID = 1210;

	public const ORDER_ACCEPTANCE_IDENTIFIED = 1251;
	public const ORDER_ACCEPTANCE_NOT_IDENTIFIED = 1252;
	public const ORDER_ACCEPTANCE_INSERTED = 1253;
	public const ORDER_ACCEPTANCE_UPDATEED = 1254;
	public const ORDER_ACCEPTANCE_SELECTED = 1255;
	public const ORDER_ACCEPTANCE_ORDER_EMPTY = 1257;
	public const ORDER_ACCEPTANCE_ORDER_INVALID = 1258;
	public const ORDER_ACCEPTANCE_CUSTOMER_EMPTY = 1259;
	public const ORDER_ACCEPTANCE_CUSTOMER_INVALID = 1260;
	public const ORDER_ACCEPTANCE_TERCOM_EMPTY = 1261;
	public const ORDER_ACCEPTANCE_TERCOM_INVALID = 1262;
	public const ORDER_ACCEPTANCE_ADDRESS_EMPTY = 1263;
	public const ORDER_ACCEPTANCE_ADDRESS_INVALID = 1264;
	public const ORDER_ACCEPTANCE_QUOTE_EXIST = 1265;
	public const ORDER_ACCEPTANCE_MANAGE = 1266;
	public const ORDER_ACCEPTANCE_APPROVING = 1267;
	public const ORDER_ACCEPTANCE_APPROVED = 1268;
	public const ORDER_ACCEPTANCE_REQUEST = 12698;
	public const ORDER_ACCEPTANCE_PAID = 1266;

	public const ORDER_ACCEPTANCE_PRODUCT_IDENTIFIED = 1301;
	public const ORDER_ACCEPTANCE_PRODUCT_NOT_IDENTIFIED = 1302;
	public const ORDER_ACCEPTANCE_PRODUCT_INSERTED = 1303;
	public const ORDER_ACCEPTANCE_PRODUCT_UPDATED = 1304;
	public const ORDER_ACCEPTANCE_PRODUCT_DELETED = 1305;
	public const ORDER_ACCEPTANCE_PRODUCT_DELETED_ALL = 1306;
	public const ORDER_ACCEPTANCE_PRODUCT_SELECTED = 1307;
	public const ORDER_ACCEPTANCE_PRODUCT_ACCEPTANCE_EMPTY = 1308;
	public const ORDER_ACCEPTANCE_PRODUCT_ACCEPTANCE_INVALID = 1309;
	public const ORDER_ACCEPTANCE_PRODUCT_NAME_EMPTY = 1310;
	public const ORDER_ACCEPTANCE_PRODUCT_AMOUNT_EMPTY = 1311;
	public const ORDER_ACCEPTANCE_PRODUCT_PRICE_EMPTY = 1312;
	public const ORDER_ACCEPTANCE_PRODUCT_AMOUNT_REQUEST_EMPTY = 1313;
	public const ORDER_ACCEPTANCE_PRODUCT_SUBPRICE_EMPTY = 1314;
	public const ORDER_ACCEPTANCE_PRODUCT_PRODUCT_EMPTY = 1315;
	public const ORDER_ACCEPTANCE_PRODUCT_PRODUCT_INVALID = 1316;
	public const ORDER_ACCEPTANCE_PRODUCT_PROVIDER_EMPTY = 1317;
	public const ORDER_ACCEPTANCE_PRODUCT_PROVIDER_INVALID = 1318;
	public const ORDER_ACCEPTANCE_PRODUCT_PACKAGE_EMPTY = 1319;
	public const ORDER_ACCEPTANCE_PRODUCT_PACKAGE_INVALID = 1320;
	public const ORDER_ACCEPTANCE_PRODUCT_MANUFACTURER_INVALID = 1321;
	public const ORDER_ACCEPTANCE_PRODUCT_TYPE_INVALID = 1322;
	public const ORDER_ACCEPTANCE_PRODUCT_USED= 1323;

	public const ORDER_ACCEPTANCE_SERVICE_IDENTIFIED = 1301;
	public const ORDER_ACCEPTANCE_SERVICE_NOT_IDENTIFIED = 1302;
	public const ORDER_ACCEPTANCE_SERVICE_INSERTED = 1303;
	public const ORDER_ACCEPTANCE_SERVICE_UPDATED = 1304;
	public const ORDER_ACCEPTANCE_SERVICE_DELETED = 1305;
	public const ORDER_ACCEPTANCE_SERVICE_DELETED_ALL = 1306;
	public const ORDER_ACCEPTANCE_SERVICE_SELECTED = 1307;
	public const ORDER_ACCEPTANCE_SERVICE_ACCEPTANCE_EMPTY = 1308;
	public const ORDER_ACCEPTANCE_SERVICE_ACCEPTANCE_INVALID = 1309;
	public const ORDER_ACCEPTANCE_SERVICE_NAME_EMPTY = 1310;
	public const ORDER_ACCEPTANCE_SERVICE_PRICE_EMPTY = 1311;
	public const ORDER_ACCEPTANCE_SERVICE_AMOUNT_REQUEST_EMPTY = 1312;
	public const ORDER_ACCEPTANCE_SERVICE_SUBPRICE_EMPTY = 1313;
	public const ORDER_ACCEPTANCE_SERVICE_SERVICE_EMPTY = 1314;
	public const ORDER_ACCEPTANCE_SERVICE_SERVICE_INVALID = 1315;
	public const ORDER_ACCEPTANCE_SERVICE_PROVIDER_EMPTY = 1316;
	public const ORDER_ACCEPTANCE_SERVICE_PROVIDER_INVALID = 1317;
	public const ORDER_ACCEPTANCE_SERVICE_USED = 1318;
}

