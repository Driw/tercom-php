
const API_ENDPOINT = window.location.protocol+ '//' +window.location.host+ '/api/site/';
const API_TYPE_GET = 'GET';
const API_TYPE_POST = 'POST';
const API_STATUS_SUCCESSFUL = 1;

const API_DATA_TYPE_JSON = 'json';
const API_CONTENT_TYPE_JSON = 'application/json; charset=utf-8';
const API_CONTENT_TYPE_FORMDATA = 'application/x-www-form-urlencoded; charset=utf-8';

function Ajax(options)
{
	this.vars = {};
	this.setDefaultOptions();
	if (options !== undefined)
	$.extend(this.vars, options);
}
Ajax.prototype.setDefaultOptions = function()
{
	this.setUrl(API_ENDPOINT);
	this.setType(API_TYPE_POST);
	this.setJson('{}');
	this.setCache(false);
	this.setDataType(API_DATA_TYPE_JSON);
	this.setBeforeSend(this.defaultBeforeSend);
	this.setComplete(this.defaultComplete);
	this.setSuccess(this.defaultSuccess);
	this.setError(this.defaultError);
	this.setErrorMessage('falha na comunicação com a API do sistema');
};
Ajax.prototype.setApiModalID = function(modal)
{
	var modalID = (modal instanceof jQuery) ? modal[0].id : (modal instanceof HTMLElement) ? modal.id : '#' +modal;
	this.modalID = modalID;
	this.modal = $(modalID);
	this.modalLabel = $(modalID+ '-label');
	this.modalMessage = $(modalID+ '-message');
};
Ajax.prototype.setEnabledLogResponse = function(enabledLogResponse)
{
	this.vars.enabledLogResponse = enabledLogResponse;
};
Ajax.prototype.setErrorMessage = function(errorMessage)
{
	this.vars.errorMessage = errorMessage;
}
Ajax.prototype.setTarget = function(target)
{
	this.vars.target = (target instanceof jQuery) ? target : $(target);
};
Ajax.prototype.setUrl = function(url)
{
	this.vars.url = url;
};
Ajax.prototype.setType = function(type)
{
	this.vars.type = type;
};
Ajax.prototype.setWebservice = function(webservice)
{
	this.setUrl(API_ENDPOINT + webservice);
};
Ajax.prototype.setData = function(data)
{
	this.vars.data = data;
};
Ajax.prototype.setJson = function(json)
{
	this.setData(json);
	this.setProcessData(true);
	this.setContentType(API_CONTENT_TYPE_JSON);
};
Ajax.prototype.setForm = function(form)
{
	var formData = new FormData(form);
	this.setFormData(formData);
};
Ajax.prototype.setJQueryForm = function(form)
{
	var formData = new FormData(form[0]);
	this.setFormData(formData);
};
Ajax.prototype.setFormData = function(formData)
{
	if (typeof(LOGIN_ID) !== 'undefined' &&
		typeof(LOGIN_TOKEN) !== 'undefined' &&
		typeof(LOGIN_EMPLOYEE_ID) !== 'undefined' &&
		typeof(IS_LOGIN_TERCOM) !== 'undefined')
	{
		formData.append('login_id', LOGIN_ID);
		formData.append('login_token', LOGIN_TOKEN);

		if (IS_LOGIN_TERCOM)
			formData.append('login_tercomEmployee', LOGIN_EMPLOYEE_ID);
		else
			formData.append('login_customerEmployee', LOGIN_EMPLOYEE_ID);
	}

	this.setData(formData);
	this.setProcessData(false);
	this.setContentType(false);
};
Ajax.prototype.setObjectData = function(object)
{
	$.extend(object, Dashboard.getAuthenticateData());
	this.setData(object);
	delete this['contentType'];
	delete this['processData'];
}
Ajax.prototype.setCache = function(cache)
{
	this.vars.cache = cache;
};
Ajax.prototype.setDataType = function(dataType)
{
	this.vars.dataType = dataType;
};
Ajax.prototype.setProcessData = function(processData)
{
	this.vars.processData = processData;
};
Ajax.prototype.setContentType = function(contentType)
{
	this.vars.contentType = contentType;
};
Ajax.prototype.setBeforeSend = function(beforeSend)
{
	this.vars.beforeSend = beforeSend;
};
Ajax.prototype.setComplete = function(complete)
{
	this.vars.complete = complete;
};
Ajax.prototype.setSuccess = function(success)
{
	this.vars.success = success;
};
Ajax.prototype.setError = function(error)
{
	this.vars.error = error;
};
Ajax.prototype.setListener = function(listener)
{
	this.vars.listener = listener;
};
Ajax.prototype.setErrorListener = function(errorListener)
{
	this.vars.errorListener = errorListener;
};
Ajax.prototype.defaultBeforeSend = function()
{
	this.openLoadingOverlay();
};
Ajax.prototype.defaultError = function()
{
	this.ajax.modalError('Erro de API', this.errorMessage);
};
Ajax.prototype.defaultSuccess = function(response)
{
	this.ajax.response = parseAdvancedObject(response);

	if (this.enabledLogResponse)
		console.log(response);

	if (response.status !== API_STATUS_SUCCESSFUL)
	{
		this.ajax.modalError('Erro de API', response.message);

		if (this.errorListener !== undefined)
			this.errorListener(response);

		return false;
	}

	var result = response.result;
	var message = response.message;

	if (this.listener !== undefined)
		this.listener(result, message, response);
};
Ajax.prototype.defaultComplete = function()
{
	this.closeLoadingOverlay();
};
Ajax.prototype.modalError = function(title, message)
{
	this.modalMessage.html(message);
	this.modalLabel.html(title);
	this.modal.modal('show');
}
Ajax.prototype.execute = function()
{
	var ajax = this;
	return $.ajax($.extend(this.vars, {
		'ajax': ajax,
		openLoadingOverlay: function()
		{
			if (this.target !== undefined)
				this.target.LoadingOverlay('show');
		},
		closeLoadingOverlay: function()
		{
			if (this.target !== undefined)
				this.target.LoadingOverlay('hide');
		},
	}));
};
