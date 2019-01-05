<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('productUnit/settings', []);
	}
}
include_once '../execute.php';

