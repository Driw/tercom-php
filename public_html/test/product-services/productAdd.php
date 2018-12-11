<?php

use tercom\GeradorDeDados;
use tercom\entities\ProductCategory;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['name']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Nome: <input type='text' name='name' required></p>
	<p>Descrição: <input type='text' name='description' required></p>
	<p>Utilidade: <input type='text' name='utility'></p>
	<p>Unidade de Produto ID: <input type='text' name='idProductUnit' required></p>
	<p>Categoria de Produto ID: <input type='text' name='idProductCategory'></p>
	<p>Tipo da Categoria de Produto: <select name='idProductCategoryType'>
		<option value="<?php echo ProductCategory::CATEGORY_FAMILY; ?>">Família</option>
		<option value="<?php echo ProductCategory::CATEGORY_GROUP; ?>">Grupo</option>
		<option value="<?php echo ProductCategory::CATEGORY_SUBGROUP; ?>">Subgroupo</option>
		<option value="<?php echo ProductCategory::CATEGORY_SECTOR; ?>">Setor</option>
	</select></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService('product/add', $_GET, true);
	}
}
include_once '../execute.php';

