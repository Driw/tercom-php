<?php

namespace tercom\api\site;

use tercom\api\ApiResult;
use tercom\entities\Provider;

class ApiResultProvider implements ApiResult
{
	private $provider;

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

	public function toApiArray(): array
	{
		return $this->provider->toArray();
	}
}

?>