<?php

namespace tercom\api\site;

use dProject\Primitive\ArrayDataException;
use dProject\Primitive\PostService;
use dProject\restful\ApiContent;
use dProject\restful\ApiConnection;
use dProject\restful\ApiResult;
use dProject\restful\ApiServiceInterface;
use dProject\restful\exception\ApiException;
use dProject\restful\exception\ApiMissParam;
use tercom\control\ManufactureControl;
use tercom\entities\Manufacture;
use tercom\api\SiteService;
use tercom\core\System;

class ManufactureService extends ApiServiceInterface
{
	public function __construct(ApiConnection $apiConnection, string $apiname, SiteService $parent)
	{
		parent::__contruct($apiConnection, $apiname, $parent);
	}

	public function execute():ApiResult
	{
		return $this->defaultExecute();
	}

	public function actionSettings(ApiContent $content):ApiManufactureSettings
	{
		$settings = new ApiManufactureSettings();

		return $settings;
	}

	public function actionAdd(ApiContent $content):ApiResult
	{
		$POST = PostService::getInstance();
		$manufacture = new Manufacture();

		try {

			$manufacture->setFantasyName($POST->getString('fantasyName'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$manufactureControl = new ManufactureControl(System::getWebConnection());
		$manufactureControl->add($manufacture);

		$result = new ApiManufactureResult();
		$result->setManufacture($manufacture);

		return $result;
	}

	public function actionSet(ApiContent $content):ApiResult
	{
		$POST = PostService::getInstance();

		$idManufacture = $content->getParameters()->getInt(0);
		$manufactureControl = new ManufactureControl(System::getWebConnection());

		if (($manufacture = $manufactureControl->get($idManufacture)) === null)
			throw new ApiException('fabricante não encontrado');

		try {

			if ($POST->isSetted('fantasyName')) $manufacture->setFantasyName($POST->getString('fantasyName'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$result = new ApiManufactureResult();
		$result->setManufacture($manufacture);

		if ($manufactureControl->set($manufacture))
			$result->setMessage('fabricante atualizado com êxtio');
		else
			$result->setMessage('nenhuma dado de fabricante modificado');

		return $result;
	}

	public function actionRemove(ApiContent $content):ApiResult
	{
		$idManufacture = $content->getParameters()->getInt(0);
		$manufactureControl = new ManufactureControl(System::getWebConnection());

		if (($manufacture = $manufactureControl->get($idManufacture)) === null)
			throw new ApiException('fabricante não encontrado');

		$result = new ApiManufactureResult();
		$result->setManufacture($manufacture);

		if ($manufactureControl->remove($manufacture))
			$result->setMessage('fabricante excluído com êxtio');
		else
			$result->setMessage('fabricante já não existe mais');

		return $result;
	}

	public function actionGet(ApiContent $content):ApiResult
	{
		$idManufacture = $content->getParameters()->getInt(0);
		$manufactureControl = new ManufactureControl(System::getWebConnection());

		if (($manufacture = $manufactureControl->get($idManufacture)) === null)
			throw new ApiException('fabricante não encontrado');

		$result = new ApiManufactureResult();
		$result->setManufacture($manufacture);

		return $result;
	}

	public function actionSearch(ApiContent $content):ApiResult
	{
		$fantasyName = $content->getParameters()->getString(0);
		$manufactureControl = new ManufactureControl(System::getWebConnection());

		if (($manufacture = $manufactureControl->getByFantasyName($fantasyName)) === null)
			throw new ApiException('fabricante não encontrado');

		$result = new ApiManufactureResult();
		$result->setManufacture($manufacture);

		return $result;
	}
}

?>