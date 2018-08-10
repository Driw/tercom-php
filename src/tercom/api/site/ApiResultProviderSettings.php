<?php

namespace tercom\api\site;

use dProject\Primitive\AdvancedObject;
use dProject\restful\ApiResult;

class ApiResultProviderSettings extends AdvancedObject implements ApiResult
{
	private $minCompanyNameLen;
	private $maxCompanyNameLen;
	private $minFantasyNameLen;
	private $maxFantasyNameLen;
	private $minSpokesmanLen;
	private $maxSpokesmanLen;
	private $maxSiteLen;

	public function __construct()
	{
		$this->minCompanyNameLen = MIN_COMPANY_NAME_LEN;
		$this->maxCompanyNameLen = MAX_COMPANY_NAME_LEN;
		$this->minFantasyNameLen = MIN_FANTASY_NAME_LEN;
		$this->maxFantasyNameLen = MAX_FANTASY_NAME_LEN;
		$this->minSpokesmanLen = MIN_SPOKESMAN_LEN;
		$this->maxSpokesmanLen = MAX_SPOKESMAN_LEN;
		$this->maxSiteLen = MAX_SITE_LEN;
	}

	public function toApiArray(): array
	{
		return $this->toArray(true);
	}
}

?>