<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	return GeradorDeDados::callWebService('provider/settings', []);
}
require_once 'execute.php';

?>