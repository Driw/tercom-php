
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
		'webservice': 'productFamily/getAllFamilies',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter famílias de produto'),
	});
};
Webservice.prototype.productGroup_getAll = function(idProductFamily, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productFamily/getCategories/{0}'.format(idProductFamily),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter grupos de produto'),
	});
};
Webservice.prototype.productSubgroup_getAll = function(idProductGroup, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productGroup/getCategories/{0}'.format(idProductGroup),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter subgrupos de produto'),
	});
};
Webservice.prototype.productSector_getAll = function(idProductSubgroup, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productSubGroup/getCategories/{0}'.format(idProductSubgroup),
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
		'webservice': 'productUnit/set/{0}'.format(idProductUnit),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar unidade de produto'),
	});
};
Webservice.prototype.productUnit_remove = function(idProductUnit, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productUnit/remove/{0}'.format(idProductUnit),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('excluir unidade de produto'),
	});
};
Webservice.prototype.productUnit_get = function(idProductUnit, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productUnit/get/{0}'.format(idProductUnit),
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
		'webservice': 'productType/set/{0}'.format(idProductType),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar tipo de produto'),
	});
};
Webservice.prototype.productType_remove = function(idProductType, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productType/remove/{0}'.format(idProductType),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('excluir tipo de produto'),
	});
};
Webservice.prototype.productType_get = function(idProductType, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productType/get/{0}'.format(idProductType),
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
		'webservice': 'product/set/{0}'.format(idProduct),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar produto'),
	});
};
Webservice.prototype.product_setActive = function(idProduct, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'product/setInactive/{0}/no'.format(idProduct),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar produto'),
	});
};
Webservice.prototype.product_setInactive = function(idProduct, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'product/setInactive/{0}/yes'.format(idProduct),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar produto'),
	});
};
Webservice.prototype.product_get = function(idProduct, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'product/get/{0}'.format(idProduct),
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
		'webservice': 'product/search/{0}/{1}'.format(filter, value),
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
		'webservice': 'productPrice/add/{0}'.format(idProduct),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar preço de produto'),
	});
};
Webservice.prototype.productPrice_set = function(idProductPrice, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPrice/set/{0}'.format(idProductPrice),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar preço de produto'),
	});
};
Webservice.prototype.productPrice_remove = function(idProductPrice, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPrice/remove/{0}'.format(idProductPrice),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter preço do produto'),
	});
};
Webservice.prototype.productPrice_get = function(idProductPrice, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPrice/get/{0}'.format(idProductPrice),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter preço do produto'),
	});
};
Webservice.prototype.productPrice_getAll = function(idProduct, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'productPrice/getAll/{0}'.format(idProduct),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter lista de preços do produto'),
	});
};

// Permission

Webservice.prototype.managePermissions_tercom_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'managePermissions/getAll/tercom/{0}'.format(LOGIN_PROFILE_ID),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter lista de permissões'),
	});
};
Webservice.prototype.managePermissions_customer_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'managePermissions/getAll/customer/{0}'.format(LOGIN_PROFILE_ID),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter lista de permissões'),
	});
};

// - Customer

Webservice.prototype.customer_settings = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customer/settings',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter configurações para cliente'),
	});
};
Webservice.prototype.customer_add = function(form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customer/add',
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar cliente'),
	});
};
Webservice.prototype.customer_set = function(idCustomer, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customer/set/{0}'.format(idCustomer),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar cliente'),
	});
};
Webservice.prototype.customer_get = function(idCustomer, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customer/get/{0}'.format(idCustomer),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter cliente'),
	});
};
Webservice.prototype.customer_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customer/getAll',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter todos clientes'),
	});
};
Webservice.prototype.customer_getByCnpj = function(cnpj, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customer/getByCnpj/{0}'.format(cnpj),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter cliente'),
	});
};
Webservice.prototype.customer_search = function(filter, value, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customer/search/{0}/{1}'.format(filter, value),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('procurar cliente'),
	});
};
Webservice.prototype.customer_setActive = function(idCustomer, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customer/setInactive/{0}/no'.format(idCustomer),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar cliente'),
	});
};
Webservice.prototype.customer_setInactive = function(idCustomer, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customer/setInactive/{0}/yes'.format(idCustomer),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar cliente'),
	});
};

// - Address

Webservice.prototype.address_settings = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'address/settings',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter configurações para endereço'),
	});
};
Webservice.prototype.address_add = function(relationship, idRelationship, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'address/add/{0}/{1}'.format(relationship, idRelationship),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar endereço'),
	});
};
Webservice.prototype.address_set = function(relationship, idRelationship, idAddress, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'address/set/{0}/{1}/{2}'.format(relationship, idRelationship, idAddress),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar endereço'),
	});
};
Webservice.prototype.address_remove = function(relationship, idRelationship, idItem, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'address/remove/{0}/{1}/{2}'.format(relationship, idRelationship, idItem),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('excluir endereço'),
	});
}
Webservice.prototype.address_get = function(relationship, idRelationship, idAddress, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'address/get/{0}/{1}/{2}'.format(relationship, idRelationship, idAddress),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter endereço'),
	});
}
Webservice.prototype.address_getAll = function(relationship, idRelationship, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'address/getAll/{0}/{1}'.format(relationship, idRelationship),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter lista de endereços'),
	});
}

// - CustomerProfile

Webservice.prototype.customerProfile_settings = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customerProfile/settings',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter configurações de perfil de cliente'),
	});
};
Webservice.prototype.customerProfile_add = function(form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customerProfile/add',
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar perfil de cliente'),
	});
};
Webservice.prototype.customerProfile_set = function(idCustomerProfile, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customerProfile/set/{0}'.format(idCustomerProfile),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar perfil de cliente'),
	});
};
Webservice.prototype.customerProfile_remove = function(idCustomerProfile, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customerProfile/remove/{0}'.format(idCustomerProfile),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('excluir perfil de cliente'),
	});
};
Webservice.prototype.customerProfile_get = function(idCustomerProfile, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customerProfile/get/{0}'.format(idCustomerProfile),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter perfil de cliente'),
	});
};
Webservice.prototype.customerProfile_getByCustomer = function(idCustomer, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customerProfile/customer/{0}'.format(idCustomer),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter perfil de cliente'),
	});
};
Webservice.prototype.customerProfile_getAll = function(idCustomer, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customerProfile/getAll/{0}'.format(idCustomer),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter todos perfis de cliente'),
	});
};

// - CustomerEmployee

Webservice.prototype.customerEmployee_settings = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customerEmployee/settings',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter configurações de funcionário de cliente'),
	});
};
Webservice.prototype.customerEmployee_add = function(form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customerEmployee/add',
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar funcionário de cliente'),
	});
};
Webservice.prototype.customerEmployee_set = function(idCustomerEmployee, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customerEmployee/set/{0}'.format(idCustomerEmployee),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar funcionário de cliente'),
	});
};
Webservice.prototype.customerEmployee_enabled = function(idCustomerEmployee, enable, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customerEmployee/enable/{0}/{1}'.format(idCustomerEmployee, enable),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('{0} funcionário de cliente'.format(enable ? 'ativar' : 'desativar')),
	});
};
Webservice.prototype.customerEmployee_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customerEmployee/getAll',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter todos funcionários de cliente'),
	});
};
Webservice.prototype.customerEmployee_get = function(idCustomerEmployee, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'customerEmployee/get/{0}'.format(idCustomerEmployee),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter funcionário de cliente'),
	});
};

// - TercomProfile

Webservice.prototype.tercomProfile_settings = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'tercomProfile/settings',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter configurações de perfil de tercom'),
	});
};
Webservice.prototype.tercomProfile_add = function(form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'tercomProfile/add',
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar perfil de tercom'),
	});
};
Webservice.prototype.tercomProfile_set = function(idTercomProfile, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'tercomProfile/set/{0}'.format(idTercomProfile),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar perfil de tercom'),
	});
};
Webservice.prototype.tercomProfile_remove = function(idTercomProfile, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'tercomProfile/remove/{0}'.format(idTercomProfile),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('excluir perfil de tercom'),
	});
};

Webservice.prototype.tercomProfile_get = function(idTercomProfile, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'tercomProfile/get/{0}'.format(idTercomProfile),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter perfil de tercom'),
	});
};
Webservice.prototype.tercomProfile_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'tercomProfile/getAll',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter todos perfis de tercom'),
	});
};

// - Tercom Employee

Webservice.prototype.tercomEmployee_settings = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'tercomEmployee/settings',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter configurações de funcionário TERCOM'),
	});
};
Webservice.prototype.tercomEmployee_add = function(form, listener) {
	this.setOptionsAndExecute({
		'webservice': 'tercomEmployee/add',
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('adicionar funcionário TERCOM'),
	});
};
Webservice.prototype.tercomEmployee_set = function(idTercomEmployee, form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'tercomEmployee/set/{0}'.format(idTercomEmployee),
		'form': form,
		'target': form,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('atualizar funcionário TERCOM'),
	});
};
Webservice.prototype.tercomEmployee_enable = function(idTercomEmployee, enable, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'tercomEmployee/enable/{0}/{1}'.format(idTercomEmployee, enable),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage(' funcionário TERCOM'),
	});
};
Webservice.prototype.tercomEmployee_get = function(idTercomEmployee, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'tercomEmployee/get/{0}'.format(idTercomEmployee),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter funcionário TERCOM'),
	});
};
Webservice.prototype.tercomEmployee_getByProfile = function(idTercomProfile, target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'tercomEmployee/getByProfile/{0}'.format(idTercomProfile),
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter funcionários TERCOM por perfil'),
	});
};
Webservice.prototype.tercomEmployee_getAll = function(target, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'tercomEmployee/getAll',
		'target': target,
		'listener': listener,
		'errorMessage': this.newBaseErrorMessage('obter todos funcionários TERCOM'),
	});
};

const ws = new Webservice();
