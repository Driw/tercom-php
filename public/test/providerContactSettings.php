<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	return GeradorDeDados::callWebService('providerContact/settings', []);
}
require_once 'execute.php';

?>