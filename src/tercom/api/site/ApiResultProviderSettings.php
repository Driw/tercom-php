<?php

namespace tercom\api\site;

use dProject\Primitive\AdvancedObject;
use dProject\restful\ApiResult;
use tercom\entities\Provider;

class ApiResultProviderSettings extends AdvancedObject implements ApiResult
{
	private $minCompanyNameLen;
	private $maxCompanyNameLen;
	private $minFantasyNameLen;
	private $maxFantasyNameLen;
	private $minSpokesmanLen;
	private $maxSpokesmanLen;
	private $maxSiteLen;
	private $phoneSettings;

	public function __construct()
	{
		$this->minCompanyNameLen = Provider::MIN_COMPANY_NAME_LEN;
		$this->maxCompanyNameLen = Provider::MAX_COMPANY_NAME_LEN;
		$this->minFantasyNameLen = Provider::MIN_FANTASY_NAME_LEN;
		$this->maxFantasyNameLen = Provider::MAX_FANTASY_NAME_LEN;
		$this->minSpokesmanLen = Provider::MIN_SPOKESMAN_LEN;
		$this->maxSpokesmanLen = Provider::MAX_SPOKESMAN_LEN;
		$this->maxSiteLen = Provider::MAX_SITE_LEN;
		$this->phoneSettings = new ApiResultPhoneSettings();
	}

	public function toApiArray(): array
	{
		return $this->toArray(true);
	}
}

?>