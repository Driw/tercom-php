<?php

namespace tercom\api;

use tercom\Encryption;

class APiFatalError implements ApiResult
{
	private $errorCode;
	private $target;
	private $source;

	public function getErrorCode(): int
	{
		return $this->errorCode;
	}

	public function setErrorCode($errorCode)
	{
		$this->errorCode = $errorCode;
	}

	public function getTarget(): int
	{
		return $this->target;
	}

	public function setTarget($target)
	{
		$this->target = $target;
	}

	public function getSource(): string
	{
		return $this->source;
	}

	public function setSource($source)
	{
		if (DEV)
		{
			$encryption = new Encryption();
			$source = $encryption->encrypt($source);
		}

		$this->source = $source;
	}


	public function toApiArray(): array
	{
		return [
			'errorCode' => $this->errorCode,
			'target' => $this->target,
			'source' => $this->source,
		];
	}
}

?>