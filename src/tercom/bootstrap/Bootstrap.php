<?php

namespace tercom\bootstrap;

class Bootstrap
{
	public static function alertMessage($class, $mensagem, $titulo = null, $colxs = null)
	{
		echo "			<div class='row " .self::parseToFullColXS($colxs). "'>".PHP_EOL;
		echo "				<div class='alert $class col-12'>".PHP_EOL;
		if (isset($titulo))
		echo "					<b>$titulo:</b> $mensagem".PHP_EOL;
		else
		echo "					$mensagem".PHP_EOL;
		echo "				</div>".PHP_EOL;
		echo "			</div>".PHP_EOL;
	}

	public static function alertSuccess($mensagem, $titulo = null, $colxs = null)
	{
		self::alertMessage('alert-success', $mensagem, $titulo, $colxs);
	}

	public static function alertInfo($mensagem, $titulo = null, $colxs = null)
	{
		self::alertMessage('alert-info', $mensagem, $titulo, $colxs);
	}

	public static function alertWarning($mensagem, $titulo = null, $colxs = null)
	{
		self::alertMessage('alert-warning', $mensagem, $titulo, $colxs);
	}

	public static function alertDanger($mensagem, $titulo = null, $colxs = null)
	{
		self::alertMessage('alert-danger', $mensagem, $titulo, $colxs);
	}

	public static function alertException(\Exception $e, $titulo = null, $colxs = null)
	{
		if ($e instanceof BootstrapException)
			switch ($e->getCode())
			{
				case BootstrapException::BOOTSTRAP_TYPE_INFO: self::alertInfo($e->getMessage(), $titulo, $colxs); return;
				case BootstrapException::BOOTSTRAP_TYPE_SUCCESS: self::alertSuccess($e->getMessage(), $titulo, $colxs); return;
				case BootstrapException::BOOTSTRAP_TYPE_WARNING: self::alertWarning($e->getMessage(), $titulo, $colxs); return;
				case BootstrapException::BOOTSTRAP_TYPE_DANGER: self::alertDanger($e->getMessage(), $titulo, $colxs); return;
			}

		self::alertDanger($e->getMessage(), $titulo, $colxs);
	}

	private static function parseToFullColXS($colxs)
	{
		if (!isset($colxs))
			$colxs = 12;

		$offset = (12 - $colxs) / 2;

		return self::newClassColXS($colxs, $offset, 12 - $colxs - $offset);
	}

	private static function newClassColXS($colxs, $offset, $offsetRight)
	{
		return "col-$colxs offset-$offset offset-r-$offsetRight";
	}
}

?>