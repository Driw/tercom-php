<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\Permission;

/**
 * @see ApiResult
 * @see Permission
 * @author Andrew
 */

class ApiResultPermission implements ApiResult
{
	/**
	 * @var string mensagem personalidade do resultado.
	 */
	private $message;
	/**
	 * @var Permission
	 */
	private $permission;

	/**
	 *
	 */
	public function __construct()
	{
		$this->permission = new Permission();
	}

	/**
	 * @param Permission $permission
	 */
	public function setPermission(Permission $permission)
	{
		$this->permission = $permission;
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

