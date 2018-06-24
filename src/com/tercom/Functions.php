<?php

namespace tercom;

use dProject\Primitive\StringUtil;
use dProject\Primitive\AdvancedObject;

class Functions
{
	/**
	 * Exibe um alerta em javascript visível apenas como mensagem.
	 * @param string $message mensagem de alerta a ser exibido.
	 */

	public static function alert($string)
	{
		echo "<script language='JavaScript' type='text/javascript'>".PHP_EOL;
		echo "	alert('$message');".PHP_EOL;
		echo "</script>".PHP_EOL;
	}

	/**
	 * Adiciona um novo log para ser exibido dentro do console do JavaScript.
	 * @param string $message mensagem a ser exibida no console do JavaScript.
	 */

	public static function debug($message)
	{
		echo "<script language='JavaScript' type='text/javascript'>".PHP_EOL;
		echo "	console.log('$message');".PHP_EOL;
		echo "</script>".PHP_EOL;
	}

	/**
	 * Exibi uma divisão na página que será estilizada com uma caixa de mensagem do tipo sucesso.
	 * @param string $message mensagem do qual deve ser exibida dentro da caixa exibida.
	 */

	public static function showSuccessMessage($message)
	{
		echo "<div class='alert alert-success' role='alert'>$message</div>";
	}
	
	/**
	 * Exibi uma divisão na página que será estilizada com uma caixa de mensagem do tipo informativo.
	 * @param string $message mensagem do qual deve ser exibida dentro da caixa exibida.
	 */

	public static function showInfoMessage($message)
	{
		echo "<div class='alert alert-info' role='alert'>$message</div>";
	}
	
	/**
	 * Exibi uma divisão na página que será estilizada com uma caixa de mensagem do tipo alerta.
	 * @param string $message mensagem do qual deve ser exibida dentro da caixa exibida.
	 */

	public static function showWarningMessage($message)
	{
		echo "<div class='alert alert-warning' role='alert'>$message</div>";
	}
	
	/**
	 * Exibi uma divisão na página que será estilizada com uma caixa de mensagem do tipo perigo.
	 * @param string $message mensagem do qual deve ser exibida dentro da caixa exibida.
	 */

	public static function showDangerMessage($message)
	{
		echo "<div class='alert alert-danger' role='alert'>$message</div>";
	}

	/**
	 * @return string aquisição da mensagem para cumprimento conforme o horário do servidor.
	 */

	public static function getGoodMorning()
	{
		$hora = date('H');

		if ($hora >= 6 && $hora < 12)
			return 'Bom Dia';

		if ($hora >= 12 && $hora < 18)
			return 'Boa Tarde';

		return 'Boa Noite';
	}

	/**
	 * @return string aquisição de uma string MD5 com valor aleatório.
	 */

	public static function newRandomMD5()
	{
		return md5(uniqid(rand(), true));
	}

	/**
	 * @return \DateTime aquisição de um horário tempo definido o horário atual do sistema.
	 */

	public static function newDateTimeNow()
	{
		return new \DateTime(time());
	}

	/**
	 * @param string $expression expressão para se definir o horário.
	 * @return \DateTime aquisição de um horário conforme a expressão especificada.
	 * @see http://php.net/manual/pt_BR/function.strtotime.php
	 */

	public static function newDateTimeExpression($expression)
	{
		return new \DateTime(time(strtotime($expression)));
	}

	/**
	 * Itera todos os valores de um vetor procurando por valores que tenham chave com o prefixo definido.
	 * As chaves que tiverem o prefixo serão copiadas para um novo vetor e não terá o prefixo nela.
	 * <b>Exemplo</b>: ['a_a' => 1, 'a_b' => 2, 'b_c' => 3] se usado o prefixo 'a' será retornado:
	 * um array apenas com os seguintes valores e com chave sem prefixo: <i>['a' => 1, 'b' => 2]</i>
	 * @param array $baseArray vetor base que será iterado os seus valores e chaves em busca do prefixo.
	 * @param string $prefix prefixo do qual as chaves devem possuir para serem copiados ao novo vetor.
	 * @param boolean $emptyAsNull retornar null se não encontrar valores (padrão: false).
	 * @return NULL|array[] vetor com os valores de prefixo ou null se não encontrar nada (opcional).
	 */

	public static function parseArrayJoin(array $baseArray, $prefix, $emptyAsNull = false)
	{
		$array = [];
		$prefix = sprintf('%s_', $prefix);

		foreach ($baseArray as $name => $value)
			if (StringUtil::startsWith($name, $prefix))
				$array[substr($name, strlen($prefix))] = $value;

			return count($array) == 0 && $emptyAsNull ? null : $array;
	}

	/**
	 * Atribui os valores de um vetor de dados simples para os dados de um objeto avançado.
	 * Internamente será criado um novo vetor com a identificação da classe e suas propriedades,
	 * em seguida esse novo vetor será aplicado ao objeto avançado atualizando seus atributos.
	 * @param AdvancedObject $object referência do objeto avançado a ter seus atribudos definidos.
	 * @param array $objectArray vetor contendo os dados do qual serão atribuidos ao objeto avançado.
	 */

	public static function parseArrayObject(AdvancedObject &$object, array $objectArray)
	{
		$advancedObjectArray = [
			'class' => get_class($object),
			'properties' => $objectArray,
		];
		$object->fromArray($advancedObjectArray);
	}

	/**
	 * Permite criar um objeto avançado especificado pelo nome da classe nos parâmetros.
	 * Entretanto o vetor aqui será considerado que as variáveis possuam um préfixo.
	 * Todos os valores com o préfixo definido serão atribuidos objeto instanciado.
	 * @param string $classname nome da classe do qual será instanciada (com namespace).
	 * @param array $baseArray vetor com os dados que serão aplicados ao novo objeto.
	 * @param string $prefix préfixo contido no vetor dos dados que serão aplicados.
	 * @return object aquisição de um novo objeto avançado.
	 */

	public static function newAdvancedObject($classname, array $baseArray, $prefix)
	{
		$object = new $classname();
		$objectArray = self::parseArrayJoin($baseArray, $prefix);
		$objectArray = self::parseArrayObject($object, $objectArray);

		return $object;
	}

	/**
	 * Adiciona um novo registro do sistema em um arquivo conforme os parâmetros:
	 * @param string $filepath caminho do qual se encontra o arquivo de registros,
	 * se o arquivo não existir será criado um novo arquivo em branco.
	 * @param string $mensagem mensagem do qual será registrada ao arquivo.
	 */

	public static function addLog($filepath, $mensagem)
	{
		$folder = substr($filepath, 0, strrpos($filepath, '/'));

		if (!file_exists($folder))
			mkdir($folder);

		if ($handle = fopen($filepath, 'a'))
		{
			if (flock($handle, LOCK_EX))
			{
				fwrite($handle, "$mensagem".PHP_EOL);
				flock($handle, LOCK_UN);
				fclose($handle);
			}
		}
	}

	/**
	 * Adiciona um novo registro do sistema em um arquivo conforme os parâmetros:
	 * @param string $filepath caminho do qual se encontra o arquivo de registros,
	 * se o arquivo não existir será criado um novo arquivo em branco.
	 * @param array $array vetor contendo os dados do registro para salvar em JSON.
	 */

	public static function addJsonLog($filepath, array $array)
	{
		self::addLog($filepath, json_encode($array));
	}
}

?>