<?php

namespace tercom\api\site;

use tercom\api\ApiResult;
use tercom\entities\lists\ProviderContacts;

class ApiResultProviderContacts implements ApiResult
{
	private $providerContacts;
	private $message;

	public function __construct()
	{
		$this->providerContacts = new ProviderContacts();
	}

	public function getProviderContacts(): ProviderContacts
	{
		return $this->providerContacts;
	}

	public function setProviderContacts(ProviderContacts $providerContacts)
	{
		$this->providerContacts = $providerContacts;
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
		$array = $this->providerContacts->toArray();

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

?>