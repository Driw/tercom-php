<?php

namespace tercom\api\site\results;

use tercom\entities\lists\Providers;
use dProject\Primitive\AdvancedObject;

/**
 * @see AdvancedObject
 * @see Providers
 * @author Andrew
 */

class ProvidersPageResult extends AdvancedObject
{
	/**
	 * @var int
	 */
	private $pageCount;
	/**
	 * @var Providers
	 */
	private $providers;

	/**
	 *
	 */

	public function __construct()
	{
		$this->pageCount = 0;
		$this->providers = new Providers();
	}

	/**
	 * @return int
	 */

	public function getPageCount(): int
	{
		return $this->pageCount;
	}

	/**
	 * @param int $pageCount
	 */

	public function setPageCount(int $pageCount)
	{
		$this->pageCount = $pageCount;
	}

	/**
	 * @return Providers
	 */

	public function getProviders(): Providers
	{
		return $this->providers;
	}

	/**
	 * @param Providers $providers
	 */

	public function setProviders(Providers $providers)
	{
		$this->providers = $providers;
	}
}

