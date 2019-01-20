
/* COMUNICAÇÃO COM OS WEBSERVICES */

function Webservice(options)
{
	this.setOptions(options);
}
Webservice.prototype.setOptions = function(options)
{
	$.extend(this, options);
};
Webservice.prototype.setOptionsAndExecute = function(options)
{
	if (typeof(options.form) === 'undefined')
		options.form = new FormData();

	this.setOptions(options);
	this.execute();
};
Webservice.prototype.execute = function()
{
	if (this.webservice === 'undefined') throw new Exception('webservice não informado');

	var ajax = new Ajax();
	ajax.setApiModalID('api-error-modal');
	ajax.setWebservice(this.webservice);
	ajax.setEnabledLogResponse(DEV);

	if (this.form !== 'undefined')
	{
		if (this.form instanceof FormData)
			ajax.setFormData(this.form);
		else if (this.form instanceof jQuery)
			ajax.setJQueryForm(this.form);
		else if (typeof(this.form))
			ajax.setObjectData(this.form);
	}

	if (this.target !== 'undefined') ajax.setTarget(this.target);
	if (this.errorMessage !== 'undefined') ajax.setErrorMessage(this.errorMessage);
	if (this.errorListener !== 'undefined') ajax.setErrorListener(this.errorListener);
	if (this.listener !== 'undefined') ajax.setListener(this.listener);
	if (this.error !== 'undefined') ajax.setError(this.error);

	ajax.execute();
};
Webservice.prototype.newBaseErrorMessage = function(message)
{
	return 'Falha de comunicação com a API da TERCOM ao ' +message;
};

// -- Provider

Webservice.prototype.provider_settings = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'provider/settings',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter configuração para fornecedor'),
	});
};
Webservice.prototype.provider_add = function(form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'provider/add',
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar fornecedor'),
	});
};
Webservice.prototype.provider_set = function(idProvider, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'provider/set/{0}'.format(idProvider),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar fornecedor'),
	});
};
Webservice.prototype.provider_setPhones = function(idProvider, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'provider/setPhones/{0}'.format(idProvider),
		'target': form,
		'form': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar telefone do fornecedor'),
	});
};
Webservice.prototype.provider_removeCommercial = function(idProvider, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'provider/removeCommercial/{0}'.format(idProvider),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('excluir telefone comercial'),
	});
};
Webservice.prototype.provider_removeOtherphone = function(idProvider, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'provider/removeOtherphone/{0}'.format(idProvider),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('excluir telefone secundário'),
	});
};
Webservice.prototype.provider_get = function(idProvider, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'provider/get/{0}'.format(idProvider),
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter fornecedor'),
	});
};
Webservice.prototype.provider_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'provider/getAll',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter os fornecedores'),
	});
};
Webservice.prototype.provider_search = function(filter, value, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'provider/search/{0}/{1}'.format(filter, encodeURIComponent(value)),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('procurar fornecedores'),
	});
};

// -- ProviderContact

Webservice.prototype.providerContact_add = function(idProvider, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'providerContact/add/{0}'.format(idProvider),
		'target': form,
		'form': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicoinar contato de fornecedor'),
	});
};
Webservice.prototype.providerContact_set = function(idProvider, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'providerContact/set/{0}'.format(idProvider),
		'target': form,
		'form': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar contato de fornecedor'),
	});
};
Webservice.prototype.providerContact_setPhones = function(idProvider, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'providerContact/setPhones/{0}'.format(idProvider),
		'target': form,
		'form': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar telefones do contato de fornecedor'),
	});
};
Webservice.prototype.providerContact_removeCommercial = function(idProvider, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'providerContact/removeCommercial/{0}'.format(idProvider),
		'target': form,
		'form': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('excluir telefone comercial do contato de fornecedor'),
	});
};
Webservice.prototype.providerContact_removeOtherphone = function(idProvider, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'providerContact/removeOtherphone/{0}'.format(idProvider),
		'target': form,
		'form': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('excluir telefone secundário do contato de fornecedor'),
	});
};
Webservice.prototype.providerContact_removeContact = function(idProvider, form, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'providerContact/removeContact/{0}'.format(idProvider),
		'target': target,
		'form': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('excluir contato de fornecedor'),
	});
};
Webservice.prototype.providerContact_getAll = function(idProvider, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'providerContact/getContacts/{0}'.format(idProvider),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter a lista de contatos do fornecedor'),
	});
};

// -- Manufacturer

Webservice.prototype.manufacturer_settings = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'manufacturer/settings',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter configuração para fabricante'),
	});
};
Webservice.prototype.manufacturer_add = function(form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'manufacturer/add',
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar fabricante'),
	});
};
Webservice.prototype.manufacturer_set = function(idManufacturer, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'manufacturer/set/{0}'.format(idManufacturer),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar fabricante'),
	});
};
Webservice.prototype.manufacturer_remove = function(idManufacturer, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'manufacturer/remove/{0}'.format(idManufacturer),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('excluir fabricante'),
	});
};
Webservice.prototype.manufacturer_get = function(idManufacturer, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'manufacturer/get/{0}'.format(idManufacturer),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter fabricante'),
	});
};
Webservice.prototype.manufacturer_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'manufacturer/getAll',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter os fabricantes'),
	});
};
Webservice.prototype.manufacturer_search = function(filter, value, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'manufacturer/search/{0}/{1}'.format(filter, encodeURIComponent(value)),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('procurar por fabricantes'),
	});
};

// ProductFamily

Webservice.prototype.productFamily_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productFamily/getAll',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter famílias de produto'),
	});
};
Webservice.prototype.productGroup_getAll = function(idProductFamily, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productFamily/getGroups/' +idProductFamily,
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter grupos de produto'),
	});
};
Webservice.prototype.productSubgroup_getAll = function(idProductGroup, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productGroup/getSubGroups/' +idProductGroup,
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter subgrupos de produto'),
	});
};
Webservice.prototype.productSector_getAll = function(idProductSubgroup, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productSubGroup/getSectores/'+ idProductSubgroup,
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter setores de produto'),
	});
};

// -- ProductPackage

Webservice.prototype.productPackage_settings = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPackage/settings',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter configurações para embalagem de produto'),
	});
};
Webservice.prototype.productPackage_add = function(form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPackage/add',
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar embalagem de produto'),
	});
};
Webservice.prototype.productPackage_set = function(idProductPackage, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPackage/set/{0}'.format(idProductPackage),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar embalagem de produto'),
	});
};
Webservice.prototype.productPackage_remove = function(idProductPackage, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPackage/remove/{0}'.format(idProductPackage),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('excluir embalagem de produto'),
	});
};
Webservice.prototype.productPackage_get = function(idProductPackage, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPackage/get/{0}'.format(idProductPackage),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter embalagem de produto'),
	});
};
Webservice.prototype.productPackage_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPackage/getAll',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter as embalagens de produto'),
	});
};

// -- ProductUnit

Webservice.prototype.productUnit_settings = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productUnit/settings',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter configuração para unidade de produto'),
	});
};
Webservice.prototype.productUnit_add = function(form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productUnit/add',
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar unidade de produto'),
	});
};
Webservice.prototype.productUnit_set = function(idProductUnit, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productUnit/set/' +idProductUnit,
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar unidade de produto'),
	});
};
Webservice.prototype.productUnit_get = function(idProductUnit, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productUnit/get/' +idProductUnit,
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter unidade de produto'),
	});
};
Webservice.prototype.productUnit_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productUnit/getAll',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter unidades de produto'),
	});
};

// -- ProductType

Webservice.prototype.productType_settings = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productType/settings',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter configurações para tipo de produto'),
	});
};
Webservice.prototype.productType_add = function(form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productType/add',
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar tipo de produto'),
	});
};
Webservice.prototype.productType_set = function(idProductType, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productType/set/' +idProductType,
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar tipo de produto'),
	});
};
Webservice.prototype.productType_get = function(idProductType, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productType/get/' +idProductType,
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter tipo de produto'),
	});
};
Webservice.prototype.productType_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productType/getAll',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter os tipos de produto'),
	});
};

// -- Product

Webservice.prototype.product_settings = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'product/settings',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('configurar produto'),
	});
};
Webservice.prototype.product_add = function(form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'product/add',
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar produto'),
	});
};
Webservice.prototype.product_set = function(idProduct, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'product/set/' +idProduct,
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar produto'),
	});
};
Webservice.prototype.product_setActive = function(idProduct, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'product/setInactive/' +idProduct+ '/no',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar produto'),
	});
};
Webservice.prototype.product_setInactive = function(idProduct, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'product/setInactive/' +idProduct+ '/yes',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar produto'),
	});
};
Webservice.prototype.product_get = function(idProduct, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'product/get/' +idProduct,
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter produto'),
	});
};
Webservice.prototype.product_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'product/getAll',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter todos os produtos'),
	});
};
Webservice.prototype.product_search = function(filter, value, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'product/search/' +filter+ '/' +value,
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('procurar produto'),
	});
};

// -- ProductPrice

Webservice.prototype.productPrice_settings = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPrice/settings',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter configurações para preço de produto'),
	});
};
Webservice.prototype.productPrice_add = function(idProduct, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPrice/add/' +idProduct,
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar preço de produto'),
	});
};
Webservice.prototype.productPrice_set = function(idProductPrice, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPrice/set/' +idProductPrice,
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar preço de produto'),
	});
};
Webservice.prototype.productPrice_remove = function(idProductPrice, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPrice/remove/' +idProductPrice,
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter preço do produto'),
	});
};
Webservice.prototype.productPrice_get = function(idProductPrice, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPrice/get/' +idProductPrice,
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter preço do produto'),
	});
};
Webservice.prototype.productPrice_getAll = function(idProduct, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPrice/getAll/' +idProduct,
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter lista de preços do produto'),
	});
};

// Permission

Webservice.prototype.managePermissions_tercom_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'managePermissions/getAll/tercom/' +LOGIN_PROFILE_ID,
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter lista de permissões'),
	});
};
Webservice.prototype.managePermissions_customer_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'managePermissions/getAll/customer/' +LOGIN_PROFILE_ID,
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter lista de permissões'),
	});
};

const ws = new Webservice();
