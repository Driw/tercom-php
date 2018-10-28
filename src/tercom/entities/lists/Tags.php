<?php

namespace tercom\entities\lists;

use tercom\ArrayList;

/**
 * @see ArrayList
 * @author Andrew
 */
class Tags extends ArrayList
{
	/**
	 * @param string $tags
	 * @return Tags
	 */
	public function parseString(string $tags): Tags
	{
		$this->clear();

		foreach (explode(';', $tags) as $tag)
			$this->add($tag);

		return $this;
	}

	/**
	 * @return string
	 */
	public function getString(): string
	{
		return implode(';', $this->elements);
	}
}

?>