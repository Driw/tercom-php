<?php

namespace tercom\bootstrap;

/**
 * <h1>Bootstrap</h1>
 *
 * @see \Exception
 *
 * @author Andrew
 */

class BootstrapException extends \Exception
{
	/**
	 * @var integer mensagem de perigo.
	 */
	const BOOTSTRAP_TYPE_DANGER = 0;
	/**
	 * @var integer mensagem de sucesso.
	 */
	const BOOTSTRAP_TYPE_SUCCESS = 1;
	/**
	 * @var integer mensagem de informação.
	 */
	const BOOTSTRAP_TYPE_INFO = 2;
	/**
	 * @var integer mensagem de aviso.
	 */
	const BOOTSTRAP_TYPE_WARNING = 3;

	/**
	 * 
	 * @param string $message
	 * @param integer $code
	 * @param \Throwable $previous
	 */

	public function __construct($message, $code, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	/**
	 * 
	 * @param string $message
	 * @return BootstrapException
	 */

	public static function newInfo($message)
	{
		return new BootstrapException(sprintf($message, array_slice(func_get_args(), 1)), self::BOOTSTRAP_TYPE_INFO);
	}

	/**
	 *
	 * @param string $message
	 * @return BootstrapException
	 */

	public static function newSuccess($message)
	{
		return new BootstrapException(sprintf($message, array_slice(func_get_args(), 1)), self::BOOTSTRAP_TYPE_SUCCESS);
	}

	/**
	 *
	 * @param string $message
	 * @return BootstrapException
	 */

	public static function newWarning($message)
	{
		return new BootstrapException(sprintf($message, array_slice(func_get_args(), 1)), self::BOOTSTRAP_TYPE_WARNING);
	}

	/**
	 *
	 * @param string $message
	 * @return BootstrapException
	 */

	public static function newDanger($message)
	{
		return new BootstrapException(sprintf($message, array_slice(func_get_args(), 1)), self::BOOTSTRAP_TYPE_DANGER);
	}
}

?>