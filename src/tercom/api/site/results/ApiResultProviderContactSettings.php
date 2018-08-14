<?php

namespace tercom\api\site\results;

use dProject\Primitive\AdvancedObject;
use dProject\restful\ApiResult;
use tercom\entities\ProviderContact;

class ApiResultProviderContactSettings extends AdvancedObject implements ApiResult
{
	private $minNameLen;
	private $maxNameLen;
	private $minPositionLen;
	private $maxPositionLen;
	private $maxEmailLen;
	private $phoneSettings;

	public function __construct()
	{
		$this->minNameLen = ProviderContact::MIN_NAME_LEN;
		$this->maxNameLen = ProviderContact::MAX_NAME_LEN;
		$this->minPositionLen = ProviderContact::MIN_POSITION_LEN;
		$this->maxPositionLen = ProviderContact::MAX_POSITION_LEN;
		$this->maxEmailLen = ProviderContact::MAX_EMAIL_LEN;
		$this->phoneSettings = new ApiResultPhoneSettings();
	}

	public function toApiArray(): array
	{
		return $this->toArray(true);
	}
}

?>