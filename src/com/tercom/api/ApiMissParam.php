<?php

namespace tercom\api;

class ApiMissParam implements ApiResult
{
	private $message;

	public function __construct(string $message)
	{
		$this->message = str_replace('não existe', 'não definido', $message);
	}

	public function toApiArray(): array
	{
		return [
			'status' => ApiResponse::API_FAILURE,
			'message' => $this->message,
		];
	}
}

?>