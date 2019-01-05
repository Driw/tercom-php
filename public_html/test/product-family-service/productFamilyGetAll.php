<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('productFamily/getAllFamilies', []);
	}
}
include '../execute.php';

