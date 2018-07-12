<?php

use dProject\Primitive\KillPage;
use dProject\Primitive\UrlFriendly;
use tercom\api\ApiConnection;
use tercom\Core\System;
use tercom\api\ApiResponse;

function IncludeThirdParty($thirdParty)
{
	include sprintf('%s/src/3rdparty/%s/include.php', $_SERVER['DOCUMENT_ROOT'], $thirdParty);
}

function PHPFatalError()
{
	if (($error = error_get_last()) != null)
	{
		$level = ($isError = PHPIsError($error['type'])) ? KILL_PAGE_FATAL_ERROR : KILL_PAGE_ERROR;
		$modalBody =
		sprintf('<b>Erro:</b> %s', $error['message']).
		sprintf('</p><hr><p>').
		sprintf('<b>Origem:</b> %s(%d)', $error['file'], $error['line']);

		KillPage::showModalDialog($modalBody, PHPGetErroName($error['type']). ' (' .PHPGetErroPrefix($error['type']). ')', $level);
	}
}

function APIShutdown()
{
	if (($error = error_get_last()) != null)
		ApiConnection::jsonFatalError(ApiResponse::API_PHP_FATAL_ERROR, $error['message'], $error['type'], $error['line'], $error['file']);
	exit;
}

function APIErrorHandler(int $code, string $message, string $file, int $line)
{
	ApiConnection::jsonFatalError(ApiResponse::API_PHP_FATAL_ERROR, $message,  $code, $line, $file);
	exit;
}

function PHPIsError($type)
{
	switch($type)
	{
		case E_ERROR:
		case E_CORE_ERROR:
		case E_RECOVERABLE_ERROR:
		case E_USER_ERROR:
		case E_COMPILE_ERROR:
		case E_COMPILE_WARNING:
			return true;
	}

	return false;
}

function PHPGetErroName($type)
{
	switch($type)
	{
		case E_ERROR:
		case E_CORE_ERROR:
		case E_RECOVERABLE_ERROR:
		case E_USER_ERROR:
			return 'Erro';

		case E_WARNING:
		case E_CORE_WARNING:
			return 'Atenção';

		case E_PARSE:
		case E_NOTICE:
		case E_USER_WARNING:
		case E_USER_NOTICE:
		case E_STRICT:
		case E_DEPRECATED:
		case E_USER_DEPRECATED:
			return 'Notificação';

		case E_COMPILE_ERROR:
		case E_COMPILE_WARNING:
			return 'Erro Fatal';
	}

	return '';
}

function PHPGetErroPrefix($type)
{
	switch($type)
	{
		case E_ERROR: return 'E_ERROR';
		case E_WARNING: return 'E_WARNING';
		case E_PARSE: return 'E_PARSE';
		case E_NOTICE: return 'E_NOTICE';
		case E_CORE_ERROR: return 'E_CORE_ERROR';
		case E_CORE_WARNING: return 'E_CORE_WARNING';
		case E_COMPILE_ERROR: return 'E_COMPILE_ERROR';
		case E_COMPILE_WARNING: return 'E_COMPILE_WARNING';
		case E_USER_ERROR: return 'E_USER_ERROR';
		case E_USER_WARNING: return 'E_USER_WARNING';
		case E_USER_NOTICE: return 'E_USER_NOTICE';
		case E_STRICT: return 'E_STRICT';
		case E_RECOVERABLE_ERROR: return 'E_RECOVERABLE_ERROR';
		case E_DEPRECATED: return 'E_DEPRECATED';
		case E_USER_DEPRECATED: return 'E_USER_DEPRECATED';
	}

	return '';
}

function KillPageDialog($modalBody, $title, $level)
{
?>
	<!-- Inicio do Modal Kill Page -->
	<div id="modalKillPage" class="modal fade">
		<div class="modal-dialog modal-lg modal-dark">
			<div class="modal-content">
				<div class="modal-header text-center">
					<h4 class="modal-title"><?php echo $title; ?></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p><?php echo $modalBody; ?></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<script type="text/javascript">
		$(document).ready(function() { $('#modalKillPage').modal('show'); });
	</script>
	<!-- Fim do Modal Kill Page -->
<?php
	if ($level == KILL_PAGE_FATAL_ERROR)
	{
		System::shutdown();
		exit;
	}
}

function FriendlyPageNavigation()
{
	echo "
			<!-- Inicio da Navegação de Páginas -->
			<div class='breadcrumb-pages'>
				<div class='btn-group btn-breadcrumb'>
					<a href='/' class='btn btn-secondary'><i class='oi oi-home' alt='Inicio' title='Inicio'></i></a>";

	for ($i = 0; $i < UrlFriendly::getPageNameCount() - 1; $i++)
	{
		$link = UrlFriendly::getBaseFront($i + 1);
		$pageName = UrlFriendly::getPageName($i);

		if ($pageName == null)
			continue;

		if (!is_array($pageName))
			echo "
					<a href='$link' class='btn btn-secondary'>$pageName</a>";
		else
		{
			$linkName = $pageName[0];
			$btnClass = !isset($pageName[1]['class']) ? 'default' : $pageName[1]['class'];

			echo "
					<a href='$link' class='btn btn-$btnClass'>$linkName</a>";
		}
	}

	echo "
				</div>
			</div>
			<!-- Fim da Navegação de Páginas -->".PHP_EOL;
}

/**
 * @return string aquisição do horário atual da máquina/servidor em milissegundos.
 */

function now():string
{
	$microtime = microtime();
	$microtimeData = explode(' ', $microtime);
	$seconds = intval($microtimeData[1]);
	$milliseconds = intval(floatval($microtimeData[0]) * 1000);

	return sprintf('%d%03d', $seconds, $milliseconds);
}

/**
 * Formata um determinado período em milissegundos em uma forma mais legível.
 * @param int $milleseconds quantidade de milissegundos do qual será formatado.
 * @return string aquisição da string contendo o tempo formatado.
 */

function strms(int $milleseconds):string
{
	if ($milleseconds >= 1000)
		return sprintf('%ss e %03dms', intval($milleseconds / 1000), $milleseconds % 1000);

	return sprintf('%03dms', $milleseconds);
}

?>