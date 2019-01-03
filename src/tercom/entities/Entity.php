<?php

namespace tercom\entities;

/**
 * Interface para especificação de entidades
 *
 * Cada entidade no sistema possui um código de identificação único de forma que possa ser identificado.
 * Através dessa interface outros objetos podem trabalhar de forma únic com as entidades através do seu código.
 *
 * @author andrews
 */
interface Entity
{
	/**
	 * @return int aquisição do código de identificação único da entidade.
	 */
	public function getId(): int;
}

