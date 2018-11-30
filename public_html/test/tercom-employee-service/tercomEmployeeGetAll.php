<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('tercomEmployee/getAll', []);
	}
}
include '../execute.php';

