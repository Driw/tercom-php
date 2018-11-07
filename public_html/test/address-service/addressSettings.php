<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService("address/settings", []);
	}
}
include '../execute.php';

