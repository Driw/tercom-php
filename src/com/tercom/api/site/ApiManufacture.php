<?php

namespace tercom\api\site;

use dProject\Primitive\ArrayData;
use dProject\Primitive\ArrayDataException;
use dProject\Primitive\PostService;
use tercom\api\ApiActionInterface;
use tercom\api\ApiConnection;
use tercom\api\ApiException;
use tercom\api\ApiMissParam;
use tercom\api\ApiResult;
use tercom\control\ManufactureControl;
use tercom\entities\Manufacture;

class ApiManufacture extends ApiActionInterface
{
	public function __construct(ApiConnection $apiConnection, string $apiname, array $vars)
	{
		parent::__contruct($apiConnection, $apiname, $vars);
	}

	public function execute():ApiResult
	{
		ApiConnection::validateInternalCall();

		return $this->defaultExecute();
	}

	public function actionSettings(ArrayData $parameters):ApiManufactureSettings
	{
		$settings = new ApiManufactureSettings();

		return $settings;
	}

	public function actionAdd(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();
		$manufacture = new Manufacture();

		try {

			$manufacture->setFantasyName($POST->getString('fantasyName'));

		} catch (ArrayDataException $e) {
			return new ApiMissParam($e);
		}

		$manufactureControl = new ManufactureControl($this->getMySQL());
		$manufactureControl->add($manufacture);

		$result = new ApiManufactureResult();
		$result->setManufacture($manufacture);

		return $result;
	}

	public function actionSet(ArrayData $parameters):ApiResult
	{
		$POST = PostService::getInstance();

		$idManufacture = $parameters->getInt(0);
		$manufactureControl = new ManufactureControl($this->getMySQL());

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

	public function actionRemove(ArrayData $parameters):ApiResult
	{
		$idManufacture = $parameters->getInt(0);
		$manufactureControl = new ManufactureControl($this->getMySQL());

		if (($manufacture = $manufactureControl->get($idManufacture)) === null)
			throw new ApiException('fabricante não encontrado');

		$result = new ApiManufactureResult();
		$result->setManufacture($manufacture);

		if ($manufactureControl->remove($manufacture))
			$result->setMessage('fabricante excluído com êxtio');
		else
			$result->setMessage('fabricante já foi excluído');

		return $result;
	}

	public function actionGet(ArrayData $parameters):ApiResult
	{
		$idManufacture = $parameters->getInt(0);
		$manufactureControl = new ManufactureControl($this->getMySQL());

		if (($manufacture = $manufactureControl->get($idManufacture)) === null)
			throw new ApiException('fabricante não encontrado');

		$result = new ApiManufactureResult();
		$result->setManufacture($manufacture);

		return $result;
	}

	public function actionSearch(ArrayData $parameters):ApiResult
	{
		$fantasyName = $parameters->getString(0);
		$manufactureControl = new ManufactureControl($this->getMySQL());

		if (($manufacture = $manufactureControl->getByFantasyName($fantasyName)) === null)
			throw new ApiException('fabricante não encontrado');

		$result = new ApiManufactureResult();
		$result->setManufacture($manufacture);

		return $result;
	}
}

?>