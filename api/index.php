<?php

use tercom\api\ApiConnection;
use tercom\api\ApiException;
use tercom\api\ApiResponse;
use tercom\Core\System;
use dProject\Primitive\PostService;

require_once '../constants.php';
require_once '../globalFunctions.php';
require_once '../vendor/autoload.php';

try {

	$POST = PostService::getInstance();

	System::init();
	{
		$apiConnection = new ApiConnection();
		$apiConnection->start();
		$apiConnection->validateKey($POST->getString('key', false));
		$apiConnection->identify();
	}
	System::shutdown();

} catch (ApiException $e) {
	$apiConnection->jsonException($e, ApiResponse::API_ERROR_API_EXCEPTION);
} catch (Exception $e) {
	$apiConnection->jsonException($e, ApiResponse::API_ERROR_EXCEPTION);
}

?>