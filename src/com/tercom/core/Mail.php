<?php

namespace tercom\core;

use PHPMailer\PHPMailer\PHPMailer;

class Mail extends PHPMailer
{
	public function __construct($exceptions = true)
	{
		parent::__construct($exceptions);

		$mailConfig = System::getConfig();

		$this->isSMTP();
		$this->Host = $mailConfig->getHost();
		$this->SMTPAuth = $mailConfig->getSMTPAuth();
		$this->Username = $mailConfig->getUsername();
		$this->Password = $mailConfig->getPassword();
		$this->SMTPSecure = $mailConfig->getSMTPSecure();
		$this->Port = $this->getPort();
		$this->CharSet = 'UTF-8';
		$this->isHTML(true);
	}

	public function setEmailLog()
	{
		$this->addReplyTo(MAIL_ADDRESS_LOG, 'Email Log');
	}

	public function setEmailContent(MailContent $mailContent)
	{
		$this->Body = $mailContent->getHtml();
		$this->AltBody = strip_tags($mailContent->getHtml());
	}
}

?>