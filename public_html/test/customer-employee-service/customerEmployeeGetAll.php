<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('customerEmployee/getAll', []);
	}
}
include '../execute.php';

