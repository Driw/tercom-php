<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;

/**
 * @see ApiResult
 * @author Andrew
 */

class ApiResultSimpleValidation implements ApiResult
{
	/**
	 * @var bool determina se a validação foi bem sucedida.
	 */
	private $ok;
	/**
	 * @var string mensagem personalizada referente a validação efetuada.
	 */
	private $message;

	/**
	 *
	 */

	public function __construct()
	{
		$this->ok = false;
		$this->message = 'validação mal sucedida';
	}

	/**
	 * @return boolean
	 */

	public function isOk(): bool
	{
		return $this->ok;
	}

	/**
	 * @param bool $ok
	 */

	public function setOk(bool $ok)
	{
		$this->ok = $ok;
	}

	/**
	 * @return string
	 */

	public function getMessage(): string
	{
		return $this->message;
	}

	/**
	 * @param string $message
	 */

	public function setMessage(string $message)
	{
		$this->message = format(func_get_args());
	}

	/**
	 * @param bool $ok
	 * @param string $message
	 */

	public function setOkMessage(bool $ok, string $message)
	{
		$this->setOk($ok);
		$this->setMessage(format(array_slice(func_get_args(), 1)));
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */

	public function toApiArray():array
	{
		return [
			'ok' => $this->ok,
			'message' => $this->message,
		];
	}
}

