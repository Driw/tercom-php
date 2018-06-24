<?php

use dProject\api\ApiConnection;
use dProject\api\ApiException;
use dProject\Core\System;

require_once '../constants.php';
require_once '../globalFunctions.php';
require_once '../vendor/autoload.php';

register_shutdown_function('APIShutdown');
set_error_handler('APIErrorHandler');
error_reporting(0);

try {

	System::init();

	$apiConnection = new ApiConnection();
	$apiConnection->start();
	$apiConnection->identify();

	System::shutdown();

} catch (ApiException $e) {
	$apiConnection->jsonException($e, API_ERROR_API_EXCEPTION);
} catch (Exception $e) {
	$apiConnection->jsonException($e, API_ERROR_EXCEPTION);
}

?>