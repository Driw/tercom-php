<?php

namespace tercom;

/**
 * <p><h1>Página</h1></p>
 *
 * <p>Essa classe irá conter alguns procedimentos que serão usados na página inicial (index).
 * Tem como finalidade separar cada verificação e inicialização do sistema adequadamente.
 * Além disso, permitir uma documentação, separação e melhor visualização do código.</p>
 *
 * @author Andrew Mello
 */

class Page
{
	/**
	 * Deve verificar se a versão do PHP Utilizada está de acordo com a versão mínima permitida.
	 * A versão mínima aceita pelo sistema poderá ser configurada na constante PHP_VERSION_MIN.
	 * Caso a versão mínima não seja atendida, a página será morta logo em seguida.
	 * @throws \Exception ocorre se a versão mínima do PHP não for suficiente.
	 */

	public static function verifyPHPVersion()
	{
		if (version_compare(PHP_VERSION, PHP_VERSION_MIN, "<"))
			throw new \Exception('É necessário PHP <b>' .PHP_VERSION_MIN. '</b> ou mais recente, você está usando <b>' .PHP_VERSION. '</b>.');
	}

	/**
	 * Verifica se o sistema deverá exibir todo e qualquer erro que o PHP encontrar na página.
	 * Essa propriedade só será habilitada na página se estiver sendo rodado no servidor.
	 * Para isso o endereço de IP da máquina usada deve ser local: <b>127.0.0.1</b> ou <b>::1</b>.
	 */

	public static function verifyPHPErrorReport()
	{
		error_reporting(0);
	}

	/**
	 * Chamado para fazer a inclusão da parte dos dados de cabeçalho da página.
	 */

	public static function includeHeader()
	{
		include_once DIR_PAGES. '/Header.php';
	}

	/**
	 * Chamado para fazer a inclusão da barra de navegação superior da página junto ao menu principal.
	 */

	public static function includeNavTopBar()
	{
		include_once DIR_PAGES. '/NavTopBar.php';
	}

	/**
	 * Chamado para fazer a inclusão do banner da página junto ao menu principal.
	 */

	public static function includeBanner()
	{
		include_once DIR_PAGES. '/Banner.php';
	}

	/**
	 * Chamado para fazer a inclusão da parte do recipiente com conteúdo da página.
	 */

	public static function includeContainer()
	{
		include_once DIR_PAGES. '/Container.php';
	}

	/**
	 * Chamado para fazer a inclusão da parte do rodapé da página.
	 */

	public static function includeFooter()
	{
		include_once DIR_PAGES. '/Footer.php';
	}

	public static function openHtml()
	{
		echo "<!DOCTYPE html>".PHP_EOL;
		echo "<html>".PHP_EOL;
	}

	public static function closeHtml()
	{
		echo "</html>".PHP_EOL;
	}

	public static function openBody()
	{
		echo "<body>".PHP_EOL;
	}

	public static function closeBody()
	{
		echo "</body>".PHP_EOL;
	}

	public static function openContainer()
	{
		echo PHP_EOL;
		echo "	<!-- Inicio do Container Fluído -->".PHP_EOL;
		echo "	<div class='container-fluid'>".PHP_EOL;
	}

	public static function closeContainer()
	{
		echo "	</div>".PHP_EOL;
		echo "	<!-- Fim do Container Fluído -->".PHP_EOL;
	}
}

?>
