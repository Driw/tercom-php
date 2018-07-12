<?php

namespace tercom\Core;

use dProject\MySQL\MySQL;
use dProject\Primitive\Config;
use dProject\Primitive\Cookie;
use dProject\Primitive\FriendlyPage;
use dProject\Primitive\Session;
use dProject\Primitive\UrlFriendly;
use tercom\bootstrap\navbar\Navbar;
use tercom\Functions;

/**
 * <p><h1>Sistema</h1></p>
 *
 * <p>Classe contendo procedimentos estáticos que devem facilitar a inicialização do mesmo.
 * Possui métodos usados pelo index.php que deve garantir funcionamento para outras partes.
 * Carregando algumas propriedades, verificar ações de login, iniciar sessão e outros.</p>
 *
 * @author Andrew Mello
 */

class System
{
	/**
	 * Cconfigurações do sistema, respectivo ao geral.
	 * @var Config
	 */
	private static $config;
	/**
	 * Conexão principal com o banco de dados MySQL.
	 * @var MySQL
	 */
	private static $webConnection;
	/**
	 * Barra de Navegação.
	 * @var Navbar
	 */
	private static $navbar;

	/**
	 * Tem como finalidade garantir algumas funcionalidades do sistema do site.
	 * Primeiramente inicializar algumas propriedades para que possa ser rodado.
	 * Como por exemplo o carregamento das configurações padrões e conexão MySQL.
	 */

	public static function init()
	{
		self::$config = Config::parse(DIR_DATA. 'conf/system.php');

		setlocale(LC_ALL, self::$config->getLocale());
		date_default_timezone_set(self::$config->getTimeZone());
	}

	/**
	 * Adiciona um novo registro de acesso ao sistema com informações básicas da solicitação.
	 * Será salvo o horário, endereço de IP, nome do arquivo acessado, origem/referência e
	 * os dados informados nos métodos GET e POST. Tudo isso salvo em formato JSON.
	 */

	public static function addAccessLog()
	{
		$filepath = 'logs/requests/' .date(DATE_SQL_FORMAT). '.log';
		$logmessage = [
			'time' => date(DATE_TIME_SQL_FORMAT),
			'ipAddress' => $_SERVER['REMOTE_ADDR'],
			'phpSelf' => $_SERVER['PHP_SELF'],
			'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
			'get' => $_GET,
			'post' => $_POST,
		];
		Functions::addJsonLog($filepath, $logmessage);
	}

	/**
	 * Tem como finalidade detectar se há alguma tentativa de efetuar ações de login.
	 * Essas ações podem ser de tentativa de acessar uma conta, sair de uma conta acessada,
	 * verificar se a conta está banida e bloquear o acesso do mesmo ou outra ação.
	 */

	public static function verifyLogin()
	{
		// TODO
	}

	/**
	 * Tem como finalidade iniciar uma nova sessão no navegador se assim for possível.
	 * No caso, uma nova sessão será necessária apenas se não houver uma.
	 * Caso haja uma sessão no momento ele irá ignorar a criação de uma nova.
	 */

	public static function verifySession()
	{
		Session::getInstance()->start();
	}

	/**
	 * Tem como finalidade verificar qual é o modelo preferêncial do sistema e/ou usuário.
	 * A partir deste, determinar qual deverá ser o modelo de fato a ser ustilizado.
	 */

	public static function verifyTemplate()
	{
		$path = 'pages/';

		if (Cookie::getInstance()->isSetted('template'))
			$path = Cookie::getInstance()->getString('template');

		if (!is_dir($path))
			$path = '/';

		FriendlyPage::getInstance()->setPagePath($path);
		define('DIR_PAGES', $path);
	}

	/**
	 * As configurações do sistema possuem propriedades que gerais, que são usadas por todo o site.
	 * Não são especificas de uma parte ou serviço prestado/oferecido no mesmo.
	 * @return Config objeto contendo as configurações do sistema pré-definidas por padrão.
	 */

	public static function getConfig()
	{
		return self::$config;
	}

	/**
	 * A conexão principal do sistema deve ser responsável por diversos dados.
	 * Dentre eles os principais se encontram os usuários e o que podem usar.
	 * @return MySQL aquisição da conexão principal do sistema.
	 */

	public static function getWebConnection()
	{
		if (self::$webConnection == null)
		{
			self::$config = Config::parse(DIR_DATA. 'conf/system.php');

			$mysqlConfigs = self::$config->getMySQL();
			$mysqlSystemConfigs = $mysqlConfigs->getSystem();

			self::$webConnection = new MySQL('Sistema Principal');
			self::$webConnection->setHost($mysqlSystemConfigs->getHost());
			self::$webConnection->setUsername($mysqlSystemConfigs->getUsername());
			self::$webConnection->setPassword($mysqlSystemConfigs->getPassword());
			self::$webConnection->setDatabase($mysqlSystemConfigs->getDatabase());
			self::$webConnection->connect();
			self::$webConnection->setCharset($mysqlSystemConfigs->getCharset());
		}

		return self::$webConnection;
	}

	/**
	 * Quando há algum erro que possa causar o fechamento do sistema devemos encerrar o sistema de forma correta.
	 */

	public static function shutdown()
	{
		if (self::$webConnection != null)
		{
			self::$webConnection->close();
			self::$webConnection = null;
		}
	}

	/**
	 * Se não houver a barra de navegação criada irá criar uma nova barra de nevagação com os itens pré-definidos.
	 * @return Navbar aquisição da barra de navegação principal do site.
	 */

	public static function getNavbar()
	{
		if (self::$navbar == null)
		{
			$navbar = new Navbar('TERCOM');

			// Com um item ou sem nenhum, sempre mostramos a página inicial
			if (UrlFriendly::getBaseLevel() >= 2)
			{
				$navbar->getNavbarBrand()->setLink(UrlFriendly::getBaseBack(1));
				$navbar->getNavbarBrand()->setName(UrlFriendly::getPageName(1));
				echo $navbar->getNavbarBrand();
			}

			else
				$navbar->getNavbarBrand()->setLink('/');

			self::$navbar = $navbar;
		}

		return self::$navbar;
	}
}

?>