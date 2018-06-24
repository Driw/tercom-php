<?php

namespace tercom\api;

use dProject\Primitive\AdvancedObject;

/**
 * @author Andrew
 */

class ApiInterface extends AdvancedObject
{
	/**
	 * @var ApiConnection
	 */
	private $apiConnection;
	/**
	 * @var ApiInterface
	 */
	private $apiParent;
	/**
	 * @var string
	 */
	private $apiname;
	/**
	 * @var array
	 */
	private $vars;

	/**
	 * @param ApiConnection $apiConnection
	 * @param string $apiname
	 * @param array $vars
	 * @param ApiInterface $apiParent
	 */

	public function __contruct(ApiConnection $apiConnection, $apiname, array $vars, ApiInterface $apiParent = null)
	{
		$this->apiConnection = $apiConnection;
		$this->apiParent = $apiParent;
		$this->apiname = $apiname;
		$this->vars = $vars;
	}

	/**
	 * @return ApiInterface
	 */

	public function getApiParent()
	{
		return $this->apiParent;
	}

	/**
	 * @return string
	 */

	public function getApiName()
	{
		return $this->apiname;
	}

	/**
	 * @param number $index
	 * @throws ApiException
	 * @return string
	 */

	protected function get($index)
	{
		return isset($this->vars[$index]) ? $this->vars[$index] : null;
	}

	/**
	 * @throws ApiException
	 */

	public function execute()
	{
		throw new ApiException('api não implementada');
	}

	/**
	 * @throws ApiException
	 */

	protected function defaultExecute()
	{
		$classname = sprintf('%s\%s\Api%s', __NAMESPACE__, $this->apiname, ucfirst($this->get(0)));

		if (!class_exists($classname))
			throw new ApiException(sprintf('%s não é uma ação válida', $this->get(0)));

		/**
		 * @var ApiInterface $apiInterface
		 */
		$apiInterface = new $classname($this->apiConnection, $this->get(0), array_slice($this->vars, 1), $this);
		return $apiInterface->execute();
	}

	protected function defaultLeafExecute()
	{
		if (empty($this->get(0)))
			return $this->callHelp();

		$method_name = sprintf('call%s', ucfirst($this->get(0)));

		if (!method_exists($this, $method_name))
			throw new ApiException(sprintf('%s não é uma ação válida para %s', $this->get(0), $this->getApiName()));

		return $this->$method_name();
	}

	public function callHelp()
	{
		
	}
}

?>