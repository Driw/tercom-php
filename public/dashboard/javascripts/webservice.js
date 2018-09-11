
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
	this.setOptions(options);
	this.execute();
};
Webservice.prototype.execute = function()
{
	if (this.webservice === undefined) throw new Exception('webservice não informado');

	var ajax = new Ajax();
	ajax.setApiModalID('api-error-modal');
	ajax.setWebservice(this.webservice);
	ajax.setEnabledLogResponse(DEV);

	if (this.form !== undefined) ajax.setJQueryForm(this.form);
	if (this.target !== undefined) ajax.setTarget(this.target);
	if (this.errorMessage !== undefined) ajax.setErrorMessage(this.errorMessage);
	if (this.errorListener !== undefined) ajax.setErrorListener(this.errorListener);
	if (this.listener !== undefined) ajax.setListener(this.listener);
	if (this.error !== undefined) ajax.setError(this.error);

	ajax.execute();
};
Webservice.prototype.newBaseErrorMessage = function(message)
{
	return 'Falha de comunicação com a API da TERCOM ao ' +message;
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

const ws = new Webservice();
