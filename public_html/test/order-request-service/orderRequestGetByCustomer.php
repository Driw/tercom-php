<?php

use tercom\GeradorDeDados;
use tercom\dao\OrderRequestDAO;

include '../include.php';
{
	function testExecute()
	{
		if (count($_GET) === 0)
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>*Funcionário de Cliente ID (se não informado usa LOGIN): <input type='text' name='idCustomerEmployee'></p>
	<p>Modo: <select name='mode' required>
		<option value='<?php echo OrderRequestDAO::SELECT_MODE_ALL; ?>'>Todos</option>
		<option value='<?php echo OrderRequestDAO::SELECT_MODE_CUSTOMER_CANCEL; ?>'>Cancelado pelo Funcionário de Cliente</option>
		<option value='<?php echo OrderRequestDAO::SELECT_MODE_TERCOM_CANCEL; ?>'>Cancelado pelo Funcionário TERCOM</option>
		<option value='<?php echo OrderRequestDAO::SELECT_MODE_CANCELED; ?>'>Cancelado</option>
	</select></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		if (($idCustomerEmployee = $_GET['idCustomerEmployee']) === '')
			return GeradorDeDados::callWebService("orderRequest/getByCustomer", $_GET);
		else
			return GeradorDeDados::callWebService("orderRequest/getByCustomer/$idCustomerEmployee", $_GET);
	}
}
include '../execute.php';

