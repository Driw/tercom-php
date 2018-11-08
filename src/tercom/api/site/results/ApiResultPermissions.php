<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\Permissions;

/**
 * @see ApiResult
 * @see Permissions
 * @author Andrew
 */

class ApiResultPermissions implements ApiResult
{
	/**
	 * @var string mensagem personalidade do resultado.
	 */
	private $message;
	/**
	 * @var Permissions
	 */
	private $permission;

	/**
	 *
	 */
	public function __construct()
	{
		$this->permission = new Permissions();
	}

	/**
	 *
	 * @param Permissions $addresses
	 */
	public function setPermissions(Permissions $addresses)
	{
		$this->permission = $addresses;
	}

	/**
	 * @param string $message mensagem personalidade do resultado.
	 */
	public function setMessage($message)
	{
		$this->message = format(func_get_args());
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiResult::toApiArray()
	 */
	public function toApiArray():array
	{
		$array = $this->permission->toArray();

		if ($this->message !== null)
			$array['message'] = $this->message;

		return $array;
	}
}

