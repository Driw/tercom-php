<?php

use tercom\Page;
use tercom\Core\System;
use dProject\Primitive\KillPage;
use dProject\Primitive\FriendlyPage;
use dProject\Primitive\FriendlyPageException;

require_once 'vendor/autoload.php';
require_once 'constants.php';
require_once 'globalFunctions.php';

register_shutdown_function('PHPFatalError');

Page::verifyPHPVersion();
Page::verifyPHPErrorReport();

System::init();
System::addAccessLog();
System::verifySession();
System::verifyLogin();
System::verifyTemplate();

KillPage::setMethodModal('KillPageDialog');
FriendlyPage::getInstance()->setDevelopment(SYS_DEVELOP);
FriendlyPage::getInstance()->setFriendlyNavMethod('FriendlyPageNavigation');

$CONFIG = System::getConfig();
$FRIENDLY_PAGE = FriendlyPage::getInstance();

try {

	Page::openHtml();
	Page::includeHeader();
	Page::openBody();
	{
		Page::includeNavTopBar();
		Page::openContainer();
		{
			Page::includeBanner();

			try {
				// As outras páginas são sempre iguais, logo devem sempre funcionar, entretanto no container é dinâmico então aqui pode aparecer erros
				Page::includeContainer();
			} catch (Exception $e) {

				if (SYS_DEVELOP && !($e instanceof FriendlyPageException))
					KillPage::showModalExceptionDetailed($e);
				else
					KillPage::showModalException($e);
			}

		}
		Page::closeContainer();
		Page::includeFooter();
		KillPage::showErrorMessages();
	}
	Page::closeBody();
	Page::closeHtml();

} catch (Exception $e) {
	die($e->getMessage());
}

?>
