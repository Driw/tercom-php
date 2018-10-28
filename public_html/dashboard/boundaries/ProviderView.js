
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
	$('#contact-new').click(function() { Controller.newContact(); });

	$('#modal-contact-add').click(function() { Controller.addContact(); });
	$('#modal-contact-save').click(function() { Controller.saveContact(); });
	$('#contact-commercial-add').click(function() { Controller.addContactPhoneCommercial(); });
	$('#contact-commercial-save').click(function() { Controller.saveContactPhoneCommercial(); });
	$('#contact-commercial-remove').click(function() { Controller.removeContactPhoneCommercial(); });
	$('#contact-otherphone-add').click(function() { Controller.addContactPhoneOtherphone(); });
	$('#contact-otherphone-save').click(function() { Controller.saveContactPhoneOtherphone(); });
	$('#contact-otherphone-remove').click(function() { Controller.removeContactPhoneOtherphone(); });
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

		this.modalContact = $('#modal-contact');
		this.formContact = $('#form-contact');
		this.formContactCommercial = $('#form-contact-commercial');
		this.formContactOtherphone = $('#form-contact-otherphone');
		this.tableConctacts = $('#table-contacts');
		this.tbodyContacts = this.tableConctacts.find('tbody');
		this.dataTableContacts = newDataTables(this.tableConctacts);
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
			beforeSend: function() {
				Controller.form.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi possível obter os dados do fornecedor (id: ' +Controller.idProvider+ ')');
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var provider = parseAdvancedObject(response.result);
				Controller.setProvider(provider);
			},
			complete: function() {
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
			beforeSend: function() {
				Controller.form.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi possível atualizar os dados do fornecedor (id: ' +Controller.idProvider+ ')');
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var provider = parseAdvancedObject(response.result);
				Controller.setProvider(provider);
			},
			complete: function() {
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
		this.setContacts(provider.contacts);
	},
	setCommercial: function(phone)
	{
		Controller.onCommercialExists(phone.id !== 0);

		if (phone.id === 0)
		{
			this.formCommercial.trigger('reset');
			return;
		}

		$(this.formCommercial[0]['commercial[ddd]']).val(phone.ddd);
		$(this.formCommercial[0]['commercial[type]']).val(phone.type);
		$(this.formCommercial[0]['commercial[number]']).val(phone.number).trigger('input');
		$(this.formCommercial[0]['commercial[idPhone]']).val(phone.id);
	},
	setOtherphone: function(phone)
	{
		Controller.onOtherphoneExists(phone.id !== 0);

		if (phone.id === 0)
		{
			this.formOtherphone.trigger('reset');
			return;
		}

		$(this.formOtherphone[0]['otherphone[ddd]']).val(phone.ddd);
		$(this.formOtherphone[0]['otherphone[type]']).val(phone.type);
		$(this.formOtherphone[0]['otherphone[number]']).val(phone.number).trigger('input');
		$(this.formOtherphone[0]['otherphone[idPhone]']).val(phone.id);
	},
	setContacts: function(contacts)
	{
		this.contacts = [];
		contacts.elements.forEach(function(contact)
		{
			Controller.addRowContact(contact);
		});
	},
	addRowContact: function(contact)
	{
		var index = this.contacts.length;
		contact.index = index;
		contact.row = Controller.dataTableContacts.row.add(Controller.newDataTablesRowContact(contact)).draw();
		this.contacts[index] = contact;
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
			beforeSend: function() {
				form.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi adicionar o telefone ' +(phoneCommercial ? 'comercial' : 'secundário'));
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var provider = parseAdvancedObject(response.result);
				Controller.setProvider(provider);
			},
			complete: function() {
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
			beforeSend: function() {
				form.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi atualizar o telefone ' +(phoneCommercial ? 'comercial' : 'secundário'));
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var provider = parseAdvancedObject(response.result);
				Controller.setProvider(provider);
			},
			complete: function() {
				form.LoadingOverlay('hide');
			}
		});
	},
	removeCommercial: function()
	{
		this.removePhone(this.formCommercial);
	},
	removeOtherphone: function()
	{
		this.removePhone(this.formOtherphone);
	},
	removePhone: function(form)
	{
		if (form.valid() === false)
			return;

		var phoneCommercial = form.attr('id') === 'form-commercial';
		var formData = new FormData();

		$.ajax({
			'url': API_ENDPOINT+ 'provider/removePhone/' +Controller.idProvider+ '/' +(phoneCommercial ? 'commercial' : 'otherphone'),
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'contentType': false,
			beforeSend: function() {
				form.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi excluir o telefone ' +(phoneCommercial ? 'comercial' : 'secundário'));
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var provider = parseAdvancedObject(response.result);
				Controller.setProvider(provider);
			},
			complete: function() {
				form.LoadingOverlay('hide');
			}
		});
	},
	newContact: function()
	{
		$('#modal-contact-add').show();
		$('#modal-contact-save').hide();
		$(this.formContact[0].id).val('');
		this.formContact.trigger('reset');
		this.formContactCommercial.trigger('reset');
		this.formContactOtherphone.trigger('reset');
		this.modalContact.modal('show');
		this.formContactCommercial.hide();
		this.formContactOtherphone.hide();
	},
	newDataTablesRowContact: function(contact)
	{
		var index = contact.index;
		var detailButton = '<button type="button" class="btn btn-primary" data-index="' +index+ '" onclick="Controller.onContactDetail(this)">Detalhes</button>';
		var removeButton = '<button type="button" class="btn btn-danger" data-index="' +index+ '" onclick="Controller.onContactRemove(this)">Excluir</button>';

		return [
			contact.name,
			contact.position,
			(contact.commercial.id === 0 ? '-' : contact.commercial.ddd+ ' ' +contact.commercial.number),
			'<div class="btn-group">' +detailButton+removeButton+ '</div>',
		];
	},
	setContact: function(contact)
	{
		if (this.providerContact !== undefined && this.providerContact.id === contact.id)
		{
			contact.index = this.providerContact.index;
			contact.row = this.providerContact.row;
			contact.row.data(Controller.newDataTablesRowContact(contact));
			this.contacts[contact.index] = contact;
		}

		this.providerContact = contact;

		var form = this.formContact[0];
		var formCommercial = this.formContactCommercial[0];
		var formOtherphone = this.formContactOtherphone[0];

		$(form.id).val(contact.id);
		$(form.name).val(contact.name);
		$(form.email).val(contact.email);
		$(form.position).val(contact.position);
		this.formContactCommercial.trigger('reset');
		this.formContactOtherphone.trigger('reset');

		if (contact.commercial.id !== 0)
		{
			$(formCommercial['commercial[ddd]']).val(contact.commercial.ddd);
			$(formCommercial['commercial[type]']).val(contact.commercial.type);
			$(formCommercial['commercial[number]']).val(contact.commercial.number).trigger('input');
			$(formCommercial['commercial[idPhone]']).val(contact.commercial.id);
			$('#contact-commercial-group-add').hide();
			$('#contact-commercial-group-exist').show();
		}
		else
		{
			$('#contact-commercial-group-add').show();
			$('#contact-commercial-group-exist').hide();
		}

		if (contact.otherphone.id !== 0)
		{
			$(formOtherphone['otherphone[ddd]']).val(contact.otherphone.ddd);
			$(formOtherphone['otherphone[type]']).val(contact.otherphone.type);
			$(formOtherphone['otherphone[number]']).val(contact.otherphone.number).trigger('input');
			$(formOtherphone['otherphone[idPhone]']).val(contact.otherphone.id);
			$('#contact-otherphone-group-add').hide();
			$('#contact-otherphone-group-exist').show();
		}
		else
		{
			$('#contact-otherphone-group-add').show();
			$('#contact-otherphone-group-exist').hide();
		}

		this.formContactCommercial.show();
		this.formContactOtherphone.show();
		$('#modal-contact-add').hide();
		$('#modal-contact-save').show();
	},
	addContact: function()
	{
		var form = this.formContact;
		var formData = new FormData(form[0]);

		$.ajax({
			'url': API_ENDPOINT+ 'providerContact/add/' +this.idProvider,
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'contentType': false,
			beforeSend: function() {
				form.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi possível atualizar o contato ' +contact.name);
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var contact = parseAdvancedObject(response.result);
				Controller.addRowContact(contact);
				Controller.setContact(contact);
			},
			complete: function() {
				form.LoadingOverlay('hide');
			}
		});
	},
	saveContact: function()
	{
		var form = this.formContact;
		var formData = new FormData(form[0]);

		$.ajax({
			'url': API_ENDPOINT+ 'providerContact/set/' +this.idProvider,
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'contentType': false,
			beforeSend: function() {
				form.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi possível atualizar o contato ' +contact.name);
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var contact = parseAdvancedObject(response.result);
				Controller.setContact(contact);
			},
			complete: function() {
				form.LoadingOverlay('hide');
			}
		});
	},
	addContactPhoneCommercial: function()
	{
		var form = this.formContactCommercial;
		var formData = new FormData(form[0]);
		formData.append('id', Controller.providerContact.id);

		$.ajax({
			'url': API_ENDPOINT+ 'providerContact/setPhones/' +this.idProvider,
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'contentType': false,
			beforeSend: function() {
				form.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi possível adicionar o telefone comercial a ' +contact.name);
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var contact = parseAdvancedObject(response.result);
				Controller.setContact(contact);
			},
			complete: function() {
				form.LoadingOverlay('hide');
			}
		});
	},
	saveContactPhoneCommercial: function()
	{
		var form = this.formContactCommercial;
		var formData = new FormData(form[0]);
		formData.append('id', Controller.providerContact.id);

		$.ajax({
			'url': API_ENDPOINT+ 'providerContact/setPhones/' +this.idProvider,
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'contentType': false,
			beforeSend: function() {
				form.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi possível atualizar o telefone comercial de ' +contact.name);
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var contact = parseAdvancedObject(response.result);
				Controller.setContact(contact);
			},
			complete: function() {
				form.LoadingOverlay('hide');
			}
		});
	},
	removeContactPhoneCommercial: function()
	{
		var form = this.formContactOtherphone;
		var formData = new FormData();
		formData.append('id', Controller.providerContact.id);

		$.ajax({
			'url': API_ENDPOINT+ 'providerContact/removeCommercial/' +this.idProvider,
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'contentType': false,
			beforeSend: function() {
				form.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi possível adicionar o telefone secundário a ' +contact.name);
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var contact = parseAdvancedObject(response.result);
				Controller.setContact(contact);
			},
			complete: function() {
				form.LoadingOverlay('hide');
			}
		});
	},
	addContactPhoneOtherphone: function()
	{
		var form = this.formContactOtherphone;
		var formData = new FormData(form[0]);
		formData.append('id', Controller.providerContact.id);

		$.ajax({
			'url': API_ENDPOINT+ 'providerContact/setPhones/' +this.idProvider,
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'contentType': false,
			beforeSend: function() {
				form.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi possível adicionar o telefone secundário a ' +contact.name);
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var contact = parseAdvancedObject(response.result);
				Controller.setContact(contact);
			},
			complete: function() {
				form.LoadingOverlay('hide');
			}
		});
	},
	saveContactPhoneOtherphone: function()
	{
		var form = this.formContactOtherphone;
		var formData = new FormData(form[0]);
		formData.append('id', Controller.providerContact.id);

		$.ajax({
			'url': API_ENDPOINT+ 'providerContact/setPhones/' +this.idProvider,
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'contentType': false,
			beforeSend: function() {
				form.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi possível salvar o telefone secundário de ' +contact.name);
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var contact = parseAdvancedObject(response.result);
				Controller.setContact(contact);
			},
			complete: function() {
				form.LoadingOverlay('hide');
			}
		});
	},
	removeContactPhoneOtherphone: function()
	{
		var form = this.formContactOtherphone;
		var formData = new FormData();
		formData.append('id', Controller.providerContact.id);

		$.ajax({
			'url': API_ENDPOINT+ 'providerContact/removeOtherphone/' +this.idProvider,
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'contentType': false,
			beforeSend: function() {
				form.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi possível excluir o telefone secundário de ' +contact.name);
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var contact = parseAdvancedObject(response.result);
				Controller.setContact(contact);
			},
			complete: function() {
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
	onContactDetail: function(button)
	{
		var contact = this.contacts[button.dataset.index];
		this.setContact(contact);
		this.modalContact.modal('show');
	},
	onContactRemove: function(button)
	{
		var tr = $(button).parents('tr');
		var contact = this.contacts[button.dataset.index];
		console.log(contact);
		var formData = new FormData();
		formData.append('id', contact.id);

		$.ajax({
			'url': API_ENDPOINT+ 'providerContact/removeContact/' +this.idProvider,
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'contentType': false,
			beforeSend: function() {
				tr.LoadingOverlay('show');
			},
			error: function() {
				showModalApiError('não foi excluir o contato ' +contact.name);
			},
			success: function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				Controller.dataTableContacts.row(tr).remove().draw();
			},
			complete: function() {
				tr.LoadingOverlay('hide');
			}
		});
	},
}
