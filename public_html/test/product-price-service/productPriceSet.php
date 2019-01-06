<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProductPrice']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Preço de Produto ID: <input type='text' name='idProductPrice' required></p>
	<p>Produto ID: <input type='text' name='idProduct'></p>
	<p>Fornecedor ID: <input type='text' name='idProvider'></p>
	<p>Fabricante ID: <input type='text' name='idManufacture'></p>
	<p>Embalagem de Produto ID: <input type='text' name='idProductPackage'></p>
	<p>Tipo de Produto ID: <input type='text' name='idProductType'></p>
	<p>Nome: <input type='text' name='name'></p>
	<p>Quantidade: <input type='text' name='amount'></p>
	<p>Preço: <input type='text' name='price'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idProductPrice = $_GET['idProductPrice']; unset($_GET['idProductPrice']);
		return GeradorDeDados::callWebService("productPrice/set/$idProductPrice", $_GET, true);
	}
}
include_once '../execute.php';

