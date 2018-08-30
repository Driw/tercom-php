<?php

namespace tercom\boundary\dashboard\template;

use Exception;
use tercom\boundary\dashboard\DashboardTemplate;
use tercom\boundary\dashboard\DashboardConfigs;
use dProject\restful\exception\ApiNotFoundException;
use dProject\restful\exception\ApiBadRequestException;
use dProject\restful\exception\ApiMethodNotAllowedException;
use dProject\restful\exception\ApiUnauthorizedException;
use dProject\restful\exception\ApiNotAcceptable;

class ErrorTemplate extends DashboardTemplate
{
	/**
	 * @var DashboardConfigs
	 */
	private $configs;

	/**
	 * @param string $script [optional]
	 */

	public function __construct($script = 'Error')
	{
		parent::__construct('Error');
	}

	/**
	 *
	 */

	public function init()
	{
		$this->configs = new DashboardConfigs();
		$this->configs->getHead()->set('BaseURL', sprintf('%s://%s/dashboard/', DEV ? 'http' : 'https', $_SERVER['HTTP_HOST']), true, true);
		$this->setDataConfig('Head', $this->configs->getHead());
	}

	/**
	 * @param Exception $e
	 */

	public function setException(Exception $e)
	{
		if ($e instanceof ApiBadRequestException)
		{
			$this->ExceptionName = '400 # Bad Request';
			$this->TraceString = 'Requisição inválida';
		}

		else if ($e instanceof ApiUnauthorizedException)
		{
			$this->ExceptionName = '401 # Unauthorized';
			$this->TraceString = 'Necessário efetuar o acesso ou acesso não autorizado';
		}

		else if ($e instanceof ApiNotFoundException)
		{
			$this->ExceptionName = '404 # Not Found';
			$this->TraceString = 'Serviço informado não foi encontrado';
		}

		else if ($e instanceof ApiMethodNotAllowedException)
		{
			$this->ExceptionName = '405 # Method Not Allowed';
			$this->TraceString = 'Ação informada não foi encontrada';
		}

		else if ($e instanceof ApiNotAcceptable)
		{
			$this->ExceptionName = '406 # Not Acceptable';
			$this->TraceString = 'Serviço informado ainda não implementado';
		}

		else
		{
			$this->ExceptionName = nameOf($e);
			$this->TraceString = str_replace(PHP_EOL, '<br>'.PHP_EOL, jTraceEx($e));
		}
	}
}

