
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
Webservice.prototype.product_set = function(form, listener)
{
	this.setOptionsAndExecute({
		'webservice': 'product/set',
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
		'webservice': 'product/get' +idProduct,
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
