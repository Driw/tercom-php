<?php

namespace tercom\core;

use dProject\annotation\AnnotationParser;
use dProject\MySQL\MySQL;
use dProject\Primitive\Config;
use dProject\Primitive\Session;
use dProject\Primitive\GlobalFunctions;
use dProject\restful\ApiConnection;
use dProject\restful\ApiSettings;
use tercom\Functions;
use tercom\api\ApiListener;
use tercom\api\ApiPermissionAnnotation;
use tercom\boundary\dashboard\BoundaryListener;
use tercom\boundary\dashboard\DashboardTemplate;
use tercom\TercomException;

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
	 * @var bool objetos informados na API terão formato simples ou avançado.
	 */
	private static $apiOnlyProperties;
	/**
	 * @var ApiConnection conexão da API dos Web Services.
	 */
	private static $apiConnection;
	/**
	 * @var ApiConnection conexão da API do Dashboard.
	 */
	private static $dashboardConnection;

	/**
	 * Tem como finalidade garantir algumas funcionalidades do sistema do site.
	 * Primeiramente inicializar algumas propriedades para que possa ser rodado.
	 * Como por exemplo o carregamento das configurações padrões e conexão MySQL.
	 */

	public static function init()
	{
		GlobalFunctions::init();

		self::initConfigs();
		self::setApiOnlyProperties(false);

		setlocale(LC_ALL, self::$config->getLocale());
		setlocale(LC_MONETARY, 'en_US');
		setlocale(LC_NUMERIC, 'en_US');
		date_default_timezone_set(self::$config->getTimeZone());
	}

	/**
	 * Inicializa o sistema executando a API (Webservices TERCOM).
	 */

	public static function initApi()
	{
		self::init();
		AnnotationParser::register(new ApiPermissionAnnotation);

		$listener = new ApiListener();

		$settings = new ApiSettings();
		$settings->setParametersOffset(1);
		$settings->setEnableDebug(DEV);
		$settings->setEnableTimeUp(true);
		$settings->setEnableResultClass(true);
		$settings->setEnableContentLength(true);
		$settings->setApiNameSpace(namespaceOf($listener));
		$settings->setResponseType(ApiSettings::RESPONSE_JSON);

		$apiConnection = (self::$apiConnection = ApiConnection::getInstance());
		$apiConnection->setSettings($settings);
		$apiConnection->setListener($listener);
		$apiConnection->start();
	}

	/**
	 * Inicializa o sistema executando o Dashboard (Painel de Controle TERCOM).
	 */

	public static function initDashboard()
	{
		self::init();

		DashboardTemplate::setDirectory(sprintf('%s/dashboard/boundaries', $_SERVER['DOCUMENT_ROOT']));

		$listener = new BoundaryListener();

		$settings = new ApiSettings();
		$settings->setParametersOffset(0);
		$settings->setEnableDebug(DEV);
		$settings->setEnableTimeUp(true);
		$settings->setEnableResultClass(true);
		$settings->setEnableContentLength(true);
		$settings->setApiNameSpace(namespaceOf($listener));
		$settings->setResponseType(ApiSettings::RESPONSE_TEMPLATE);

		$apiConnection = (self::$dashboardConnection = ApiConnection::getInstance());
		$apiConnection->setSettings($settings);
		$apiConnection->setListener($listener);
		$apiConnection->start();
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
	 * Inicializa a definição das configurações do sistema carregado o arquivo de configurações.
	 */

	private static function initConfigs()
	{
		self::$config = Config::parse(DIR_DATA. format('conf/%s', DEV ? 'system.php' : 'system_prod.php'));
	}

	/**
	 * As configurações do sistema possuem propriedades que gerais, que são usadas por todo o site.
	 * Não são especificas de uma parte ou serviço prestado/oferecido no mesmo.
	 * @return Config objeto contendo as configurações do sistema pré-definidas por padrão.
	 */

	public static function getConfig()
	{
		if (self::$config === null)
			self::initConfigs();

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
			$mysqlConfigs = self::getConfig()->getMySQL();
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
	 * @return bool
	 */

	public static function isApiOnlyProperties(): bool
	{
		return self::$apiOnlyProperties;
	}

	/**
	 * @param boolean $apiOnlyProperties
	 */

	public static function setApiOnlyProperties(bool $apiOnlyProperties)
	{
		self::$apiOnlyProperties = $apiOnlyProperties;
	}

	/**
	 * @throws TercomException quando a conexão de API não tiver sido definida.
	 * @return ApiConnection aquisição da conexão de API do sistema.
	 */
	public static function getApiConnection(): ApiConnection
	{
		if (self::$apiConnection === null)
			throw TercomException::newApiConnection();

		return self::$apiConnection;
	}

	/**
	 * @throws TercomException quando a conexão de API não tiver sido definida.
	 * @return ApiConnection aquisição da conexão de API do sistema.
	 */
	public static function getDashboardConnection(): ApiConnection
	{
		if (self::$dashboardConnection === null)
			throw TercomException::newDashboardConnection();

		return self::$dashboardConnection;
	}
}

