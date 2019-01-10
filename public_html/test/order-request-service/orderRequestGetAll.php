<?php

use tercom\GeradorDeDados;
use tercom\dao\OrderRequestDAO;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['mode']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Modo: <select name='mode' required>
		<option value='<?php echo OrderRequestDAO::SELECT_MODE_ALL; ?>'>Todos</option>
		<option value='<?php echo OrderRequestDAO::SELECT_MODE_CUSTOMER_CANCEL; ?>'>Cancelado pelo Funcionário de Cliente</option>
		<option value='<?php echo OrderRequestDAO::SELECT_MODE_TERCOM_CANCEL; ?>'>Cancelado pelo Funcionário TERCOM</option>
		<option value='<?php echo OrderRequestDAO::SELECT_MODE_CANCELED; ?>'>Cancelado</option>
		<option value='<?php echo OrderRequestDAO::SELECT_MODE_QUOTING; ?>'>Em Cotação</option>
		<option value='<?php echo OrderRequestDAO::SELECT_MODE_QUOTED; ?>'>Cotados</option>
	</select></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService("orderRequest/getAll", $_GET);
	}
}
include '../execute.php';

