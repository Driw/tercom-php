
$(document).ready(function()
{
	Controller.init();
	Controller.initValidation();
	Controller.loadProvider();

	$('#controller-reload').click(function() { Controller.loadProvider(); });
	$('#controller-save').click(function() { Controller.saveProvider(); });
	$('#commercial-add').click(function() { Controller.addCommercial(); });
	$('#commercial-save').click(function() { Controller.saveCommercial(); });
	$('#commercial-remove').click(function() { Controller.removeCommercial(); });
	$('#otherphone-add').click(function() { Controller.addOtherphone(); });
	$('#otherphone-save').click(function() { Controller.saveOtherphone(); });
	$('#otherphone-remove').click(function() { Controller.removeOtherphone(); });
});

var Controller = Controller ||
{
	init: function()
	{
		this.form = $('#form-view');
		this.formCommercial = $('#form-commercial');
		this.formOtherphone = $('#form-otherphone');
		this.idProvider = this.form[0].id.value;
		this.provider = {};
	},
	initValidation: function()
	{
		$.ajax({
			'url': API_ENDPOINT+ 'provider/settings',
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			beforeSend: function() {
				Controller.form.LoadingOverlay('show');
				Controller.formCommercial.LoadingOverlay('show');
				Controller.formOtherphone.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi possível obter as validações para fornecedores');
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var result = response.result;
				var settings = parseAdvancedObject(response.result);
				Controller.initFormValidation(settings);
				Controller.initPhoneValidation(settings.phoneSettings);
			},
			complete: function() {
				Controller.form.LoadingOverlay('hide');
				Controller.formCommercial.LoadingOverlay('hide');
				Controller.formOtherphone.LoadingOverlay('hide');
			}
		});
	},
	initFormValidation: function(settings)
	{
		this.form.validate({
			'rules': {
				'cnpj': {
					'required': true,
					'remoteapi': {
						'webservice': 'provider/validate/cnpj/{value}/{idProvider}',
						'replacePattern': [ /\D/g, '' ],
						'parameters' : {
							'idProvider': function() { return Controller.form[0].id.value; },
						}
					},
				},
				'companyName': {
					'required': true,
					'rangelength': [ settings.minCompanyNameLen, settings.maxCompanyNameLen ],
				},
				'fantasyName': {
					'required': true,
					'rangelength': [ settings.minFantasyNameLen, settings.maxFantasyNameLen ],
				},
				'spokesman': {
					'required': false,
					'rangelength': [ settings.minSpokesmanLen, settings.maxSpokesmanLen ],
				},
				'site': {
					'required': false,
					'maxlength': settings.maxSiteLen,
				},
				'inactive': {
					'required': false,
				},
			},
		});
	},
	initPhoneValidation: function(settings)
	{
		this.formCommercial.validate({
			'rules': {
				'commercial[ddd]': {
					'required': true,
					'min': settings.minDDD,
					'max': settings.maxDDD,
				},
				'commercial[number]': {
					'required': true,
					'rangelength': [ settings.minNumberLen + 1, settings.maxNumberLen + 1 ],
				},
				'commercial[type]': {
					'required': true,
				},
			},
		});
		this.formOtherphone.validate({
			'rules': {
				'otherphone[ddd]': {
					'required': true,
					'min': settings.minDDD,
					'max': settings.maxDDD,
				},
				'otherphone[number]': {
					'required': true,
					'rangelength': [ settings.minNumberLen + 1, settings.maxNumberLen + 1 ],
				},
				'otherphone[type]': {
					'required': true,
				},
			},
		});
	},
	loadProvider: function()
	{
		$.ajax({
			'url': API_ENDPOINT+ 'provider/get/' +Controller.idProvider,
			'type': 'POST',
			'dataType': 'json',
			'contentType': 'application/json; charset=utf8',
			'beforeSend': function() {
				Controller.form.LoadingOverlay('show');
			},
			'error': function() {
				showModalApiError('não foi possível obter os dados do fornecedor (id: ' +Controller.idProvider+ ')');
			},
			'success': function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var result = response.result;
				var provider = Controller.provider = parseAdvancedObject(response.result);
				Controller.setProvider(provider);
			},
			'complete': function() {
				Controller.form.LoadingOverlay('hide');
			}
		});
	},
	saveProvider: function()
	{
		var formData = this.form.serialize();

		$.ajax({
			'url': API_ENDPOINT+ 'provider/set/' +Controller.idProvider,
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'beforeSend': function() {
				Controller.form.LoadingOverlay('show');
			},
			'error': function() {
				showModalApiError('não foi possível atualizar os dados do fornecedor (id: ' +Controller.idProvider+ ')');
			},
			'success': function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var result = response.result;
				var provider = Controller.provider = parseAdvancedObject(response.result);
				Controller.setProvider(provider);
			},
			'complete': function() {
				Controller.form.LoadingOverlay('hide');
			}
		});
	},
	setProvider: function(provider)
	{
		$(this.form[0].cnpj).val(provider.cnpj).trigger('input');
		this.idProvider = provider.id;
		this.form[0].companyName.value = provider.companyName;
		this.form[0].fantasyName.value = provider.fantasyName;
		this.form[0].spokesman.value = provider.spokesman;
		this.form[0].site.value = provider.site;
		this.setCommercial(provider.commercial);
		this.setOtherphone(provider.otherphone);
	},
	setCommercial: function(phone)
	{
		Controller.onCommercialExists(phone.id !== 0);

		if (phone.id === 0)
			return;

		$(this.formCommercial[0]['commercial[id]']).val(phone.id);
		$(this.formCommercial[0]['commercial[ddd]']).val(phone.ddd);
		$(this.formCommercial[0]['commercial[type]']).val(phone.type);
		$(this.formCommercial[0]['commercial[number]']).val(phone.number).trigger('input');
	},
	setOtherphone: function(phone)
	{
		Controller.onOtherphoneExists(phone.id !== 0);

		if (phone.id === 0)
			return;

		$(this.formOtherphone[0]['otherphone[id]']).val(phone.id);
		$(this.formOtherphone[0]['otherphone[ddd]']).val(phone.ddd);
		$(this.formOtherphone[0]['otherphone[type]']).val(phone.type);
		$(this.formOtherphone[0]['otherphone[number]']).val(phone.number).trigger('input');
	},
	addCommercial: function()
	{
		this.addPhone(this.formCommercial);
	},
	addOtherphone: function()
	{
		this.addPhone(this.formOtherphone);
	},
	addPhone: function(form)
	{
		if (form.valid() === false)
			return;

		var phoneCommercial = form.attr('id') === 'form-commercial';
		var formData = new FormData(form[0]);

		$.ajax({
			'url': API_ENDPOINT+ 'provider/setPhones/' +Controller.idProvider,
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'contentType': false,
			'beforeSend': function() {
				form.LoadingOverlay('show');
			},
			'error': function() {
				showModalApiError('não foi adicionar o telefone ' +(phoneCommercial ? 'comercial' : 'secundário'));
			},
			'success': function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var provider = parseAdvancedObject(response.result);
				Controller.setProvider(provider);
			},
			'complete': function() {
				form.LoadingOverlay('hide');
			}
		});
	},
	saveCommercial: function()
	{
		this.savePhone(this.formCommercial);
	},
	saveOtherphone: function()
	{
		this.savePhone(this.formOtherphone);
	},
	savePhone: function(form)
	{
		if (form.valid() === false)
			return;

		var phoneCommercial = form.attr('id') === 'form-commercial';
		var formData = new FormData(form[0]);

		$.ajax({
			'url': API_ENDPOINT+ 'provider/setPhones/' +Controller.idProvider,
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'contentType': false,
			'beforeSend': function() {
				form.LoadingOverlay('show');
			},
			'error': function() {
				showModalApiError('não foi atualizar o telefone ' +(phoneCommercial ? 'comercial' : 'secundário'));
			},
			'success': function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var provider = parseAdvancedObject(response.result);
				Controller.setProvider(provider);
			},
			'complete': function() {
				form.LoadingOverlay('hide');
			}
		});
	},
	onCommercialExists: function(exist)
	{
		if (exist)
			$.when($('#commercial-group-add').fadeOut('slow')).done(function() { $('#commercial-group-exist').fadeIn('slow'); });
		else
			$.when($('#commercial-group-exist').fadeOut('slow')).done(function() { $('#commercial-group-add').fadeIn('slow'); });
	},
	onOtherphoneExists: function(exist)
	{
		if (exist)
			$.when($('#otherphone-group-add').fadeOut('slow')).done(function() { $('#otherphone-group-exist').fadeIn('slow'); });
		else
			$.when($('#otherphone-group-exist').fadeOut('slow')).done(function() { $('#otherphone-group-add').fadeIn('slow'); });
	},
}
