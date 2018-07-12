<?php

namespace tercom\api\site;

use tercom\api\ApiResult;
use tercom\entities\Provider;

class ApiResultProvider implements ApiResult
{
	private $provider;
	private $message;

	public function __construct()
	{
		$this->provider = new Provider();
	}

	public function getProvider(): Provider
	{
		return $this->provider;
	}

	public function setProvider(Provider $provider)
	{
		$this->provider = $provider;
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
		$array = $this->provider->toArray();

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

?>