<?php

namespace tercom\boundary\dashboard;

use dProject\restful\ApiConnectionAdapter;
use tercom\boundary\dashboard\template\ErrorTemplate;

/**
 * @see ApiConnectionAdapter
 * @author Andrew
 */

class BoundaryListener extends ApiConnectionAdapter
{
	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiConnectionAdapter::onApiException()
	 */

	public function onApiException($connection, $e)
	{
		$notfoundTemplate = new ErrorTemplate($connection);
		$notfoundTemplate->init();
		$notfoundTemplate->setException($e);
		die($notfoundTemplate->callIndex()->toApiArray()[0]->show());
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiConnectionAdapter::onGenericException()
	 */

	public function onGenericException($connection, $e)
	{
		$notfoundTemplate = new ErrorTemplate();
		$notfoundTemplate->init();
		$notfoundTemplate->setException($e);
		die($notfoundTemplate->callIndex()->toApiArray()[0]->show());
	}
}

