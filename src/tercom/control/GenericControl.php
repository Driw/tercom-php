<?php

namespace tercom\control;

/**
 * @author Andrew
 */
class GenericControl
{
	/**
	 * @var int resultado de uma query de replace sem resultado.
	 */
	const REPLACE_NONE = 0;
	/**
	 * @var int resultado de uma query de replace que adicionou um registro.
	 */
	const REPLACE_INSERTED = 1;
	/**
	 * @var int resultado de uma query de replace que substituiu um registro.
	 */
	const REPLACE_UPDATED = 2;


	/**
	 * @var int entrada duplicada.
	 */
	const ER_DUP_ENTRY = 1062;
	/**
	 * @var int não pode atualizar/inserir uma coluna por falha com chave estrangeira
	 */
	const ER_NO_REFERENCED_ROW_2 = 1452;
}

?>