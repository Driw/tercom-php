<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\exceptions\FilterException;
use tercom\api\site\results\ApiResultObject;
use tercom\api\site\results\ApiResultProductPriceSettings;
use tercom\entities\ProductPrice;

/**
 * Serviço de Preço de Produto
 *
 * Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de preço de serviço.
 * Como serviço, oferece as possibilidades de acicionar preços de produto, atualizar preços de produto,
 * atualizar preços de produto, remover preços de produto, obter preços de produto e procurar por nome de preço.
 *
 * @see DefaultSiteService
 * @see ApiResultObject
 * @see ApiResultProductPriceSettings
 *
 * @author Andrew
 */
class ProductPriceService extends DefaultSiteService
{
	/**
	 * Ação para se obter as configurações de limites de cada atributo referente a preço de produto.
	 * @ApiPermissionAnnotation({})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultProductPriceSettings aquisição do resultado com as configurações.
	 */
	public function actionSettings(ApiContent $content): ApiResultProductPriceSettings
	{
		return new ApiResultProductPriceSettings();
	}

	/**
	 * Adiciona um novo preço de produto a partir de dados em POST.
	 * @ApiPermissionAnnotation({"method":"post","params":["idProduct"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado contendo os dados do preço de produto adicionado.
	 */
	public function actionAdd(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProduct = $content->getParameters()->getInt('idProduct');
		$idProvider = $post->getInt('idProvider');
		$idProductPackage = $post->getInt('idProductPackage');
		$product = $this->getProductControl()->get($idProduct);
		$provider = $this->getProviderControl()->get($idProvider);
		$productPackage = $this->getProductPackageControl()->get($idProductPackage);

		$productPrice = new ProductPrice();
		$productPrice->setName($product->getName());
		$productPrice->setAmount($post->getInt('amount'));
		$productPrice->setPrice($post->getFloat('price'));
		$productPrice->setProduct($product);
		$productPrice->setProvider($provider);
		$productPrice->setProductPackage($productPackage);

		if ($post->isSetted('name')) $productPrice->setName($post->getString('name'));

		if (($idManufacturer = $post->getInt('idManufacturer', false)) !== null)
		{
			$manufacturer = $this->getManufacturerControl()->get($idManufacturer);
			$productPrice->setManufacturer($manufacturer);
		}

		if (($idProductType = $post->getInt('idProductType', false)) !== null)
		{
			$productType = $this->getProductTypeControl()->get($idProductType);
			$productPrice->setProductType($productType);
		}

		$this->getProductPriceControl()->add($productPrice);

		$result = new ApiResultObject();
		$result->setResult($productPrice, 'valor de produto para "%s" adicionado com êxito', $productPrice->getName());

		return $result;
	}

	/**
	 * Ação para atualizar os dados de um preço de produto a partir de dados em POST.
	 * @ApiPermissionAnnotation({"method":"post","params":["idProductPrice"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado contendo os dados do preço de produto atualizados.
	 */
	public function actionSet(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idProductPrice = $content->getParameters()->getInt('idProductPrice');
		$productPrice = $this->getProductPriceControl()->get($idProductPrice);

		if ($post->isSetted('name')) $productPrice->setName($post->getString('name'));
		if ($post->isSetted('amount')) $productPrice->setAmount($post->getInt('amount'));
		if ($post->isSetted('price')) $productPrice->setPrice($post->getFloat('price'));

		if (($idProvider = $post->getInt('idProvider', false)) !== null)
		{
			$provider = $this->getProviderControl()->get($idProvider);
			$productPrice->setProvider($provider);
		}

		if (($idManufacturer = $post->getInt('idManufacturer', false)) !== null)
		{
			$manufacturer = $this->getManufacturerControl()->get($idManufacturer);
			$productPrice->setManufacturer($manufacturer);
		}

		if (($idProductType = $post->getInt('idProductType', false)) !== null)
		{
			$productType = $this->getProductTypeControl()->get($idProductType);
			$productPrice->setProductType($productType);
		}

		if (($idProductPackage = $post->getInt('idProductPackage', false)) !== null)
		{
			$productPackage = $this->getProductPackageControl()->get($idProductPackage);
			$productPrice->setProductPackage($productPackage);
		}

		$this->getProductPriceControl()->set($productPrice);

		$result = new ApiResultObject();
		$result->setResult($productPrice, 'valor de produto para "%s" atualizado com êxito', $productPrice->getName());

		return $result;
	}

	/**
	 * Ação para remover os dados de um preço de produto a partir dos parâmetros.
	 * @ApiPermissionAnnotation({"params":["idProductPrice"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado contendo os dados do preço de produto removido.
	 */
	public function actionRemove(ApiContent $content): ApiResultObject
	{
		$idProductPrice = $content->getParameters()->getInt('idProductPrice');
		$productPrice = $this->getProductPriceControl()->get($idProductPrice);
		$this->getProductPriceControl()->remove($productPrice);

		$result = new ApiResultObject();
		$result->setResult($productPrice, 'valor de produto para "%s" excluído com êxito', $productPrice->getName());

		return $result;
	}

	/**
	 * Ação para obter os dados de um preço de produto a partir dos parâmetros.
	 * @ApiPermissionAnnotation({"params":["idProductPrice"]})
	 * @param ApiContent $content conteúdo fornecedido pelo cliente no chamado.
	 * @return ApiResultObject aquisição do resultado contendo os dados do preço de produto obtido.
	 */
	public function actionGet(ApiContent $content): ApiResultObject
	{
		$idProductPrice = $content->getParameters()->getInt('idProductPrice');
		$productPrice = $this->getProductPriceControl()->get($idProductPrice);

		$result = new ApiResultObject();
		$result->setResult($productPrice, 'valor de produto para "%s" obtido com êxito', $productPrice->getName());

		return $result;
	}

	/**
	 * Ação para obter os dados dos preços de produtos registradas no sistema por produto.
	 * @ApiPermissionAnnotation({"params":["idProduct"]})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados de todos os tipos de produto.
	 */
	public function actionGetAll(ApiContent $content): ApiResultObject
	{
		$idProduct = $content->getParameters()->getInt('idProduct');
		$product = $this->getProductControl()->get($idProduct);
		$productPrices = $this->getProductPriceControl()->getByProduct($idProduct);

		$result = new ApiResultObject();
		$result->setResult($productPrices, 'há %d preços de produto para o produto "%s"', $productPrices->size(), $product->getName());

		return $result;
	}

	/**
	 * Ação para obter os dados dos preços de produto filtradas conforme parâmetros.
	 * @ApiPermissionAnnotation({"params":["filter","value"]})
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @throws FilterException somente se o filtro informado não existir na ação.
	 * @return ApiResultObject aquisição do resultado contendo os dados dos preços de produto filtradas.
	 */
	public function actionSearch(ApiContent $content): ApiResultObject
	{
		$filter = $content->getParameters()->getString('filter');

		switch ($filter)
		{
			case 'provider': return $this->searchByProvider($content);
			case 'name': return $this->searchByName($content);
		}

		throw new FilterException($filter);
	}

	/**
	 * Procedimento interno para ação de pesquisa por preços através do fornecedor.
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados dos preços de produto.
	 */
	private function searchByProvider(ApiContent $content): ApiResultObject
	{
		$idProduct = $content->getParameters()->getInt('value');
		$idProvider = $content->getPost()->getInt('idProvider');
		$provider = $this->getProviderControl()->get($idProvider);
		$productPrices = $this->getProductPriceControl()->searchByProvider($idProduct, $idProvider);

		$result = new ApiResultObject();
		$result->setResult($productPrices, 'há %d preços de produto no fornecedor "%s"', $productPrices->size(), $provider->getFantasyName());

		return $result;
	}

	/**
	 * Procedimento interno para ação de pesquisa por preços através do nome do preço.
	 * @param ApiContent $content conteúdo fornecedido na solicitação do serviço.
	 * @return ApiResultObject aquisição do resultado contendo os dados dos preços de produto.
	 */
	private function searchByName(ApiContent $content): ApiResultObject
	{
		$name = $content->getParameters()->getString('value');
		$productPrices = $this->getProductPriceControl()->searchByName($name);

		$result = new ApiResultObject();
		$result->setResult($productPrices, 'há %d preços de produto nomeado com "%s"', $productPrices->size(), $name);

		return $result;
	}
}

