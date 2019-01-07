<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService("managePermissions/settings", []);
	}
}
include '../execute.php';

