<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('productSubGroup/settings', []);
	}
}
include '../execute.php';

