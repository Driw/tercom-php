<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['name']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Produto ID: <input type='text' name='idProduct'><br>
	Fornecedor ID: <input type='text' name='idProvider'><br>
	Fabricante ID: <input type='text' name='idManufacture'><br>
	Embalagem de Produto ID: <input type='text' name='idProductPackage'><br>
	Tipo de Produto ID: <input type='text' name='idProductType'><br>
	Nome: <input type='text' name='name'><br>
	Quantidade: <input type='text' name='amount'><br>
	Preço: <input type='text' name='price'><br>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$idProduct = $_GET['idProduct']; unset($_GET['idProduct']);
	return GeradorDeDados::callWebService("productPrice/add/$idProduct", $_GET);
}
require_once 'execute.php';

?>