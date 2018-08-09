<?php

namespace tercom\api;

use dProject\Primitive\ArrayDataException;

class ApiMissParam implements ApiResult
{
	private $message;

	public function __construct(ArrayDataException $e)
	{
		$this->message = str_replace('não existe', 'não definido', $e->getMessage());
	}

	public function toApiArray(): array
	{
		return [
			'status' => ApiResponse::API_MISS_PARAM,
			'message' => $this->message,
		];
	}
}

?>