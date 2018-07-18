<?php

namespace tercom\control;

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
}

?>