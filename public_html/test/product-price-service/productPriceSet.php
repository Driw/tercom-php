<?php

use tercom\GeradorDeDados;

include_once '../include.php';
function testExecute()
{
	if (!isset($_GET['idProductPrice']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Preço de Produto ID: <input type='text' name='idProductPrice'><br>
	Produto ID: <input type='text' name='idProduct'><br>
	Fornecedor ID: <input type='text' name='idProvider'><br>
	Fabricante ID: <input type='text' name='idManufacture'><br>
	Embalagem de Produto ID: <input type='text' name='idProductPackage'><br>
	Tipo de Produto ID: <input type='text' name='idProductType'><br>
	Nome: <input type='text' name='name'><br>
	Quantidade: <input type='text' name='amount'><br>
	Preço: <input type='text' name='price'><br>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
	}
	$idProductPrice = $_GET['idProductPrice']; unset($_GET['idProductPrice']);
	return GeradorDeDados::callWebService("productPrice/set/$idProductPrice", $_GET);
}
include_once '../execute.php';

?>