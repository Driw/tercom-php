<?php

namespace tercom\entities;

use tercom\TercomException;

/**
 * Exceção de Análise de Entidade
 *
 * Exceção usada para restringir que entidades possam receber qualquer valor em seus atributos.
 * Quando uma validação do valor de atributo da entidade for analisada e invalidada uma exceção é gerada.
 *
 * @author Andrew
 *
 */
class EntityParseException extends TercomException
{
	/**
	 * Método para criar uma exceção com mensagem formatada rápida e sem código de erro.
	 * @param string $format mensagem formatada da especificação do erro encontrado.
	 * @return EntityParseException exceção criada conforme mensagem formatada.
	 */
	public static function new(string $format): EntityParseException
	{
		$args = func_get_args();
		array_shift($args); // Remover format

		return new EntityParseException(vsprintf($format, $args));
	}
}

