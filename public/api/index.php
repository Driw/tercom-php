<?php

require_once '../../include.php';

use dProject\Primitive\GlobalFunctions;
use dProject\restful\ApiSettings;
use dProject\restful\ApiConnection;
use tercom\boundary\dashboard\DashboardTemplate;
use tercom\api\ApiListener;

GlobalFunctions::init();
DashboardTemplate::setDirectory(sprintf('%s/%s', __DIR__, 'boundaries'));

$listener = new ApiListener();

$settings = new ApiSettings();
$settings->setParametersOffset(1);
$settings->setEnableDebug(true);
$settings->setEnableTimeUp(true);
$settings->setEnableResultClass(true);
$settings->setEnableContentLength(true);
$settings->setApiNameSpace(namespaceOf($listener));
$settings->setResponseType(ApiSettings::RESPONSE_JSON);

$apiConnection = ApiConnection::getInstance();
$apiConnection->setSettings($settings);
$apiConnection->setListener($listener);
$apiConnection->start();

?>