<?php

namespace tercom\control;

use tercom\entities\Entity;
use tercom\ListEntity;

/**
 * @see Entity
 * @see ListEntity
 * @author andrews
 */
interface RelationshipControl
{
	/**
	 *
	 * @param Entity $relationship
	 * @param Entity $item
	 * @return bool
	 */
	public function addRelationship($relationship, $item): bool;

	/**
	 *
	 * @param Entity $relationship
	 * @param Entity $item
	 * @return bool
	 */
	public function setRelationship($relationship, $item): bool;

	/**
	 *
	 * @param Entity $relationship
	 * @param Entity $item
	 * @return bool
	 */
	public function removeRelationship($relationship, $item): bool;

	/**
	 *
	 * @param Entity $relationship
	 * @param int $idRelationship
	 * @return Entity
	 */
	public function getRelationship($relationship, int $idRelationship);

	/**
	 *
	 * @param ListEntity $relationship
	 */
	public function getRelationships($relationship);

	/**
	 *
	 * @param Entity $relationship
	 * @param Entity $item
	 * @return bool
	 */
	public function hasRelationship($relationship, $item): bool;
}

