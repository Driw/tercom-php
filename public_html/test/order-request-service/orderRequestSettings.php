<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('orderRequest/settings', []);
	}
}
include '../execute.php';

