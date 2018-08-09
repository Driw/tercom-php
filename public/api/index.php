<?php

use tercom\api\ApiConnection;
use tercom\api\ApiException;
use tercom\api\ApiResponse;
use tercom\core\System;
use dProject\Primitive\PostService;

require_once '../../include.php';

try {

	$POST = PostService::getInstance();

	System::init();
	{
		$apiConnection = ApiConnection::getInstance();
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