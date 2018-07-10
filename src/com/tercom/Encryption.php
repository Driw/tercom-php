<?php

namespace tercom;

class Encryption
{
	const PHP_ENCRYPT_KEY = 'hg65M03DgUpdHjeuDM6rkgOa6ImBQ1';

	private $key;
	private $encryptMethod;

	public function __construct()
	{
		$this->setKey(self::PHP_ENCRYPT_KEY);
		$this->encryptMethod = 'AES-256-CBC';
	}

	public function setkey($key)
	{
		$this->key = $key;
	}

	public function encrypt($string)
	{
		$this->parse($string, $key, $iv);
		$output = base64_encode(openssl_encrypt($string, $this->encryptMethod, $key, 0, $iv));

		return $output;
	}

	public function decrypt($string)
	{
		$this->parse($string, $key, $iv);
		$output = openssl_decrypt(base64_decode($string), $this->encryptMethod, $key, 0, $iv);

		return $output;
	}

	private function parse($string, &$key, &$iv)
	{
		$secret_key = sprintf('%s_key', self::PHP_ENCRYPT_KEY);
		$secret_iv = sprintf('%s_iv', self::PHP_ENCRYPT_KEY);

		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
	}
}

?>