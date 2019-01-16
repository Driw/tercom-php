<?php

namespace tercom\boundary\dashboard\template;

use Exception;
use dProject\restful\ApiConnection;
use dProject\restful\exception\ApiBadRequestException;
use dProject\restful\exception\ApiMethodNotAllowedException;
use dProject\restful\exception\ApiNotAcceptable;
use dProject\restful\exception\ApiNotFoundException;
use dProject\restful\exception\ApiUnauthorizedException;
use dProject\restful\template\ApiTemplateResult;
use tercom\boundary\dashboard\DashboardConfigs;
use tercom\boundary\dashboard\DefaultDashboardBoundary;

class ErrorTemplate extends DefaultDashboardBoundary
{
	/**
	 * @var DashboardConfigs
	 */
	private $configs;
	/**
	 * @var string
	 */
	private $exceptionMessage;
	/**
	 * @var string
	 */
	private $exceptionTrace;

	/**
	 * @param string $script [optional]
	 */
	public function __construct(ApiConnection $connection, $script = 'Error')
	{
		parent::__construct($connection, 'Error');
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\boundary\dashboard\DefaultDashboardBoundary::isVerifyLogin()
	 */
	public function isVerifyLogin(): bool
	{
		return false;
	}

	/**
	 * @param Exception $e
	 */
	public function setException(Exception $e)
	{
		if ($e instanceof ApiBadRequestException)
		{
			$this->exceptionMessage = '400 # Bad Request';
			$this->exceptionTrace = 'Requisição inválida';
		}

		else if ($e instanceof ApiUnauthorizedException)
		{
			$this->exceptionMessage = '401 # Unauthorized';
			$this->exceptionTrace = 'Necessário efetuar o acesso ou acesso não autorizado';
		}

		else if ($e instanceof ApiNotFoundException)
		{
			$this->exceptionMessage = '404 # Not Found';
			$this->exceptionTrace = 'Serviço informado não foi encontrado';
		}

		else if ($e instanceof ApiMethodNotAllowedException)
		{
			$this->exceptionMessage = '405 # Method Not Allowed';
			$this->exceptionTrace = 'Ação informada não foi encontrada';
		}

		else if ($e instanceof ApiNotAcceptable)
		{
			$this->exceptionMessage = '406 # Not Acceptable';
			$this->exceptionTrace = 'Serviço informado ainda não implementado';
		}

		else
			$this->exceptionMessage = nameOf($e);

		if (DEV)
			$this->exceptionTrace .= '<br>' .str_replace(PHP_EOL, '<br>'.PHP_EOL, jTraceEx($e));
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\template\ApiTemplate::callIndex()
	 */
	public function callIndex()
	{
		$dashboardTemplate = $this->hasLogin() ? $this->newBaseTemplate() : $this->newErrorBaseTemplate();
		$dashboardTemplate->addFile('IncludeDashboard', 'Error');
		$dashboardTemplate->ExceptionName = $this->exceptionMessage;
		$dashboardTemplate->TraceString = $this->exceptionTrace;

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}
}

