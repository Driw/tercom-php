<?php

namespace tercom;

class Encryption
{
	const PHP_ENCRYPT_KEY = 'hg65M03DgUpdHjeuDM6rkgOa6ImBQ1';

	private $key;
	private $iv;
	private $encryptMethod;

	public function __construct()
	{
		$this->encryptMethod = 'AES-256-CBC';
		$this->setKey(self::PHP_ENCRYPT_KEY);
	}

	public function setkey($key)
	{
		$this->key = hash('sha256', sprintf('%s_key', $key));
		$this->iv = substr(hash('sha256', sprintf('%s_iv', $key)), 0, 16);
	}

	public function encrypt($string)
	{
		$output = openssl_encrypt($string, $this->encryptMethod, $this->key, 0, $this->iv);
		$output = base64_encode($output);

		return $output;
	}

	public function decrypt($string)
	{
		$output = base64_decode($string);
		$output = openssl_decrypt($output, $this->encryptMethod, $this->key, 0, $this->iv);

		return $output;
	}
}

?>