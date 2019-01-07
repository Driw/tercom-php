<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\api\exceptions\RelationshipException;

/**
 * @see DefaultSiteService
 * @author andrews
 */
abstract class RelationshipService extends DefaultSiteService
{
	/**
	 *
	 * @return string
	 */
	public abstract function getRelationshipName(): string;

	/**
	 *
	 * @param ApiContent $content
	 * @return ApiResult
	 */
	public abstract function actionSettings(ApiContent $content): ApiResult;

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["relationship","idRelationship"]})
	 * @param ApiContent $content
	 * @return ApiResult
	 */
	public function actionAdd(ApiContent $content): ApiResult
	{
		$parameters = $content->getParameters();
		$relationship = $parameters->getString('relationship');
		$idRelationship = $parameters->getInt('idRelationship');
		$method_name = sprintf('add%s%s', ucfirst($relationship), $this->getRelationshipName());

		if (method_exists($this, $method_name))
			return $this->$method_name($content, $idRelationship);

		throw new RelationshipException($relationship);
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"method":"post","params":["relationship","idRelationship","idItem"]})
	 * @param ApiContent $content
	 * @throws RelationshipException
	 * @return ApiResult
	 */
	public function actionSet(ApiContent $content): ApiResult
	{
		$parameters = $content->getParameters();
		$relationship = $parameters->getString('relationship');
		$idRelationship = $parameters->getInt('idRelationship');
		$idItem = $parameters->getInt('idItem');
		$method_name = sprintf('set%s%s', ucfirst($relationship), $this->getRelationshipName());

		if (method_exists($this, $method_name))
			return $this->$method_name($content, $idRelationship, $idItem);

		throw new RelationshipException($relationship);
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["relationship","idRelationship","idItem"]})
	 * @param ApiContent $content
	 * @throws RelationshipException
	 * @return ApiResult
	 */
	public function actionRemove(ApiContent $content): ApiResult
	{
		$parameters = $content->getParameters();
		$relationship = $parameters->getString('relationship');
		$idRelationship = $parameters->getInt('idRelationship');
		$idItem = $parameters->getInt('idItem');
		$method_name = sprintf('remove%s%s', ucfirst($relationship), $this->getRelationshipName());

		if (method_exists($this, $method_name))
			return $this->$method_name($content, $idRelationship, $idItem);

		throw new RelationshipException($relationship);
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["relationship","idRelationship","idItem"]})
	 * @param ApiContent $content
	 * @throws RelationshipException
	 * @return ApiResult
	 */
	public function actionGet(ApiContent $content): ApiResult
	{
		$parameters = $content->getParameters();
		$relationship = $parameters->getString('relationship');
		$idRelationship = $parameters->getInt('idRelationship');
		$idItem = $parameters->getInt('idItem');
		$method_name = sprintf('get%s%s', ucfirst($relationship), $this->getRelationshipName());

		if (method_exists($this, $method_name))
			return $this->$method_name($content, $idRelationship, $idItem);

		throw new RelationshipException($relationship);
	}

	/**
	 *
	 * @ApiPermissionAnnotation({"params":["relationship","idRelationship"]})
	 * @param ApiContent $content
	 * @throws RelationshipException
	 * @return ApiResult
	 */
	public function actionGetAll(ApiContent $content): ApiResult
	{
		$parameters = $content->getParameters();
		$relationship = $parameters->getString('relationship');
		$idRelationship = $parameters->getInt('idRelationship');
		$method_name = sprintf('getAll%s%s', ucfirst($relationship), $this->getRelationshipName());

		if (method_exists($this, $method_name))
			return $this->$method_name($content, $idRelationship);

		throw new RelationshipException($relationship);
	}
}

