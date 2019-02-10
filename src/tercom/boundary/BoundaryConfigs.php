<?php

namespace tercom\boundary;

use dProject\Primitive\Config;
use tercom\Functions;

/**
 * @see Config
 * @author Andrew
 */

abstract class BoundaryConfigs
{
	/**
	 * @var Config[]
	 */
	private $configs;
	/**
	 * @var bool
	 */
	protected $isLoginTercom;

	/**
	 *
	 */

	public function __construct($isLoginTercom)
	{
		$this->configs = [];
		$this->isLoginTercom = $isLoginTercom;
	}

	/**
	 * @return Config
	 */

	public abstract function getHead(): Config;

	/**
	 * @return Config
	 */

	public abstract function getStyleSheets(): Config;

	/**
	 * @return Config
	 */

	public abstract function getJavaScripts(): Config;

	/**
	 * @param string $name
	 * @return Config
	 */

	public function getConfigByName(string $name): Config
	{
		if (isset($this->configs[$name]))
			return $this->configs[$name];

		return ($this->configs[$name] = $this->newConfigs($name));
	}

	/**
	 * @param string $name
	 * @return Config
	 */

	private function newConfigs(string $name): Config
	{
		$configs = $this->getConfigsJson($name);
		$config = new Config($configs);

		return $config;
	}

	/**
	 * @param string $name
	 * @throws BoundaryException
	 * @return array
	 */

	private function getConfigsJson(string $name): array
	{
		$filename = sprintf('%s/%s.json', DIR_CONFIGS, $name);

		if (!file_exists($filename))
			throw new BoundaryException(sprintf('configurações %s não encontrada', $name), BoundaryException::CONFIG_NOT_FOUND);

		$json = file_get_contents($filename);

		if (!Functions::isUTF8($json))
			$json = utf8_encode($json);

		$array = json_decode($json, true);

		if (json_last_error() !== JSON_ERROR_NONE)
			throw new BoundaryException(sprintf('falha nas configurações de %s (%d: %s)', $name, json_last_error(), json_last_error_msg()), BoundaryException::CONFIG_PARSE);

		return $array;
	}
}