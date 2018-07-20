<?php

namespace tercom\api;

use ReflectionClass;
use dProject\Primitive\AdvancedObject;
use dProject\MySQL\MySQL;
use dProject\Primitive\ArrayData;
use tercom\core\System;

class ApiActionInterface extends AdvancedObject
{
	private $apiConnection;
	private $apiParent;
	private $addon;
	private $vars;

	public function __contruct(ApiConnection $apiConnection, $addon, array $vars, ApiActionInterface $apiParent = null)
	{
		$this->apiConnection = $apiConnection;
		$this->apiParent = $apiParent;
		$this->addon = $addon;
		$this->vars = $vars;
	}

	public function getApiParent(): ApiActionInterface
	{
		return $this->apiParent;
	}

	public function getAddon(): string
	{
		return $this->addon;
	}

	protected function get($index): string
	{
		return isset($this->vars[$index]) ? $this->vars[$index] : '';
	}

	protected function getMySQL(): MySQL
	{
		return System::getWebConnection();
	}

	public function execute(): ApiResult
	{
		throw new ApiException('api não implementada');
	}

	protected function defaultExecute(): ApiResult
	{
		if (empty($this->get(0)))
			return $this->callHelp();

		$method_name = sprintf('action%s', ucfirst($this->get(0)));

		if (method_exists($this, $method_name))
		{
			$array = array_splice($this->vars, 1);
			$data = new ArrayData($array);

			return $this->$method_name($data);
		}

		$class = new ReflectionClass(get_class($this));
		$classname = sprintf('%s\%s\Api%s', $class->getNamespaceName(), $this->addon, ucfirst($this->get(0)));

		if (!class_exists($classname))
			throw new ApiMethodNotAllowedException();

		$apiInterface = self::newApiAction($classname);

		return $apiInterface->execute();
	}

	private function newApiAction($classname): ApiActionInterface
	{
		return new $classname($this->apiConnection, $this->get(0), array_slice($this->vars, 1), $this);
	}

	public function callHelp(): ApiResult
	{
		throw new ApiMethodNotAllowedException();
	}
}

?>