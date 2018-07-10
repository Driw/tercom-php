<?php

namespace tercom\api;

class ApiResponse
{
	const API_FAILURE = 00;
	const API_SUCCESS = 01;
	const API_PHP_FATAL_ERROR = 97;
	const API_ERROR_EXCEPTION = 98;
	const API_ERROR_API_EXCEPTION = 99;

	private $status;
	private $message;
	private $result;
	private $time;

	public function __construct()
	{
		$this->time = 0;
		$this->status = self::API_FAILURE;
		$this->message = null;
		$this->result = [];
	}

	private function clear()
	{
		$this->status = null;
		$this->message = null;
		$this->result = null;
	}

	public function getStatus(): int
	{
		return $this->status;
	}

	public function setStatus(int $status)
	{
		$this->status = $status;
	}

	public function getMessage(): string
	{
		return $this->message;
	}

	public function setMessage(string $message)
	{
		$this->message = $message;
	}

	public function getResult(): array
	{
		return $this->result;
	}

	public function setResult(array $result)
	{
		$this->result = $result;
	}

	public function setApiResult(ApiResult $apiResult)
	{
		$this->clear();
		$array = $apiResult->toApiArray();

		if (isset($array['status']))
		{
			$this->status = $array['status'];
			unset($array['status']);
		}

		if (isset($array['message']))
		{
			$this->message = $array['message'];
			unset($array['message']);
		}

		if ($this->status == null) $this->status = self::API_SUCCESS;
		if ($this->message == null) $this->message = 'ação executada com êxito';

		$this->result = $array;
	}

	public function getTime(): int
	{
		return $this->time;
	}

	public function setTime(int $time)
	{
		$this->time = $time;
	}

	public function toApiArray(): array
	{
		return [
			'status' => $this->status,
			'message' => $this->message,
			'time' => strms($this->time),
			'result' => $this->result,
		];
	}

	public function toApiJSON()
	{
		return json_encode($this->toApiArray());
	}
}

?>