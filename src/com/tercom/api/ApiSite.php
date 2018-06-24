<?php

namespace tercom\api;

use dProject\Primitive\StringUtil;

/**
 * @author Andrew
 */

class ApiSite extends ApiInterface
{
	/**
	 * @param ApiConnection $apiConnection
	 * @param string $apiname
	 * @param array $vars
	 */

	public function __construct(ApiConnection $apiConnection, $apiname, array $vars)
	{
		parent::__contruct($apiConnection, $apiname, $vars);
	}

	/**
	 * {@inheritDoc}
	 * @see ApiInterface::execute()
	 */

	public function execute()
	{
		// Se não tem HTTP_REFERER está sendo acessado diretamente pelo link
		if (!isset($_SERVER['HTTP_REFERER']))
		{
			// Se estiver em dev podemos permitir
			if (SYS_DEVELOP !== true)
				throw new ApiException('acesso negado');
		}

		// Se tem HTTP_REFERER foi chamado de alguma página por AJAX por exemplo
		else
		{
			// No caso dessa API só será permitido o acesso do nosso site
			if (!StringUtil::startsWith($_SERVER['HTTP_REFERER'], DOMAIN) && !StringUtil::startsWith($_SERVER['HTTP_REFERER'], WWW_DOMAIN))
				throw new ApiException('acesso negado');
		}

		return $this->defaultExecute();
	}
}

?>