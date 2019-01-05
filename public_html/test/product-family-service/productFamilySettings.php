<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('productFamily/settings', []);
	}
}
include '../execute.php';

