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
	 */
	public function addRelationship($relationship, $item): void;

	/**
	 *
	 * @param Entity $relationship
	 * @param Entity $item
	 */
	public function setRelationship($relationship, $item): void;

	/**
	 *
	 * @param Entity $relationship
	 * @param Entity $item
	 */
	public function removeRelationship($relationship, $item): void;

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

