<?php

namespace tercom\entities;

/**
 * @see Login
 * @author Andrew
 */
class LoginTercom extends Login
{
	/**
	 * @var TercomEmployee
	 */
	private $tercomEmployee;

	/**
	 * @return TercomEmployee
	 */
	public function getTercomEmployee(): TercomEmployee
	{
		return $this->tercomEmployee;
	}

	/**
	 * @param TercomEmployee $tercomEmployee
	 */
	public function setTercomEmployee($tercomEmployee)
	{
		$this->tercomEmployee = $tercomEmployee;
	}

	/**
	 * @return int
	 */
	public function getTercomEmployeeId(): int
	{
		return $this->tercomEmployee === null ? 0 : $this->tercomEmployee->getId();
	}
}

