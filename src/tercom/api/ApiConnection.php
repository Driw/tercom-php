<?php

namespace tercom\api;

use Exception;
use dProject\Primitive\GetService;
use dProject\Primitive\StringUtil;
use tercom\Encryption;
use tercom\core\System;

class ApiConnection
{
	private static $instance;

	private $timeup;
	private $apiname;

	private function __construct()
	{
		$this->timeup = now();
	}

	public function start()
	{
		if (GetService::getInstance()->isSetted('debug'))
			error_reporting(E_ALL);
		else
		{
			register_shutdown_function([ self::$instance, 'shutdownError' ]);
			set_error_handler([ self::$instance, 'errorHandler' ]);
			error_reporting(0);
		}

		header('Content-type: application/json');
		header('Cache-Control: no-cache');
		header('Pragma: no-cache');
	}

	public function identify()
	{
		$uri = substr($_SERVER['REQUEST_URI'], 1);

		if (($strpos = strpos($uri, '?')) !== false)
			$uri = substr($uri, 0, $strpos);

		$parameters = explode('/', $uri);

		if ($parameters[0] !== 'api')
			throw new ApiBadRequestException();

		if (count($parameters) < 3)
			throw new ApiBadRequestException();

		$this->apiname = $parameters[1];
		$apiclassname = $parameters[2];
		$classname = sprintf('%s\%s\Api%s', __NAMESPACE__, $this->apiname, ucfirst($apiclassname));

		if (!class_exists($classname))
			throw new ApiNotFoundException();

		$variables = array_slice($parameters, 3); // 0: sistema de api, 1: nome da api, 3: classe da api
		$apiAction = self::newApiAction($classname, $parameters[2], $variables);
		$apiResult = $apiAction->execute();

		$response = new ApiResponse();
		$response->setApiResult($apiResult);
		$response->setTime(now() - $this->timeup);

		echo $response->toApiJSON();
	}

	private function newApiAction(string $classname, string $addon, array $variables): ApiActionInterface
	{
		return new $classname($this, $addon, $variables);
	}

	public function jsonException(Exception $e, int $code)
	{
		if ($e instanceof ApiException)
			$this->jsonFatalError(ApiResponse::API_ERROR_API_EXCEPTION, $e->getMessage(), $e->getCode(), $e->getLine(), $e->getFile());
		else
			$this->jsonFatalError(ApiResponse::API_ERROR_EXCEPTION, $e->getMessage(), $e->getCode(), $e->getLine(), $e->getFile());
	}

	private function jsonFatalError(int $status, string $message, int $errorCode, int $target, string $source)
	{
		$encryption = new Encryption();
		$result = [
			'code' => $errorCode,
			'target' => $target,
			'source' => SYS_DEVELOP ? $source : $encryption->encrypt($source),
		];

		$response = new ApiResponse();
		$response->setStatus($status);
		$response->setMessage($message);
		$response->setResult($result);
		$response->setTime(now() - $this->timeup);

		echo $response->toApiJSON();
		System::shutdown();
		exit;
	}

	public function shutdownError()
	{
		if (($error = error_get_last()) != null)
			ApiConnection::jsonFatalError(ApiResponse::API_PHP_FATAL_ERROR, $error['message'], $error['type'], $error['line'], $error['file']);
	}

	public function errorHandler(int $code, string $message, string $file, int $line)
	{
		ApiConnection::jsonFatalError(ApiResponse::API_PHP_FATAL_ERROR, $message,  $code, $line, $file);
	}

	public function validateKey($key)
	{

	}

	public function shutdown()
	{
		System::shutdown();
		exit;
	}

	public static function validateInternalCall()
	{
		// Se não tem HTTP_REFERER está sendo acessado diretamente pelo link
		if (!isset($_SERVER['HTTP_REFERER']))
		{
			// Se estiver em dev podemos permitir
			if (SYS_DEVELOP !== true)
				throw new ApiUnauthorizedException();
		}

		// Se tem HTTP_REFERER foi chamado de alguma página por AJAX por exemplo
		else
		{
			// No caso dessa API só será permitido o acesso do nosso site
			if (!StringUtil::startsWith($_SERVER['HTTP_REFERER'], DOMAIN) && !StringUtil::startsWith($_SERVER['HTTP_REFERER'], WWW_DOMAIN))
				throw new ApiUnauthorizedException();
		}
	}

	public static function getInstance()
	{
		if (self::$instance === null)
			self::$instance = new ApiConnection();

		return self::$instance;
	}
}

?>