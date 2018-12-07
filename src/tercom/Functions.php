<?php

namespace tercom;

use DateTime;
use dProject\Primitive\AdvancedObject;
use dProject\Primitive\StringUtil;

class Functions
{
	/**
	 * @var string padronização de formato para CNPJ.
	 */
	public const PATTERN_CNPJ = '/[0-9]{2}\.?[0-9]{3}\.?[0-9]{3}\/?[0-9]{4}\-?[0-9]{2}/';
	/**
	 * @var string padronização de formato para CNPJ.
	 */
	public const PATTERN_CPF = '/[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}/';

	/**
	 * Exibe um alerta em javascript visível apenas como mensagem.
	 * @param string $message mensagem de alerta a ser exibido.
	 */

	public static function alert(string $message)
	{
		echo "<script language='JavaScript' type='text/javascript'>".PHP_EOL;
		echo "	alert('$message');".PHP_EOL;
		echo "</script>".PHP_EOL;
	}

	/**
	 * Adiciona um novo log para ser exibido dentro do console do JavaScript.
	 * @param string $message mensagem a ser exibida no console do JavaScript.
	 */

	public static function debug(string $message)
	{
		echo "<script language='JavaScript' type='text/javascript'>".PHP_EOL;
		echo "	console.log('$message');".PHP_EOL;
		echo "</script>".PHP_EOL;
	}

	/**
	 * Exibi uma divisão na página que será estilizada com uma caixa de mensagem do tipo sucesso.
	 * @param string $message mensagem do qual deve ser exibida dentro da caixa exibida.
	 */

	public static function showSuccessMessage(string $message)
	{
		echo "<div class='alert alert-success' role='alert'>$message</div>";
	}

	/**
	 * Exibi uma divisão na página que será estilizada com uma caixa de mensagem do tipo informativo.
	 * @param string $message mensagem do qual deve ser exibida dentro da caixa exibida.
	 */

	public static function showInfoMessage(string $message)
	{
		echo "<div class='alert alert-info' role='alert'>$message</div>";
	}

	/**
	 * Exibi uma divisão na página que será estilizada com uma caixa de mensagem do tipo alerta.
	 * @param string $message mensagem do qual deve ser exibida dentro da caixa exibida.
	 */

	public static function showWarningMessage(string $message)
	{
		echo "<div class='alert alert-warning' role='alert'>$message</div>";
	}

	/**
	 * Exibi uma divisão na página que será estilizada com uma caixa de mensagem do tipo perigo.
	 * @param string $message mensagem do qual deve ser exibida dentro da caixa exibida.
	 */

	public static function showDangerMessage(string $message)
	{
		echo "<div class='alert alert-danger' role='alert'>$message</div>";
	}

	/**
	 * @return string aquisição da mensagem para cumprimento conforme o horário do servidor.
	 */

	public static function getGoodMorning():string
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

	public static function newRandomMD5():string
	{
		return md5(uniqid(rand(), true));
	}

	/**
	 * @return \DateTime aquisição de um horário tempo definido o horário atual do sistema.
	 */

	public static function newDateTimeNow():DateTime
	{
		return new DateTime(time());
	}

	/**
	 * @param string $expression expressão para se definir o horário.
	 * @return \DateTime aquisição de um horário conforme a expressão especificada.
	 * @see http://php.net/manual/pt_BR/function.strtotime.php
	 */

	public static function newDateTimeExpression($expression):DateTime
	{
		return new DateTime(time(strtotime($expression)));
	}

	/**
	 * Itera todos os valores do objeto procurando resultados obtidos através de JOIN dos dados.
	 * Será considerado como JOIN qualquer valor que tenha chave com _ (underline).
	 * Antes do underline é o nome do atributo do objeto, após o underline é o nome do atributo no objeto.
	 * @param array $array vetor base que será iterado os seus valores e chaves em busca de JOIN.
	 */

	public static function parseArrayJoin(array &$array)
	{
		foreach ($array as $name => $value)
		{
			if (($index = strpos($name, '_')) === false)
				continue;

			$prefix = substr($name, 0, $index);
			$prefixName = substr($name, $index + 1);

			unset($array[$name]);
			$array[$prefix][$prefixName] = $value;
		}
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
	 * @return AdvancedObject aquisição de um novo objeto avançado.
	 */

	public static function newAdvancedObject(string $classname, array $baseArray, string $prefix):?AdvancedObject
	{
		$object = new $classname();

		if (!($object instanceof AdvancedObject))
			return null;

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

	public static function addLog(string $filepath, string $mensagem)
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

	public static function addJsonLog(string $filepath, array $array)
	{
		self::addLog($filepath, json_encode($array));
	}

	/**
	 * Procedimento para validar um número de CNPJ das empresas no sistema.
	 * @param string $cnpj cadastro nacional de pessoa jurídica.
	 * @return bool true se for válido ou false caso contrário.
	 * @link https://gist.github.com/guisehn/3276302
	 */
	public static function validateCNPJ(string &$cnpj):bool
	{
		if (!preg_match(self::PATTERN_CNPJ, $cnpj))
			return false;

		$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

		// Valida tamanho
		if (strlen($cnpj) != 14)
			return false;

		// Valida primeiro dígito verificador
		for ($i = 0, $j = 5, $sum = 0; $i < 12; $i++)
		{
			$sum += $cnpj{$i} * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$remainder = $sum % 11;

		if ($cnpj{12} != ($remainder < 2 ? 0 : 11 - $remainder))
			return false;

		// Valida segundo dígito verificador
		for ($i = 0, $j = 6, $sum = 0; $i < 13; $i++)
		{
			$sum += $cnpj{$i} * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$remainder = $sum % 11;

		return $cnpj{13} == ($remainder < 2 ? 0 : 11 - $remainder);
	}

	/**
	 * Procedimento para validar um número de CPF das pessoas no sistema.
	 * @param string $cpf cadastro de pessoa física.
	 * @return bool true se for válido ou false caso contrário.
	 * @link https://gist.github.com/guisehn/3276015
	 */
	public static function validateCPF(string &$cpf):bool
	{
		if (!preg_match(self::PATTERN_CPF, $cpf))
			return false;

		$cpf = preg_replace('/[^0-9]/', '', (string) $cpf);

		// Valida tamanho
		if (strlen($cpf) != 11)
			return false;

		// Calcula e confere primeiro dígito verificador
		for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--)
			$soma += $cpf{$i} * $j;

		$resto = $soma % 11;

		if ($cpf{9} != ($resto < 2 ? 0 : 11 - $resto))
			return false;

		// Calcula e confere segundo dígito verificador
		for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--)
			$soma += $cpf{$i} * $j;

		$resto = $soma % 11;

		return $cpf{10} == ($resto < 2 ? 0 : 11 - $resto);
	}

	/**
	 * Remove caracteres utilizados para formatação de números:
	 * pontos (.), virgulas (,), ífens (-) e til (~) e dois pontos (:).
	 * @param string $number string contendo o valor numérico.
	 * @return string aquisição do número com formatação removida.
	 */

	public static function parseOnlyNumbers(string $number):string
	{
		return preg_replace('~[.,-/ ]~', '', $number);
	}

	/**
	 * Remove todos os tipos de acentos de uma string sejam caracteres minúsculos ou maísculos.
	 * A remoção não consite em remover o caracter mas sim substituí-lo por um que não tenha acento.
	 * @param string $string string do qual deseja substituir os acentos por suas lentras convencionais.
	 * @return string aquisição da string com os acentos substituídos.
	 */

	public static function stripAccents(string $string):string
	{
		$replacements = [
			'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
			'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
			'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
			'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
			'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'];

		return strtr($string, $replacements);
	}

	/**
	 * Verifica se o conteúdo de uma daterminada string está em formato UTF8.
	 * @param string $string string do qual será verificado o formato.
	 * @return bool true se estiver em UTF8 ou false caso contrário.
	 */

	public static function isUTF8(string $string): bool
	{
		return preg_match('!!u', $string) === true;
	}

	/**
	 * @param array $entry
	 * @param string $prefix
	 * @return string[]
	 */

	public static function parseEntrySQL(array &$entry, string $prefix)
	{
		$filtrado = [];
		$prefix .= '_';

		foreach ($entry as $campo => $value)
			if (StringUtil::startsWith($campo, $prefix))
			{
				$filtrado[substr($campo, strlen($prefix))] = $value;
				unset($entry[$campo]);
			}

		return $filtrado;
	}
}

?>