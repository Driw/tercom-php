<?php

use tercom\GeradorDeDados;

include_once '../include.php';
function testExecute()
{
	return GeradorDeDados::callWebService('productPrice/settings', []);
}
include_once '../execute.php';

?>