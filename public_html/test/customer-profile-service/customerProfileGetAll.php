<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('customerProfile/getAll', []);
	}
}
include '../execute.php';

