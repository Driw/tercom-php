<?php

namespace tercom\api;

use Exception;
use tercom\Core\Encryption;

class ApiConnection
{
	private $timeup;
	private $apiname;

	public function __construct()
	{
		$this->timeup = now();
	}

	public function start()
	{
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
			self::jsonFatalError(ApiResponse::API_ERROR_API_EXCEPTION, $e->getMessage(), $e->getCode(), $e->getLine(), $e->getFile());
		else
			self::jsonFatalError(ApiResponse::API_ERROR_EXCEPTION, $e->getMessage(), $e->getCode(), $e->getLine(), $e->getFile());
	}

	public static function jsonFatalError(int $status, string $message, int $errorCode, int $target, string $source)
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

		echo $response->toApiJSON();
	}
}

?>