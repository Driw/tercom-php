<?php

namespace tercom\api;

use dProject\restful\ApiServiceInterface;
use dProject\restful\ApiConnection;

/**
 * @see ApiConnection
 * @see ApiServiceInterface
 * @author Andrew
 */

class SiteService extends ApiServiceInterface
{
	/**
	 * @param ApiConnection $apiConnection
	 * @param string $addon
	 */

	public function __construct(ApiConnection $apiConnection, string $addon)
	{
		parent::__construct($apiConnection, $addon);

		$this->setNamespaceServices(__NAMESPACE__);
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::execute()
	 */

	public function execute()
	{
		return $this->defaultExecute();
	}
}

