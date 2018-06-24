<?php

namespace tercom\Core;

use PHPMailer\PHPMailer\Exception;

class MailContent
{
	private $html;

	public function __construct($template)
	{
		$filename = sprintf('%s/%s.html', DIR_MAILS, $template);

		if (!file_exists($filename))
			throw new Exception("modelo de e-mail <b>$template</b> não encontrado");

		$this->html = file_get_contents($filename);
	}

	public function set($var, $value)
	{
		$this->html = str_replace("{$var}", $value, $this->html);
	}

	public function getHtml()
	{
		return $this->html;
	}
}

?>