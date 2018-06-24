<?php

namespace tercom\api;

use tercom\Core\Encryption;

define('API_FAILURE', 0);
define('API_SUCCESS', 1);
define('API_PHP_FATAL_ERROR', 97);
define('API_ERROR_EXCEPTION', 98);
define('API_ERROR_API_EXCEPTION', 99);

/**
 * @author Andrew
 */

class ApiConnection
{
	/**
	 * 
	 */

	public function __construct()
	{
		
	}

	/**
	 * 
	 */

	public function start()
	{
		header('Content-type: application/json');
		header('Cache-Control: no-cache');
		header('Pragma: no-cache');
	}

	/**
	 * @throws \Exception
	 */

	public function identify()
	{
		$parameters = explode('/', substr($_SERVER['REQUEST_URI'], 1));

		if ($parameters[0] !== 'api')
			throw new \Exception('bad request');

		$variables = array_slice($parameters, 2); // 0: sistema de api, 1: nome da api
		$apiname = $parameters[1];
		$classname = sprintf('%s\Api%s', __NAMESPACE__, $apiname);

		/**
		 * @var ApiInterface $api
		 */
		$api = new $classname($this, $apiname, $variables);
		$result = $api->execute();

		echo json_encode([
			'status' => $result !== null && $result !== false && !isset($result['failure']) ? API_SUCCESS : API_SUCCESS,
			'result' => $result,
		]);
	}

	/**
	 * @param \Exception $e
	 * @param integer $code
	 * @see ApiConnection::jsonFatalError()
	 */

	public function jsonException(\Exception $e, $code)
	{
		if ($e instanceof ApiException)
			self::jsonFatalError(API_ERROR_API_EXCEPTION, $e->getMessage(), $e->getCode(), $e->getLine(), $e->getFile());
		else
			self::jsonFatalError(API_ERROR_EXCEPTION, $e->getMessage(), $e->getCode(), $e->getLine(), $e->getFile());
	}

	/**
	 * @param integer $status
	 * @param string $message
	 * @param integer $errorCode
	 * @param integer $target
	 * @param string $source
	 */

	public static function jsonFatalError($status, $message, $errorCode, $target, $source)
	{
		$encryption = new Encryption();

		echo json_encode([
			'status' => $status,
			'message' => $message,
			'result' => [
				'code' => $errorCode,
				'target' => $target,
				'source' => SYS_DEVELOP ? $source : $encryption->encrypt($source),
			],
		]);
	}
}

?>