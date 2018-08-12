<?php

namespace tercom\api\site;

use dProject\restful\ApiResult;
use tercom\entities\lists\Providers;

class ApiResultProviders implements ApiResult
{
	private $providers;
	private $message;

	public function __construct()
	{
		$this->providers = new Providers();
	}

	public function getProviders(): Providers
	{
		return $this->providers;
	}

	public function setProviders(Providers $providers)
	{
		$this->providers = $providers;
	}

	public function getMessage():?string
	{
		return $this->message;
	}

	public function setMessage(string $message)
	{
		$this->message = $message;
	}

	public function toApiArray(): array
	{
		$array = $this->providers->toArray();

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

?>