<?php

namespace tercom\api\site\results;

use dProject\restful\ApiResult;
use tercom\entities\ProviderContact;

class ApiResultProviderContact implements ApiResult
{
	private $providerContact;
	private $message;

	public function __construct()
	{
		$this->providerContact = new ProviderContact();
	}

	public function getProviderContact(): ProviderContact
	{
		return $this->providerContact;
	}

	public function setProviderContact(ProviderContact $providerContact)
	{
		$this->providerContact = $providerContact;
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
		$array = $this->providerContact->toArray();

		if (!empty($this->message))
			$array['message'] = $this->message;

		return $array;
	}
}

?>