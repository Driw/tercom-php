<?php

namespace tercom\boundary\dashboard;

use dProject\template\DefaultTemplate;

class DashboardTemplate extends DefaultTemplate
{
	/**
	 * @var string
	 */
	private static $directory = '';

	/**
	 * @param string $script
	 */

	public function __construct($script)
	{
		$filename = sprintf('%s/%s.html', self::$directory, $script);

		parent::__construct($filename);
	}

	/**
	 * {@inheritDoc}
	 * @see \raelgc\view\Template::addFile()
	 */

	public function addFile($varname, $filename)
	{
		parent::addFile($varname, sprintf('%s/%s.html', self::$directory, $filename));
	}

	/**
	 * @return string
	 */

	public static function getDirectory(): string
	{
		return self::$directory;
	}

	/**
	 * @param string $directory
	 */

	public static function setDirectory(string $directory)
	{
		self::$directory = $directory;
	}
}

