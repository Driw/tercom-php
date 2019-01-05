<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('productGroup/settings', []);
	}
}
include '../execute.php';

