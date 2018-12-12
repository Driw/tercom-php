<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['name']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Produto ID: <input type='text' name='idProduct' required></p>
	<p>Fornecedor ID: <input type='text' name='idProvider' required></p>
	<p>Fabricante ID: <input type='text' name='idManufacture'></p>
	<p>Embalagem de Produto ID: <input type='text' name='idProductPackage' required></p>
	<p>Tipo de Produto ID: <input type='text' name='idProductType'></p>
	<p>Nome: <input type='text' name='name'></p>
	<p>Quantidade: <input type='text' name='amount' required></p>
	<p>Preço: <input type='text' name='price' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idProduct = $_GET['idProduct']; unset($_GET['idProduct']);

		return GeradorDeDados::callWebService("productPrice/add/$idProduct", $_GET);
	}
}
include_once '../execute.php';

