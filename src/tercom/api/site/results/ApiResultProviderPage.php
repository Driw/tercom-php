<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\lists\Providers;
use dProject\Primitive\AdvancedObject;

class ApiResultProviderPage extends AdvancedObject implements ApiResult
{
	private $pageCount;
	private $providers;

	public function __construct()
	{
		$this->pageCount = 0;
		$this->providers = new Providers();
	}

	public function getPageCount(): int
	{
		return $this->pageCount;
	}

	public function setPageCount(int $pageCount)
	{
		$this->pageCount = $pageCount;
	}

	public function getProviders(): Providers
	{
		return $this->providers;
	}

	public function setProviders(Providers $providers)
	{
		$this->providers = $providers;
	}

	public function toApiArray(): array
	{
		return $this->toArray();
	}
}

?>